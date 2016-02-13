<?php

namespace Mping\CoreBundle\Entity;

use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\Validator\Constraints as SecurityAssert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Mping\CoreBundle\Validator\Constraints as MpingAssert;

/**
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="Mping\CoreBundle\Repository\UserRepository")
 *
 * @UniqueEntity(fields="email", message="Cette adresse email est déjà utilisé", groups={"registration"})
 *
 * @ORM\HasLifecycleCallbacks
 */
class User implements AdvancedUserInterface, \Serializable
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(name="password", type="string", length=128)
     */
    protected $password;
    
    /**
     * @SecurityAssert\UserPassword(message="Le mot de passe actuel ne correspond pas", groups={"changePassword"})
     */
    protected $oldPassword;

    /**
     * @Assert\NotBlank(message="Veuillez entrer un mot de passe", groups={"registration", "changePassword", "resetPassword"})
     * @Assert\Length(min="6", max="30",
     *     minMessage="Votre mot de passe doit faire au moins {{ limit }} caractère",
     *     maxMessage="Votre mot de passe ne doit pas dépasser {{ limit }} caractère",
     *     groups={"registration", "changePassword", "resetPassword"}
     * )
     */
    protected $plainPassword;

    /**
     * @ORM\Column(name="confirmation_token", type="string", length=255, nullable=true)
     */
    protected $confirmationToken;

    /**
     * @ORM\Column(name="pasword_requested", type="datetime", nullable=true)
     */
    protected $passwordRequestedAt;

    /**
     * @ORM\Column(type="string", length=32)
     */
    protected $salt;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     *
     * @Assert\NotBlank(message="Veuillez entrer une adresse email", groups={"registration"})
     * @Assert\Email(checkMX=true, message="Cette adresse email n'est pas valide", groups={"registration"})
     */
    protected $email;

    /**
     * @ORM\Column(name="enabled", type="boolean")
     */
    protected $enabled;

    /**
     * @ORM\Column(name="locked", type="boolean")
     */
    protected $locked;
    
    /**
     * @ORM\Column(name="admin", type="boolean")
     */
    protected $admin;

    /**
     * @ORM\Column(type="string", length=16, nullable=true)
     *
     * @MpingAssert\FfttLicence(groups={"registration", "settings"})
     */
    protected $licence;
    
    /**
     * @var string $createdAt
     *
     * @ORM\Column(name="created", type="datetime")
     */
    protected $createdAt;

    /**
     * @var DateTime $updatedAt
     *
     * @ORM\Column(name="updated", type="datetime")
     */
    protected $updatedAt;

    /**
     * @ORM\OneToMany(targetEntity="Favorite", mappedBy="user")
     */
    protected $favorites;

    public function __construct()
    {
        $this->enabled = true;
        $this->locked = false;
        $this->admin = false;

        $this->favorites = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * @ORM\PrePersist
     */
    public function prePersist()
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = $this->createdAt;
    }

    /**
     * @ORM\PreUpdate
     */
    public function preUpdate()
    {
        $this->updatedAt = new \DateTime();
    }

    public function getId()
    {
        return $this->id;
    }

    public function isAccountNonExpired()
    {
        return true;
    }

    public function isAccountNonLocked()
    {
        return !$this->locked;
    }

    public function isCredentialsNonExpired()
    {
        return true;
    }

    public function isEnabled()
    {
        return $this->enabled;
    }

    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;
    }

    public function setLocked($locked)
    {
        $this->locked = $locked;
    }
    
    public function setAdmin($admin)
    {
        $this->admin = $admin;
    }
    
    public function isAdmin()
    {
        return $this->admin;
    }

    /**
     * @inheritDoc
     */
    public function getUsername()
    {
        return $this->getEmail();
    }

    public function setUsername($username)
    {
        $this->setEmail($username);
    }

    /**
     * @inheritDoc
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * @inheritDoc
     */
    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    public function setPlainPassword($password)
    {
        $this->plainPassword = $password;
    }
    
    public function getOldPassword()
    {
        return $this->oldPassword;
    }

    public function setOldPassword($password)
    {
        $this->oldPassword = $password;
    }

    /**
     * @inheritDoc
     */
    public function getRoles()
    {
        if ($this->isAdmin()) {
            return array('ROLE_ADMIN');
        } else {
            return array('ROLE_USER');
        }
    }

    /**
     * @inheritDoc
     */
    public function eraseCredentials()
    {
        $this->plainPassword = null;
        $this->oldPassword = null;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getLicence()
    {
        return $this->licence;
    }

    public function setLicence($licence)
    {
        $this->licence = $licence;
    }

    /**
     * @see \Serializable::serialize()
     */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->password,
            $this->salt,
            $this->email,
        ));
    }

    /**
     * @see \Serializable::unserialize()
     */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->password,
            $this->salt,
            $this->email
        ) = unserialize($serialized);
    }

    public function getFavorites()
    {
        return $this->favorites->toArray();
    }

    public function setConfirmationToken($confirmationToken)
    {
        $this->confirmationToken = $confirmationToken;
    }
    public function getConfirmationToken()
    {
        return $this->confirmationToken;
    }

    public function setPasswordRequestedAt(\DateTime $date = null)
    {
        $this->passwordRequestedAt = $date;
    }
    public function getPasswordRequestedAt()
    {
        return $this->passwordRequestedAt;
    }
    public function isPasswordRequestNonExpired($ttl)
    {
        return $this->getPasswordRequestedAt() instanceof \DateTime && $this->getPasswordRequestedAt()->getTimestamp() + $ttl > time();
    }
}