<?php

namespace Mping\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends Controller
{
    public function indexAction()
    {
        return $this->render('MpingCoreBundle:Home:index.html.php');
    }

    public function playersAction()
    {
        return $this->render('MpingCoreBundle:Home:players.html.php');
    }

    public function playerSearchAction(Request $request)
    {
        $search = utf8_decode($request->query->get('search'));
        $firstname = utf8_decode($request->query->get('firstname'));
        $lastname = utf8_decode($request->query->get('lastname'));
        $licence = $request->query->get('licence');
        $club = $request->query->get('club');

        if ($search) {
            $players = $this->get('fftt')->searchJoueurs($search);
        } else if ($licence) {
            return $this->redirect($this->generateUrl('player', array('licence' => $licence)));
        } else if ($club) {
            $players = $this->get('fftt')->getJoueursByClub($club);
        } else {
            if (strlen($lastname) > 1) {
                $players = $this->get('fftt')->getJoueursByName($lastname, $firstname);
            } else {
                $players = array();
            }
        }

        if (count($players) == 1) {
            return $this->redirect($this->generateUrl('player', array('licence' => $players[0]['licence'])));
        }

        return $this->render('MpingCoreBundle:Home:player-search.html.php', array('players' => $players));
    }

    public function playerAction($licence)
    {
        $player = $this->get('fftt')->getJoueur($licence);
        $licence = $this->get('fftt')->getLicence($licence);

        if (!$player) {
            $player = array(
                'nom' => $licence['nom'],
                'prenom' => $licence['prenom'],
                'licence' => empty($licence['licence']) ? '0' : $licence['licence'],
                'club' => $licence['nomclub'],
                'nclub' => empty($licence['numclub']) ? '0' : $licence['numclub'],
                'clast' => $licence['clast'],
                'point' => $licence['point'],
                'valcla' => $licence['point'],
                'valinit' => $licence['point'],
                'categ' => $licence['cat'],
                'progmois' => 0,
                'progann' => 0,
            );
        }

        //var_dump($player); var_dump($licence); exit;
        
        list($tempGames, $games) = $this->get('fftt')->getJoueurParties($player);

        $player['pointvirt'] = (float)$player['point'];
        $player['nbvictoire'] = $player['nbdefaite'] = 0;
        $player['nbperf'] = $player['nbcontre'] = 0;
        $player['nbvnormal'] = $player['nbdnormal'] = 0;

        $player['adversaire'] = array(
            '5' => array('v' => 0, 'd' => 0, 't' => 0),
            '6' => array('v' => 0, 'd' => 0, 't' => 0),
            '7' => array('v' => 0, 'd' => 0, 't' => 0),
            '8' => array('v' => 0, 'd' => 0, 't' => 0),
            '9' => array('v' => 0, 'd' => 0, 't' => 0),
            '10' => array('v' => 0, 'd' => 0, 't' => 0),
            '11' => array('v' => 0, 'd' => 0, 't' => 0),
            '12' => array('v' => 0, 'd' => 0, 't' => 0),
            '13' => array('v' => 0, 'd' => 0, 't' => 0),
            '14' => array('v' => 0, 'd' => 0, 't' => 0),
            '15' => array('v' => 0, 'd' => 0, 't' => 0),
            '16' => array('v' => 0, 'd' => 0, 't' => 0),
            '17' => array('v' => 0, 'd' => 0, 't' => 0),
            '18' => array('v' => 0, 'd' => 0, 't' => 0),
            '19' => array('v' => 0, 'd' => 0, 't' => 0),
            '20' => array('v' => 0, 'd' => 0, 't' => 0),
            'N°' => array('v' => 0, 'd' => 0, 't' => 0),
        );

        foreach($tempGames as $game) {
            $player['pointvirt'] += $game['pointres'];
            $cla = substr($game['classement'], 0, 1) == 'N' ? 'N°' : floor($game['classement']/100);
            $player['adversaire'][$cla]['t']++;

            if ($game['victoire'] == 'V') {
                $player['nbvictoire']++;
                $player['adversaire'][$cla]['v']++;

                if ($game['pointres']/$game['coefchamp'] > 6) {
                    $player['nbperf']++;
                } else {
                    $player['nbvnormal']++;
                }
            } else {
                $player['nbdefaite']++;
                $player['adversaire'][$cla]['d']++;

                if ($game['pointres']/$game['coefchamp'] < -5) {
                    $player['nbcontre']++;
                } else {
                    $player['nbdnormal']++;
                }
            }
        }

        foreach($games as $game) {
            $cla = substr($game['classement'], 0, 1) == 'N' ? 'N°' : floor($game['classement']/100);
            $player['adversaire'][$cla]['t']++;

            if ($game['vd'] == 'V') {
                $player['nbvictoire']++;
                $player['adversaire'][$cla]['v']++;

                if ($game['pointres']/$game['coefchamp'] > 6) {
                    $player['nbperf']++;
                } else {
                    $player['nbvnormal']++;
                }
            } else {
                $player['nbdefaite']++;
                $player['adversaire'][$cla]['d']++;

                if ($game['pointres']/$game['coefchamp'] < -5) {
                    $player['nbcontre']++;
                } else {
                    $player['nbdnormal']++;
                }
            }
        }

        $player['nbmatch'] = $player['nbvictoire']+$player['nbdefaite'];

        $maxadv = '5';
        foreach($player['adversaire'] as $classement => $adv) {
            if ($adv['t'] > $player['adversaire'][$maxadv]['t']) {
                $maxadv = $classement;
            }
        }

        foreach($player['adversaire'] as $classement => &$adv) {
            if ($player['adversaire'][$maxadv]['t'] == 0) {
                $adv['pv'] = $adv['pd'] = $adv['pt'] = 0;
                continue;
            }
            $adv['pv'] = $adv['t'] > 0 ? round(100*$adv['v']/($adv['t']), 1) : 0;
            $adv['pd'] = round(100 - $adv['pv'], 1);

            $adv['pt'] = round(100*$adv['t']/($player['adversaire'][$maxadv]['t']), 1);
        }

        $player['progvirt'] = $player['pointvirt'] - $player['point'];
        $player['progannvirt'] = $player['progann'] + $player['progvirt'];

        if ($player['nbmatch'] == 0) {
            $player['pvictoire'] = $player['pdefaite']
                = $player['pperf'] = $player['pcontre']
                = $player['pvnormal'] = $player['pdnormal']
                = 0;
        } else {
            $player['pvictoire'] = round(100*$player['nbvictoire']/($player['nbmatch']), 1);
            $player['pdefaite'] = round(100 - $player['pvictoire'], 1);
    
            $player['pperf'] = round(100*$player['nbperf']/($player['nbmatch']), 1);
            $player['pcontre'] = round(100*$player['nbcontre']/($player['nbmatch']), 1);
            $player['pvnormal'] = round(100*$player['nbvnormal']/($player['nbmatch']), 1);
            $player['pdnormal'] = round(100 - $player['pvnormal'] - $player['pcontre'] - $player['pperf'], 1);
        }
        
        if ($user = $this->getUser()) {
            $favorite = $this->getDoctrine()->getManager()->getRepository('MpingCoreBundle:Favorite')->findOneByUser($user, 'player', $player['nom'] . ' ' . $player['prenom']);
        } else {
            $favorite = false;
        }

        return $this->render('MpingCoreBundle:Home:player.html.php', array('player' => $player, 'licence' => $licence, 'isFavorite' => !!$favorite));
    }

    public function playerRefreshAction($licence)
    {
        if ($this->getUser()->isAdmin()) {
            $this->get('fftt')->cleanJoueur($licence);
        }

        return $this->redirect($this->generateUrl('player', array('licence' => $licence)));
    }

    public function playerHistoryAction($licence)
    {
        $player = $this->get('fftt')->getLicence($licence);
        $history = $this->get('fftt')->getJoueurHistorique($licence);

        return $this->render('MpingCoreBundle:Home:player-history.html.php', array('player' => $player, 'history' => $history));
    }

    public function playerGamesAction($licence)
    {
        $player = $this->get('fftt')->getLicence($licence);
        
        list($tempGames, $games) = $this->get('fftt')->getJoueurParties($player);

        $order = $this->getRequest()->query->get('tri');
        if (!in_array($order, array('clt', 'pts', 'date'))) {
            $order = 'date';
        }
        if ($order == 'clt') {
            foreach($games as &$game) {
                if (substr($game['advclaof'], 0, 1) == 'N') {
                    $game['group'] = 'Numéroté';
                } else {
                    $game['group'] = $game['advclaof'];
                }
            }

            usort($games, function($p1, $p2) {
                if ($p2['group'] != $p1['group']) {
                    $p1clt = substr($p1['advclaof'], 0, 1) == 'N' ? 21 : (int)$p1['advclaof'];
                    $p2clt = substr($p2['advclaof'], 0, 1) == 'N' ? 21 : (int)$p2['advclaof'];

                    return $p2clt - $p1clt;
                } else {
                    if (substr($p1['advclaof'], 0, 1) == 'N' && substr($p2['advclaof'], 0, 1) == 'N') {
                        return (int)substr($p1['advclaof'], 1) - (int)substr($p2['advclaof'], 1);
                    } else {
                        return strcmp($p1['advnompre'], $p2['advnompre']);
                    }
                }
            });

            foreach($tempGames as &$game) {
                $game['group'] = floor($game['classement'] / 100);
            }

            usort($tempGames, function($p1, $p2) {
                return $p2['group'] - $p1['group'];
            });
        } else if ($order == 'pts') {
            foreach($games as &$game) {
                $game['group'] = (float)$game['pointres'] > 0 ? '+'.$game['pointres'] : $game['pointres'];
            }

            usort($games, function($p1, $p2) {
                return (float)$p2['pointres']*100 - (float)$p1['pointres']*100;
            });

            foreach($tempGames as &$game) {
                $game['group'] = (float)$game['pointres'] > 0 ? '+'.$game['pointres'] : $game['pointres'];
            }

            usort($tempGames, function($p1, $p2) {
                return (float)$p2['pointres']*100 - (float)$p1['pointres']*100;
            });
        } else {
            foreach($games as &$game) {
                $game['group'] = \DateTime::createFromFormat('d/m/y', $game['date'])->format('d/m/Y') . ' - ' . (empty($game['epreuve']) ? '' : $game['epreuve']);
            }

            usort($games, function($p1, $p2) {
                $t1 = \DateTime::createFromFormat('d/m/y', $p1['date']);
                $t2 = \DateTime::createFromFormat('d/m/y', $p2['date']);

                return $t2->getTimestamp() - $t1->getTimestamp();
            });

            foreach($tempGames as &$game) {
                $game['group'] = \DateTime::createFromFormat('d/m/y', $game['date'])->format('d/m/Y') . ' - ' . $game['epreuve'];
            }

            usort($tempGames, function($p1, $p2) {
                $t1 = \DateTime::createFromFormat('d/m/y', $p1['date']);
                $t2 = \DateTime::createFromFormat('d/m/y', $p2['date']);

                return $t2->getTimestamp() - $t1->getTimestamp();
            });
        }
        return $this->render('MpingCoreBundle:Home:player-games.html.php', array('player' => $player, 'games' => $games, 'tempGames' => $tempGames));
    }

    public function playerSpidAction($licence)
    {
        /*$player = $this->get('fftt')->getJoueur($licence);
        $games = $this->get('fftt')->getJoueurPartiesSpid($licence);

        $order = $this->getRequest()->query->get('tri');
        if ($order == 'clt') {

        } else {

        }

        return $this->render('MpingCoreBundle:Home:player-spid.html.php', array('player' => $player, 'games' => $games));*/
    }

    public function clubsAction()
    {
        $departements = $this->getDepartements();
        $favorites = array();
        $user = $this->getUser();

        if ($user && $user->getLicence()) {
            $player = $this->get('fftt')->getJoueur($user->getLicence());

            $playerDep = substr($player['licence'], 0, 2);
            $clubDep = substr($player['nclub'], 2, 2);

            if ($playerDep) {
                $favorites[] = $playerDep;
            }

            if ($clubDep && $playerDep != $clubDep) {
                $favorites[] = $clubDep;
            }
        }


        return $this->render('MpingCoreBundle:Home:clubs.html.php', array('departements' => $departements, 'favorites' => $favorites));
    }

    public function clubSearchAction(Request $request)
    {
        $search = strtoupper($request->query->get('search'));
        $departement = strtoupper($request->query->get('departement'));

        if ($departement) {
            $clubs = $this->get('fftt')->getClubsByDepartement($departement);
        } else {
            $clubs = $this->get('fftt')->searchClubs($search);
        }

        if (count($clubs) == 1) {
            return $this->redirect($this->generateUrl('club', array('numero' => $clubs[0]['numero'])));
        }

        return $this->render('MpingCoreBundle:Home:club-search.html.php', array('clubs' => $clubs));
    }

    public function clubAction($numero)
    {
        $club = $this->get('fftt')->getClub($numero);
        $players = $this->get('fftt')->getLicencesByClub($numero);
        $players = array_filter($players, function($j) {
            return $j['type'] === 'T' && isset($j['pointm']);
        });
        $mapLink = 'http://maps.google.fr/maps?' . http_build_query(array('q' => (!empty($club['adressesalle1']) ? $club['adressesalle1'] . ', ' : '') . $club['codepsalle'] . ' - '. $club['villesalle']));
        
        $club['classement'] = array(
            '5' => array('m' => 0, 'f' => 0, 't' => 0),
            '6' => array('m' => 0, 'f' => 0, 't' => 0),
            '7' => array('m' => 0, 'f' => 0, 't' => 0),
            '8' => array('m' => 0, 'f' => 0, 't' => 0),
            '9' => array('m' => 0, 'f' => 0, 't' => 0),
            '10' => array('m' => 0, 'f' => 0, 't' => 0),
            '11' => array('m' => 0, 'f' => 0, 't' => 0),
            '12' => array('m' => 0, 'f' => 0, 't' => 0),
            '13' => array('m' => 0, 'f' => 0, 't' => 0),
            '14' => array('m' => 0, 'f' => 0, 't' => 0),
            '15' => array('m' => 0, 'f' => 0, 't' => 0),
            '16' => array('m' => 0, 'f' => 0, 't' => 0),
            '17' => array('m' => 0, 'f' => 0, 't' => 0),
            '18' => array('m' => 0, 'f' => 0, 't' => 0),
            '19' => array('m' => 0, 'f' => 0, 't' => 0),
            '20' => array('m' => 0, 'f' => 0, 't' => 0),
            'N°' => array('m' => 0, 'f' => 0, 't' => 0),
        );
        
        $club['categorie'] = array(
            'Poussin' =>    array('m' => 0, 'f' => 0, 't' => 0),
            'Benjamin' =>   array('m' => 0, 'f' => 0, 't' => 0),
            'Minime' =>     array('m' => 0, 'f' => 0, 't' => 0),
            'Cadet' =>      array('m' => 0, 'f' => 0, 't' => 0),
            'Junior' =>     array('m' => 0, 'f' => 0, 't' => 0),
            'Sénior' =>     array('m' => 0, 'f' => 0, 't' => 0),
            'Vétéran 1' =>  array('m' => 0, 'f' => 0, 't' => 0),
            'Vétéran 2' =>  array('m' => 0, 'f' => 0, 't' => 0),
            'Vétéran 3' =>  array('m' => 0, 'f' => 0, 't' => 0),
            'Vétéran 4+' => array('m' => 0, 'f' => 0, 't' => 0),
        );
        
        $club['nbplayer'] = $club['nbmale'] = $club['nbfemale'] = 0;
        foreach($players as &$player) {
            if (!empty($player['echelon']) && !empty($player['place'])) {
                $cla = 'N°';
            } else {
                $cla = floor((float)$player['point']/100);
            }
            
            switch($player['cat']) {
                case 'P':
                    $cat = 'Poussin'; break;
                case 'B1': case 'B2':
                    $cat = 'Benjamin'; break;
                case 'M1': case 'M2':
                    $cat = 'Minime'; break;
                case 'C1': case 'C2':
                    $cat = 'Cadet'; break;
                case 'J1': case 'J2': case 'J3':
                    $cat = 'Junior'; break;
                case 'S':
                    $cat = 'Sénior'; break;
                case 'V1':
                    $cat = 'Vétéran 1'; break;
                case 'V2':
                    $cat = 'Vétéran 2'; break;
                case 'V3':
                    $cat = 'Vétéran 3'; break;
                case 'V4': case 'V5': case 'V6':
                    $cat = 'Vétéran 4+'; break;
            }
            
            $club['nbplayer']++;
            $club['classement'][$cla]['t']++;
            $club['categorie'][$cat]['t']++;
            
            
            if ($player['sexe'] == 'M') {
                $club['nbmale']++;
                $club['classement'][$cla]['m']++;
                $club['categorie'][$cat]['m']++;
            } else {
                $club['nbfemale']++;
                $club['classement'][$cla]['f']++;
                $club['categorie'][$cat]['f']++;
            }
        }
        
        $maxcla = '5';
        foreach($club['classement'] as $classement => $stats) {
            if ($stats['t'] > $club['classement'][$maxcla]['t']) {
                $maxcla = $classement;
            }
        }
        
        $maxcat = 'Poussin';
        foreach($club['categorie'] as $cat => $stats) {
            if ($stats['t'] > $club['categorie'][$maxcat]['t']) {
                $maxcat = $cat;
            }
        }

        foreach($club['classement'] as $classement => &$stats) {
            $stats['pm'] = $stats['t'] > 0 ? round(100*$stats['m']/($stats['t']), 1) : 0;
            $stats['pf'] = round(100 - $stats['pm'], 1);

            $stats['pt'] = round(100*$stats['t']/($club['classement'][$maxcla]['t']), 1);
        }
        
        foreach($club['categorie'] as $cat => &$stats) {
            $stats['pm'] = $stats['t'] > 0 ? round(100*$stats['m']/($stats['t']), 1) : 0;
            $stats['pf'] = round(100 - $stats['pm'], 1);

            $stats['pt'] = round(100*$stats['t']/($club['categorie'][$maxcat]['t']), 1);
        }
        
        $club['pmale'] = round(100 * $club['nbmale'] / $club['nbplayer'], 2);
        $club['pfemale'] = 100 - $club['pmale'];
        
        if ($user = $this->getUser()) {
            $favorite = $this->getDoctrine()->getManager()->getRepository('MpingCoreBundle:Favorite')->findOneByUser($user, 'club', $club['nom']);
        } else {
            $favorite = false;
        }

        return $this->render('MpingCoreBundle:Home:club.html.php', array('club' => $club, 'mapLink' => $mapLink, 'isFavorite' => !!$favorite));
    }

    public function clubRefreshAction($numero)
    {
        if ($this->getUser()->isAdmin()) {
            $this->get('fftt')->cleanClub($numero);
        }

        return $this->redirect($this->generateUrl('club', array('numero' => $licence)));
    }

    public function clubPlayersAction($numero, Request $request)
    {
        $club = $this->get('fftt')->getClub($numero);
        //$players = $this->get('fftt')->getJoueursByClub($numero);
        $players = $this->get('fftt')->getLicencesByClub($numero);

        $order = $request->query->get('tri');
        if (!in_array($order, array('alpha', 'clt', 'pts', 'cat'))) {
            $order = 'pts';
        }

        $promo = (boolean)$request->query->get('promo', false);

        $players = array_filter($players, function($j) use ($promo) {
            if ($promo) {
                return $j['type'] === 'T' || $j['type'] === 'P';
            } else {
                return $j['type'] === 'T';
            }
        });

        if ($request->query->get('format') == 'csv') {
            $data = '';
            foreach($players as $p) {
                $data .= "{$p['licence']};{$p['nom']};{$p['prenom']};{$p['sexe']};{$p['pointm']};\n";
            }

            $response = new Response($data);
            $response->headers->set('Content-Type', 'text/plain');

            return $response;
        }

        if ($order == 'alpha') {
            foreach($players as &$player) {
                $player['group'] = strtoupper(substr($player['nom'], 0, 1));
                $player['display'] = $player['clast'];
            }

            usort($players, function($p1, $p2) {
                return strcmp($p1['nom'], $p2['nom']);
            });
        } else if ($order == 'clt') {
            foreach($players as &$player) {
                if ($player['type'] === 'P') {
                    $player['group'] = 'Licences promotionnelles';
                } else {
                    if (substr($player['clast'], 0, 1) == 'N') {
                        $player['group'] = 'Numéroté';
                    } else {
                        $player['group'] = $player['clast'];
                    }
                }

                $player['display'] = $player['clast'];
                $player['tri'] = $player['type'] == 'P' ? 0 : (float)$player['point'];
            }

            usort($players, function($p1, $p2) {
                if ($p2['group'] != $p1['group']) {
                    return $p2['tri'] - $p1['tri'];
                } else {
                    if ($p2['tri'] > $p1['tri']) {
                        return 1;
                    } else if ($p2['tri'] < $p1['tri']) {
                        return -1;
                    } else {
                        return strcmp($p1['nom'], $p2['nom']);
                    }
                }
            });
        } else if ($order == 'cat') {
            foreach($players as &$player) {
                $player['cat'] = substr($player['cat'], 0, 2);
                $player['group'] = $player['cat'];
                $player['display'] = $player['clast'];
                $player['tri'] = $player['type'] == 'P' ? 0 : (float)$player['point'];
            }
            
            $categories = array_flip(array('P', 'B1', 'B2', 'M1', 'M2', 'C1', 'C2', 'J1', 'J2', 'J3', 'S', 'V1', 'V2', 'V3', 'V4', 'V5'));
            
            usort($players, function($p1, $p2) use($categories) {
                if ($categories[$p2['cat']] > $categories[$p1['cat']]) {
                    return -1;
                } else if ($categories[$p2['cat']] < $categories[$p1['cat']]) {
                    return 1;
                } else {
                    if ($p2['tri'] > $p1['tri']) {
                        return 1;
                    } else if ($p2['tri'] < $p1['tri']) {
                        return -1;
                    } else {
                        return strcmp($p1['nom'], $p2['nom']);
                    }

                }
            });
        } else {
            foreach($players as &$player) {
                if (!empty($player['echelon']) && (float)$player['pointm']<100) {
                    $player['pointm'] = $player['point'];
                }

                if ($player['type'] === 'P') {
                    $player['group'] = 'Licences promotionnelles';
                } else {
                    $player['group'] = floor((float)$player['pointm']/100);
                }

                $player['display'] = $player['type'] == 'T' ? $player['pointm'] : 'P';
                $player['tri'] = $player['type'] == 'P' ? 0 : (isset($player['pointm']) ? (float)$player['pointm'] : $player['point']);
            }

            usort($players, function($p1, $p2) {
                if ($p2['tri'] > $p1['tri']) {
                    return 1;
                } else if ($p2['tri'] < $p1['tri']) {
                    return -1;
                } else {
                    return strcmp($p1['nom'], $p2['nom']);
                }

            });
        }


        return $this->render('MpingCoreBundle:Home:club-players.html.php', array(
            'club' => $club,
            'players' => $players,
            'order' => $order,
            'promo' => $promo,
        ));
    }

    public function clubTeamsAction($numero)
    {
        $club = $this->get('fftt')->getClub($numero);

        $teamsM = $this->get('fftt')->getEquipesByClub($numero, 'M');
        $teamsF = $this->get('fftt')->getEquipesByClub($numero, 'F');
        $teamsA = $this->get('fftt')->getEquipesByClub($numero, null);

        return $this->render('MpingCoreBundle:Home:club-teams.html.php', array(
            'teamsM' => $teamsM,
            'teamsF' => $teamsF,
            'teamsA' => $teamsA,
            'club' => $club,
        ));
    }

    public function rulesAction()
    {
        $rules = $this->getRules();

        return $this->render('MpingCoreBundle:Home:rules.html.php', array('rules' => $rules));
    }

    public function ruleAction($rule)
    {
        $rules = $this->getRules();

        return $this->render('MpingCoreBundle:Home:rule.html.php', array('name' => $rule, 'url' => $rules[$rule]));
    }

    public function championshipsAction()
    {
        $organisme = '100001';

        $teamEvents = $this->get('fftt')->getEpreuves($organisme, 'E');
        $singleEvents = $this->get('fftt')->getEpreuves($organisme, 'I');

        return $this->render('MpingCoreBundle:Home:championships.html.php', array('organisme' => $organisme, 'teamEvents' => $teamEvents, 'singleEvents' => $singleEvents));
    }

    public function championshipsDepAction()
    {
        $organismes = $this->get('fftt')->getOrganismes('D');
        $fav = $favorites = array();
        $user = $this->getUser();

        if ($user && $user->getLicence()) {
            $player = $this->get('fftt')->getJoueur($user->getLicence());

            $playerDep = substr($player['licence'], 0, 2);
            $clubDep = substr($player['nclub'], 2, 2);

            if ($playerDep) {
                $fav[] = 'D'.$playerDep;
            }

            if ($clubDep && $playerDep != $clubDep) {
                $fav[] = 'D'.$clubDep;
            }
        }

        foreach($fav as $favorite) {
            foreach($organismes as $organisme) {
                if ($organisme['code'] == $favorite) {
                    $favorites[] = $organisme;
                    break;
                }
            }
        }

        return $this->render('MpingCoreBundle:Home:championships-dep.html.php', array('organismes' => $organismes, 'favorites' => $favorites));
    }

    public function championshipsZoneAction()
    {
        $organismes = $this->get('fftt')->getOrganismes('Z');

        return $this->render('MpingCoreBundle:Home:championships-zone.html.php', array('organismes' => $organismes));
    }

    public function championshipsRegAction()
    {
        $organismes = $this->get('fftt')->getOrganismes('L');
        $fav = $favorites = array();
        $user = $this->getUser();

        if ($user && $user->getLicence()) {
            $player = $this->get('fftt')->getJoueur($user->getLicence());

            $clubReg = substr($player['nclub'], 0, 2);

            if ($clubReg) {
                $fav[] = 'L'.$clubReg;
            }
        }

        foreach($fav as $favorite) {
            foreach($organismes as $organisme) {
                if ($organisme['code'] == $favorite) {
                    $favorites[] = $organisme;
                    break;
                }
            }
        }

        return $this->render('MpingCoreBundle:Home:championships-reg.html.php', array('organismes' => $organismes, 'favorites' => $favorites));
    }

    public function championshipEventsAction($organisme)
    {
        $teamEvents = $this->get('fftt')->getEpreuves($organisme, 'E');
        $singleEvents = $this->get('fftt')->getEpreuves($organisme, 'I');

        return $this->render('MpingCoreBundle:Home:championship-events.html.php', array('organisme' => $organisme, 'teamEvents' => $teamEvents, 'singleEvents' => $singleEvents));
    }

    public function championshipTeamDivisionsAction($organisme, $epreuve)
    {
        $divisions = $this->get('fftt')->getDivisions($organisme, $epreuve);

        return $this->render('MpingCoreBundle:Home:championship-team-divisions.html.php', array('divisions' => $divisions));
    }

    public function championshipSingleDivisionsAction($organisme, $epreuve)
    {
        $divisions = $this->get('fftt')->getDivisions($organisme, $epreuve, 'I');

        return $this->render('MpingCoreBundle:Home:championship-single-divisions.html.php', array('divisions' => $divisions));
    }

    public function championshipTeamGroupAction($division, $poule = null)
    {
        $groups = $this->get('fftt')->getPoules($division);
        $ranking = $this->get('fftt')->getPouleClassement($division, $poule);
        $matchs = $this->get('fftt')->getPouleRencontres($division, $poule);

        $idxpoule = 0;
        if ($poule) {
            $i = 0; foreach($groups as $g) {
                if ($g['idpoule'] == $poule) {
                    $idxpoule = $i;
                }
            $i++; }
        }

        $group = array(
            'idpoule' => $groups[$idxpoule]['idpoule'],
            'iddiv' => $groups[$idxpoule]['iddiv'],
            'libelle' => $groups[$idxpoule]['libelle'],
        );

        return $this->render('MpingCoreBundle:Home:championship-team-group.html.php', array('groups' => $groups, 'ranking' => $ranking, 'matchs' => $matchs, 'group' => $group));
    }

    public function championshipSingleGroupAction($division, $groupe = null)
    {
        $groups = $this->get('fftt')->getIndivGroupes($division);
        $ranking = $this->get('fftt')->getGroupeClassement($division, $groupe);
        $matchs = $this->get('fftt')->getGroupeRencontres($division, $groupe);

        $idxgroupe = 0;
        if ($groupe) {
            $i = 0; foreach($groups as $g) {
                if ($g['idgroupe'] == $groupe) {
                    $idxgroupe = $i;
                }
            $i++; }
        }

        $groupe = array(
            'idgroupe' => $groups[$idxgroupe]['idgroupe'],
            'iddiv' => $groups[$idxgroupe]['iddiv'],
            'libelle' => $groups[$idxgroupe]['libelle'],
        );

        return $this->render('MpingCoreBundle:Home:championship-single-group.html.php', array('groups' => $groups, 'ranking' => $ranking, 'matchs' => $matchs, 'groupe' => $groupe));
    }

    public function championshipTeamEncounterAction($link)
    {
        $link = base64_decode($link);
        $encounter = $this->get('fftt')->getRencontre($link);

        return $this->render('MpingCoreBundle:Home:championship-team-encounter.html.php', array('encounter' => $encounter));
    }

    public function licencesAction()
    {
        return $this->render('MpingCoreBundle:Home:licences.html.php');
    }

    public function licenceSearchAction(Request $request)
    {
        $firstname = $request->query->get('firstname');
        $lastname = $request->query->get('lastname');
        $licence = $request->query->get('licence');
        $club = $request->query->get('club');

        if ($licence) {
            return $this->redirect($this->generateUrl('licence', array('licence' => $licence)));
        } else if ($club) {
            $licences = $this->get('fftt')->getLicencesByClub($club);
        } else {
            $licences = $this->get('fftt')->getLicencesByName($lastname, $firstname);
        }

        return $this->render('MpingCoreBundle:Home:licence-search.html.php', array('licences' => $licences));
    }

    public function licenceAction($licence)
    {
        $licence = $this->get('fftt')->getLicence($licence);

        return $this->render('MpingCoreBundle:Home:licence.html.php', array('licence' => $licence));
    }


    private function getDepartements()
    {
        return array(
            '01' => 'AIN',
            '02' => 'AISNE',
            '03' => 'ALLIER',
            '04' => 'ALPES HTE PROVENCE',
            '05' => 'HAUTES ALPES',
            '06' => 'ALPES MARITIMES',
            '08' => 'ARDENNES',
            '09' => 'ARIEGE',
            '10' => 'AUBE',
            '11' => 'AUDE',
            '12' => 'AVEYRON',
            '13' => 'BOUCHES DU RHONE',
            '14' => 'CALVADOS',
            '15' => 'CANTAL',
            '16' => 'CHARENTE',
            '17' => 'CHARENTE MARITIME',
            '18' => 'CHER',
            '19' => 'CORREZE',
            '21' => 'COTE D\'OR',
            '22' => 'CÔTES D ARMOR',
            '23' => 'CREUSE',
            '24' => 'DORDOGNE',
            '25' => 'DOUBS',
            '26' => 'DROME/ARDECHE',
            '27' => 'EURE',
            '28' => 'EURE ET LOIR',
            '29' => 'FINISTERE',
            '30' => 'GARD',
            '31' => 'HAUTE GARONNE',
            '32' => 'GERS',
            '33' => 'GIRONDE',
            '34' => 'HERAULT',
            '35' => 'ILLE ET VILAINE',
            '36' => 'INDRE',
            '37' => 'INDRE ET LOIRE',
            '38' => 'ISERE',
            '39' => 'JURA',
            '40' => 'LANDES',
            '41' => 'LOIR ET CHER',
            '42' => 'LOIRE',
            '43' => 'HAUTE LOIRE',
            '44' => 'LOIRE ATLANTIQUE',
            '45' => 'LOIRET',
            '46' => 'LOT',
            '47' => 'LOT ET GARONNE',
            '48' => 'LOZERE',
            '49' => 'MAINE ET LOIRE',
            '50' => 'MANCHE',
            '51' => 'MARNE',
            '52' => 'HAUTE-MARNE',
            '53' => 'MAYENNE',
            '54' => 'MEURTHE ET MOSELLE',
            '55' => 'MEUSE',
            '56' => 'MORBIHAN',
            '57' => 'MOSELLE',
            '58' => 'NIEVRE',
            '59' => 'NORD',
            '60' => 'OISE',
            '61' => 'ORNE',
            '62' => 'PAS DE CALAIS',
            '63' => 'PUY DE DOME',
            '64' => 'PYRENEES ATLANTIQUES',
            '65' => 'HAUTES PYRENEES',
            '66' => 'PYRENEES ORIENTALES',
            '67' => 'BAS RHIN',
            '68' => 'HAUT RHIN',
            '69' => 'RHONE',
            '70' => 'HAUTE SAONE',
            '71' => 'SAONE ET LOIRE',
            '72' => 'SARTHE',
            '73' => 'SAVOIE',
            '74' => 'HAUTE SAVOIE',
            '75' => 'PARIS',
            '76' => 'SEINE MARITIME',
            '77' => 'SEINE ET MARNE',
            '78' => 'YVELINES',
            '79' => 'DEUX SEVRES',
            '80' => 'SOMME',
            '81' => 'TARN',
            '82' => 'TARN ET GARONNE',
            '83' => 'VAR',
            '84' => 'VAUCLUSE',
            '85' => 'VENDEE',
            '86' => 'VIENNE',
            '87' => 'HAUTE VIENNE',
            '88' => 'VOSGES',
            '89' => 'YONNE',
            '90' => 'BELFORT',
            '91' => 'ESSONNE',
            '92' => 'HAUTS DE SEINE',
            '93' => 'SEINE-SAINT-DENIS',
            '94' => 'VAL DE MARNE',
            '95' => 'VAL D OISE',
            '98' => 'HAUTE CORSE',
            '99' => 'CORSE DU SUD',
            '9A' => 'GUADELOUPE',
            '9B' => 'COMITE MARTINIQUE',
            '9C' => 'GUYANE',
            '9D' => 'REUNION',
            '9E' => 'COMITE PROVINCIAL NORD',
            '9F' => 'COMITE PROVINCIAL SUD',
            '9G' => 'MAYOTTE',
            '9H' => 'TAHITI',
            '9W' => 'WALLIS ET FUTUNA',
        );
    }

    private function getRules()
    {
        return array(
            "Comptage des points" => "http://www.fftt.com/sportif/pclassement/html/grille.htm",
            "Règlements sportifs" => "http://www.fftt.com/reglements/reglement_sportif.htm",
            "Règles du jeu" => "http://www.fftt.com/reglements/regles_jeu.htm",
            "Règlement disciplinaire" => "http://www.fftt.com/reglements/reglement_disciplinaire.htm",
            "Règlement disciplinaire (cartons)" => "http://www.fftt.com/reglements/reglement_carton.htm",
            "Règlement médical" => "http://www.fftt.com/reglements/reglement_medical.htm",
            "Lutte contre le dopage" => "http://www.fftt.com/reglements/reglement_dopage.htm",
            "Statuts de la FFTT" => "http://www.fftt.com/reglements/statuts.htm",
            "Règlement Intérieur" => "http://www.fftt.com/reglements/reglement_interieur.htm",
            "Règlement administratif" => "http://www.fftt.com/reglements/reglement_administratif.htm",
            "Règlement financier" => "http://www.fftt.com/reglements/reglement_financier.htm",
        );
    }


}
