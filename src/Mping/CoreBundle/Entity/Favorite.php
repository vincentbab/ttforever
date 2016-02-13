<?php

namespace Mping\CoreBundle\Entity;

use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="favorite")
 * @ORM\Entity(repositoryClass="Mping\CoreBundle\Repository\FavoriteRepository")
 */
class Favorite
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var User $user
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="favorites")
     * @ORM\JoinColumn(name="iduser", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    protected $user;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $name;
    
    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $type;

    /**
     * @ORM\Column(type="string", length=32)
     */
    protected $route;

    /**
     * @ORM\Column(type="array")
     */
    protected $params;

    public function getId()
    {
        return $this->id;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function setUser($user)
    {
        $this->user = $user;
    }

    public function getRoute()
    {
        return $this->route;
    }

    public function setRoute($route)
    {
        $this->route = $route;
    }

    public function getParams()
    {
        return $this->params;
    }

    public function setParams($params)
    {
        $this->params = $params;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }
    
    public function getType()
    {
        return $this->type;
    }
    
    public function setType($type)
    {
        $this->type = $type;
    }
}