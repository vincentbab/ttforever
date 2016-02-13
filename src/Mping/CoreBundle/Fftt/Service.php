<?php

namespace Mping\CoreBundle\Fftt;

/**
 * @author VincentBab vincentbab@gmail.com
 */
class Service
{
    /**
     * @var string $appId ID de l'application fourni par la FFTT (ex: AM001)
     */
    protected $appId;

    /**
     * @var string $appKey Mot de passe fourni par la FFTT
     */
    protected $appKey;

    /**
     * @var string $serial Serial de l'utilisateur
     */
    protected $serial;

    /**
     * @var object $cache
     */
    protected $cache;

    /**
     * @var object $logger
     */
    protected $logger;
    

    public function __construct($appId, $appKey)
    {
        $this->appId = $appId;
        $this->appKey = $appKey;

        libxml_use_internal_errors(true);
    }

    public function getAppId()
    {
        return $this->appId;
    }

    public function getAppKey()
    {
        return $this->appKey;
    }

    public function setSerial($serial)
    {
        $this->serial = $serial;

        return $this;
    }

    public function getSerial()
    {
        return $this->serial;
    }

    public function setCache($cache)
    {
        $this->cache = $cache;

        return $this;
    }

    public function getCache()
    {
        return $this->cache;
    }

    public function setLogger($logger)
    {
        $this->logger = $logger;

        return $this;
    }

    public function getLogger()
    {
        return $this->logger;
    }

    public function initialization()
    {
        return Service::getObject($this->getData('http://www.fftt.com/mobile/pxml/xml_initialisation.php', array()));
    }

    public function getClubsByDepartement($departement)
    {
        return $this->getCachedData("clubs_{$departement}", 3600*24*7, function($service) use ($departement) {
            return Service::getCollection($service->getData('http://www.fftt.com/mobile/pxml/xml_club_dep2.php', array('dep' => $departement)), 'club');
        });
    }

    public function getClubsByVille($ville)
    {
        return $this->getCachedData("clubs_{$ville}", 3600*24*7, function($service) use ($ville) {
            return Service::getCollection($service->getData('http://www.fftt.com/mobile/pxml/xml_club_b.php', array('ville' => $ville)), 'club');
        });
    }

    public function getClubsByCodePostal($cp)
    {
        return $this->getCachedData("clubs_{$cp}", 3600*24*7, function($service) use ($cp) {
            return Service::getCollection($service->getData('http://www.fftt.com/mobile/pxml/xml_club_b.php', array('code' => $cp)), 'club');
        });
    }

    public function searchClubs($search)
    {
        if (strlen($search) == 2 && is_numeric(substr($search, 0, 1))) {
            return $this->getClubsByDepartement($search);
        }

        if (strlen($search) == 5 && is_numeric(substr($search, 0, 1))) {
            return $this->getClubsByCodePostal($search);
        }

        if (strlen($search) > 5 && is_numeric(substr($search, 0, 2))) {
            $result = $this->getClub($search);
            return $result ? array($result) : array();
        }

        $tokens = explode(' ', $search);
        if (count($tokens) > 1 && is_numeric($tokens[count($tokens) - 1])) { // Dernier token est un chiffre (numéro d'équipe)
            array_pop($tokens);
            $search = implode(' ', $tokens);
        }

        return $this->getClubsByVille($search);
    }

    public function getClub($numero)
    {
        return $this->getCachedData("club_{$numero}", 3600*24*1, function($service) use ($numero) {
            return Service::getObject($service->getData('http://www.fftt.com/mobile/pxml/xml_club_detail.php', array('club' => $numero)), 'club');
        });
    }
    public function cleanClub($numero)
    {
        if (!$this->cache) {
            return;
        }

        $this->cache->delete("club_{$numero}");
        $this->cache->delete("clubjoueurs_{$numero}");
        $this->cache->delete("licencesclub_{$numero}");
        $this->cache->delete("clubequipes_{$numero}_M");
        $this->cache->delete("clubequipes_{$numero}_F");
        $this->cache->delete("clubequipes_{$numero}_");
    }

    public function getJoueur($licence, $raw = false)
    {
        $joueur = $this->getCachedData("joueur_{$licence}", 3600*24*1, function($service) use ($licence) {
            return Service::getObject($service->getData('http://www.fftt.com/mobile/pxml/xml_joueur.php', array('licence' => $licence)), 'joueur');
        });

        if (!isset($joueur['licence'])) {
            return null;
        }

        if ($raw) {
            return $joueur;
        }
        
        if (empty($joueur['natio'])) {
            $joueur['natio'] = 'F';
        }
        
        if ($joueur['clpro'] == 'NM') {
            $joueur['clast'] .= ' - NM' . $joueur['point'];
            $joueur['point'] = (float)$joueur['valcla'];
            
            $games = $this->getJoueurPartiesMysql($joueur['licence']);
            if (is_array($games)) {
                foreach($games as $game) {
                    $joueur['point'] += $game['pointres'];
                }
            }
            
            $joueur['apoint'] = $joueur['point'];
        }

        $joueur['photo'] = "http://www.fftt.com/espacelicencie/photolicencie/{$joueur['licence']}_.jpg";
        $joueur['progmois'] = round($joueur['point'] - $joueur['apoint'], 2); // Progression mensuelle
        $joueur['progann'] = round($joueur['point'] - $joueur['valinit'], 2); // Progression annuelle

        return $joueur;
    }
    public function cleanJoueur($licence)
    {
        if (!$this->cache) {
            return;
        }

        $this->cache->delete("licence_b_{$licence}");
        $this->cache->delete("joueur_{$licence}");
        $this->cache->delete("joueurparties_{$licence}");
        $this->cache->delete("joueurspid_{$licence}");
    }

    public function searchJoueurs($search)
    {
        if (strlen($search) >= 3 && is_numeric(substr($search, 0, 1))) {
            $result = $this->getJoueur($search);
            return $result ? array($result) : array();
        }

        $names = explode(' ', $search);
        $lastname = '';
        $firstname = '';

        foreach($names as $name) {
            if (strtoupper($name) == $name) {
                $lastname .= $name . ' ';
            } else {
                $firstname .= $name . ' ';
            }
        }

        $lastname = trim($lastname);
        $firstname = trim($firstname);

        return $this->getJoueursByName($lastname, $firstname);
    }

    public function getJoueurParties($player)
    {
        $games = $this->getJoueurPartiesMysql($player['licence']);
        $spidGames = $this->getJoueurPartiesSpid($player['licence']);

        $tempGames = array();
        if (is_array($spidGames)) {
            foreach ($spidGames as $sg) {
                if ($sg['forfait'] == '1' || strtolower($sg['nom']) == 'absent absent') {
                    continue;
                }
                $isTemp = true;
                foreach ($games as &$g) {
                    if ($g['date'] == $sg['date'] && (mb_strtolower($g['advnompre']) == mb_strtolower($sg['nom']) || strtolower(utf8_decode($g['advnompre'])) == substr(strtolower(utf8_decode($sg['nom'])), 0, 24)) && !isset($g['epreuve'])) {
                        $g['epreuve'] = $sg['epreuve'];
                        $g['classement'] = $sg['classement'];

                        $isTemp = false;
                        break;
                    }
                }
                
                $sg['coefchamp'] = $this->getCoef($sg['epreuve'], $player['cat']);
                $sg['pointres'] = $sg['coefchamp'] * $this->getPoints($player['point'], $this->parsePoints($sg['classement']), ($sg['victoire'] == 'V'));

                if ($isTemp) {
                    $tempGames[] = $sg;
                }
            }
        }

        if (!is_array($games)) {
            $games = array();
        }

        foreach($games as &$g) {
            if (!isset($g['epreuve'])) {
                $g['epreuve'] = $g['codechamp'];
            }
            
            if (!isset($g['classement'])) {
                $g['classement'] = empty($g['advclaof']) ? '500' : $g['advclaof'];
            }
        }

        return array($tempGames, $games);
    }

    public function getJoueurPartiesMysql($licence)
    {
        return $this->getCachedData("joueurparties_{$licence}", 3600*24*1, function($service) use ($licence) {
            return Service::getCollection($service->getData('http://www.fftt.com/mobile/pxml/xml_partie_mysql.php', array('licence' => $licence)), 'partie');
        });
    }

    public function getJoueurPartiesSpid($licence)
    {
        return $this->getCachedData("joueurspid_{$licence}", 3600*1, function($service) use ($licence) {
            return Service::getCollection($service->getData('http://www.fftt.com/mobile/pxml/xml_partie.php', array('numlic' => $licence)), 'resultat');
        });
    }

    public function getJoueurHistorique($licence)
    {
        return $this->getCachedData("joueur_historique_{$licence}", 3600*24*1, function($service) use ($licence) {
            return Service::getCollection($service->getData('http://www.fftt.com/mobile/pxml/xml_histo_classement.php', array('numlic' => $licence)), 'histo');
        });
    }

    public function getJoueursByName($nom, $prenom= '')
    {
        return $this->getCachedData("joueurs_{$nom}_{$prenom}", 3600*24*1, function($service) use ($nom, $prenom) {
            return Service::getCollection($service->getData('http://www.fftt.com/mobile/pxml/xml_liste_joueur.php', array('nom' => $nom, 'prenom' => $prenom)), 'joueur');
        });
    }

    public function getJoueursByClub($club)
    {
        $players = $this->getCachedData("clubjoueurs_{$club}", 3600*24*1, function($service) use ($club) {
            return Service::getCollection($service->getData('http://www.fftt.com/mobile/pxml/xml_liste_joueur.php', array('club' => $club)), 'joueur');
        });
        
        foreach($players as &$player) {
            if (substr($player['clast'], 0, 1) == 'N') {
                continue;
            }
            
            if ((int)$player['clast'] > 20) {
                $player['clast'] = 'N' . $player['clast'];
            }
        }
        
        return $players;
    }

    public function getEquipesByClub($club, $type = null)
    {
        if ($type && !in_array($type, array('M', 'F'))) {
            $type = 'M';
        }

        $teams = $this->getCachedData("clubequipes_{$club}_{$type}", 3600*24*1, function($service) use ($club, $type) {
            return Service::getCollection($service->getData('http://www.fftt.com/mobile/pxml/xml_equipe.php', array('numclu' => $club, 'type' => $type)), 'equipe');
        });

        foreach($teams as &$team) {
            $params = array();
            parse_str($team['liendivision'], $params);

            $team['idpoule'] = $params['cx_poule'];
            $team['iddiv'] = $params['D1'];
        }

        return $teams;
    }

    public function getPoules($division)
    {
        $poules = $this->getCachedData("poules_{$division}", 3600*24*7, function($service) use ($division) {
            return Service::getCollection($service->getData('http://www.fftt.com/mobile/pxml/xml_result_equ.php', array('action' => 'poule', 'D1' => $division)), 'poule');
        });

        foreach($poules as &$poule) {
            $params = array();
            parse_str($poule['lien'], $params);

            $poule['idpoule'] = $params['cx_poule'];
            $poule['iddiv'] = $params['D1'];
        }

        return $poules;
    }

    public function getPouleClassement($division, $poule = null)
    {
        return $this->getCachedData("pouleclassement_{$division}_{$poule}", 3600*1, function($service) use ($division, $poule) {
            return Service::getCollection($service->getData('http://www.fftt.com/mobile/pxml/xml_result_equ.php', array('auto' => 1, 'action' => 'classement', 'D1' => $division, 'cx_poule' => $poule)), 'classement');
        });
    }

    public function getPouleRencontres($division, $poule = null)
    {
        return $this->getCachedData("poulerencontres_{$division}_{$poule}", 3600*1, function($service) use ($division, $poule) {
            return Service::getCollection($service->getData('http://www.fftt.com/mobile/pxml/xml_result_equ.php', array('auto' => 1, 'D1' => $division, 'cx_poule' => $poule)), 'tour');
        });
    }

    public function getIndivGroupes($division)
    {
        $groupes = $this->getCachedData("groupes_{$division}", 3600*1, function($service) use ($division) {
            return Service::getCollection($service->getData('http://www.fftt.com/mobile/pxml/xml_result_indiv.php', array('action' => 'poule', 'res_division' => $division)), 'tour');
        });

        foreach($groupes as &$groupe) {
            $params = array();
            parse_str($groupe['lien'], $params);

            if (isset($params['cx_tableau'])) {
                $groupe['idgroupe'] = $params['cx_tableau'];
            } else {
                $groupe['idgroupe'] = null;
            }
            $groupe['iddiv'] = $params['res_division'];
        }

        return $groupes;
    }

    public function getGroupeClassement($division, $groupe = null)
    {
        return $this->getCachedData("groupeclassement_{$division}_{$groupe}", 3600*1, function($service) use ($division, $groupe) {
            return Service::getCollection($service->getData('http://www.fftt.com/mobile/pxml/xml_result_indiv.php', array('action' => 'classement', 'res_division' => $division, 'cx_tableau' => $groupe)), 'classement');
        });
    }

    public function getGroupeRencontres($division, $groupe = null)
    {
        return $this->getCachedData("grouperencontres_{$division}_{$groupe}", 3600*1, function($service) use ($division, $groupe) {
            return Service::getCollection($service->getData('http://www.fftt.com/mobile/pxml/xml_result_indiv.php', array('action' => 'partie', 'res_division' => $division, 'cx_tableau' => $groupe)), 'partie');
        });
    }

    public function getOrganismes($type)
    {
        // Zone / Ligue / Departement
        if (!in_array($type, array('Z', 'L', 'D'))) {
            $type = 'L';
        }

        return $this->getCachedData("organismes_{$type}", 3600*24*30, function($service) use ($type) {
            return Service::getCollection($service->getData('http://www.fftt.com/mobile/pxml/xml_organisme.php', array('type' => $type)), 'organisme');
        });
    }

    public function getEpreuves($organisme, $type)
    {
        // Equipe / Individuelle
        if (!in_array($type, array('E', 'I'))) {
            $type = 'E';
        }

        return $this->getCachedData("epreuves_{$organisme}_{$type}", 3600*24*30, function($service) use ($organisme, $type) {
            return Service::getCollection($service->getData('http://www.fftt.com/mobile/pxml/xml_epreuve.php', array('type' => $type, 'organisme' => $organisme)), 'epreuve');
        });
    }

    public function getDivisions($organisme, $epreuve, $type = 'E')
    {
        // Equipe / Individuelle
        if (!in_array($type, array('E', 'I'))) {
            $type = 'E';
        }

        return $this->getCachedData("divisions_{$organisme}_{$epreuve}_{$type}", 3600*24*7, function($service) use ($organisme, $epreuve, $type) {
            return Service::getCollection($service->getData('http://www.fftt.com/mobile/pxml/xml_division.php', array('organisme' => $organisme, 'epreuve' => $epreuve, 'type' => $type)), 'division');
        });
    }

    public function getRencontre($link)
    {
        $params = array();
        parse_str($link, $params);

        return $this->getCachedData("rencontre_".sha1($link), 3600*1, function($service) use ($params) {
            return Service::getObject($service->getData('http://www.fftt.com/mobile/pxml/xml_chp_renc.php', $params), null);
        });
    }

    public function getLicencesByName($nom, $prenom= '')
    {
        return $this->getCachedData("licences_{$nom}_{$prenom}", 3600*24*1, function($service) use ($nom, $prenom) {
            return Service::getCollection($service->getData('http://www.fftt.com/mobile/pxml/xml_liste_joueur_o.php', array('nom' => strtoupper($nom), 'prenom' => ucfirst($prenom))), 'joueur');
        });
    }

    public function getLicencesByClub($club)
    {
        $players = $this->getCachedData("licencesclub_{$club}", 3600*24*1, function($service) use ($club) {
            return Service::getCollection($service->getData('http://www.fftt.com/mobile/pxml/xml_licence_b.php', array('club' => $club)), 'licence');
        });

        foreach($players as &$player) {
            if (!isset($player['pointm'])) {
                $player['pointm'] = $player['point'];
            }

            if ($player['type'] != 'T') {
                $player['clast'] = 'P';
            } else {
                if (!empty($player['echelon']) && !empty($player['place'])) {
                    $player['clast'] = 'N' . $player['place'];
                } else {
                    $player['clast'] = floor((float)$player['point'] / 100);
                }
            }
        }

        return $players;
    }

    public function getLicence($licence, $raw = false)
    {
        $licence = $this->getCachedData("licence_b_{$licence}", 3600*24*1, function($service) use ($licence) {
            return Service::getObject($service->getData('http://www.fftt.com/mobile/pxml/xml_licence_b.php', array('licence' => $licence)), 'licence');
        });

        if (!$licence) {
            return null;
        }

        if ($raw) {
            return $licence;
        }
        
        if (empty($licence['validation'])) {
            $licence['validation'] = '-';
        }

        $licence['raw_type'] = $licence['type'];
        if (empty($licence['type'])) {
            $licence['type'] = '-';
        } else if ($licence['type'] == 'T') {
            $licence['type'] = 'Traditionnelle';
        } else if ($licence['type'] == 'P') {
            $licence['type'] = 'Promotionnelle';
        }

        $licence['raw_certif'] = $licence['certif'];
        if (empty($licence['certif'])) {
            $licence['certif'] = 'Aucun';
        } else if ($licence['certif'] == 'C') {
            $licence['certif'] = 'Présenté';
        } else if ($licence['certif'] == 'N') {
            $licence['certif'] = 'Ni Entrainement Ni compétition';
        } else if ($licence['certif'] == 'D') {
            $licence['certif'] = 'Double';
        } else if ($licence['certif'] == 'T') {
            $licence['certif'] = 'Triple';
        } else if ($licence['certif'] == 'Q') {
            $licence['certif'] = 'Quadruple';
        }

        if (!isset($licence['pointm'])) {
            $licence['pointm'] = $licence['point'];
        }
        if (!empty($licence['echelon']) && !empty($licence['place'])) {
            $licence['clast'] = 'N' . $licence['place'];
        } else {
            $licence['clast'] = floor((float)$licence['point'] / 100);
        }

        return $licence;
    }

    protected function getCachedData($key, $lifeTime, $callback)
    {
        if (!$this->cache) {
            return $callback($this);
        }

        if (false === ($data = $this->cache->fetch($key))) {
            $data = $callback($this);

            if ($data !== false) {
                $this->cache->save($key, $data, $lifeTime);
            }
        }

        return $data;
    }

    public function getData($url, $params = array(), $generateHash = true)
    {
        if ($generateHash) {
            $params['serie'] = $this->getSerial();
            $params['id'] = $this->getAppId();
            $params['tm'] = date('YmdHis') . substr(microtime(), 2, 3);
            $params['tmc'] =  hash_hmac('sha1', $params['tm'], hash('md5', $this->getAppKey(), false));
        }

        if (!empty($params)) {
            $url .= '?' . http_build_query($params);
        }

        $data = file_get_contents($url);

        if ($this->logger) {
            $this->logger->log($url, $data);
        }

        $xml = simplexml_load_string($data);

        if ($xml === false) {
            return false;
        }

        // Petite astuce pour transformer simplement le XML en tableau
        return json_decode(json_encode($xml), true);
    }

    public static function getCollection($data, $key = null)
    {
        if ($data === false) {
            return false;
        }

        if (empty($data)) {
            return array();
        }

        if ($key) {
            if (!array_key_exists($key, $data)) {
                return array();
            }
            $data = $data[$key];
        }

        return isset($data[0]) ? $data : array($data);
    }

    public static function getObject($data, $key = null)
    {
        if ($data === false) {
            return false;
        }

        if ($key && $data !== false) {
            return array_key_exists($key, $data) ? $data[$key] : null;
        } else {
            return empty($data) ? null : $data;
        }
    }

    public static function generateSerial()
    {
        $serial = '';
        for($i=0; $i<15; $i++) {
            $serial .= chr(mt_rand(65, 90)); //(A-Z)
        }

        return $serial;
    }

    public function parsePoints($points) {
        if (substr($points, 0, 1) == 'N' && strpos($points, ' - ') !== false) {
            return (int)substr($points, strpos($points, ' - ') + 3);
        }

        if (is_numeric($points)) {
            return (int)$points;
        }

        return 0;
    }

    public function getPoints($playerPoints, $advPoints, $victory)
    {
        $diff = $playerPoints - $advPoints;

        if ($diff > -25 && $diff < 25) {
            return $victory ? 6 : -5;
        }

        if ($diff >= 500) {
            return $victory ? 0 : -29;
        } else if ($diff >= 400) {
            return $victory ? 0.5 : -20;
        } else if ($diff >= 300) {
            return $victory ? 1 : -16;
        } else if ($diff >= 200) {
            return $victory ? 2 : -12.5;
        } else if ($diff >= 150) {
            return $victory ? 3 : -10;
        } else if ($diff >= 100) {
            return $victory ? 4 : -8;
        } else if ($diff >= 50) {
            return $victory ? 5 : -7;
        } else if ($diff >= 25) {
            return $victory ? 5.5 : -6;
        }

        if ($diff <= -500) {
            return $victory ? 40 : 0;
        } else if ($diff <= -400) {
            return $victory ? 28 : 0;
        } else if ($diff <= -300) {
            return $victory ? 22 : -0.5;
        } else if ($diff <= -200) {
            return $victory ? 17 : -1;
        } else if ($diff <= -150) {
            return $victory ? 13 : -2;
        } else if ($diff <= -100) {
            return $victory ? 10 : -3;
        } else if ($diff <= -50) {
            return $victory ? 8 : -4;
        } else if ($diff <= -25) {
            return $victory ? 7 : -4.5;
        }

        return 0;
    }

    public function getCoef($epreuve, $category)
    {
        switch($epreuve) {
            case 'Championnat de France Seniors':
                return 1.5;
            case 'Finales Individuelles':
            case 'Finales par classement':
                return 1.25;
            case 'Critérium fédéral':
                if (in_array($category, array('S', 'V1', 'V2', 'V3', 'V4', 'V5'))) {
                    return 1.25;
                } else {
                    return 1;
                }
            case 'Chpt France par équipes masculin':
            case 'Chpt France par équipes féminin':
            case 'Champt des Jeunes par Equipes':
            case 'Championnats de France Vétérans':
            case 'Coupe Nationale Corporative':
                return 1;
            case 'Tournoi National et Internat.':
            case 'Championnats de France Corpo.':
            case 'Championnat par équipes corpo':
            case 'Championnat de Paris IDF':
            case 'Coupe Nationale Vétérans':
            case 'Challenge Bernard Jeu':
                return 0.75;
            default:
                return 0.5;
        }
    }

}