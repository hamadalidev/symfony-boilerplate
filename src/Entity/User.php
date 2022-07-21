<?php


namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Table(name="users")
 * @ORM\Entity
 */
class User implements UserInterface
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=25, unique=true)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=45)
     */
    private $email;

    /**
     * User constructor.
     * @param $username
     */
    public function __construct($username)
    {
        $this->username = $username;
        $this->roles = new ArrayCollection();
    }

    /**
     * @var Collection
     * @ORM\ManyToMany(targetEntity="Role", inversedBy="users", cascade={"persist"})
     * @ORM\JoinTable(name="users_roles")
     */
    protected $roles;

    /** @ORM\Column(type="datetime",nullable=true) */
    private $created;

    /** @ORM\Column(type="datetime",nullable=true) */
    private $updated;

    public function setCreated()
    {
        $this->created = new \DateTime("now");
    }

    public function getCreated()
    {
        return $this->created;
    }

    public function setUpdated()
    {
        $this->updated = new \DateTime("now");
    }

    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username): void
    {
        $this->username = $username;
    }

    /**
     * @return string|null
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @return string|null
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @param $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email): void
    {
        $this->email = $email;
    }


    public function eraseCredentials()
    {
    }

    public function setRoles(Collection $roles): void
    {
        $this->roles = null;
        foreach ($roles as $role) {
            $this->addRole($role);
        }
    }

    /**
     * @return mixed
     */

    public function addRole(Role $role)
    {
        $this->roles[] = $role;
    }

    public function getRoles()
    {
        return $this->roles->toArray();
    }

    public function getRole()
    {
        return $this->roles[0]->getName();
    }



}
