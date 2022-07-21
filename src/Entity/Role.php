<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;

/**
 * Class Role
 * @ORM\Entity
 * @ORM\Table(name="roles")
*/
class Role
{
    public function __construct()
    {
        $this->permissions = new ArrayCollection();
        $this->users = new ArrayCollection();
    }
    /**
     * @var int
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @var string
     * @ORM\Column(type="string", name="name")
     */
    private $name;

    /**
     * @var Collection
     * @ORM\ManyToMany(targetEntity="Permission", inversedBy="roles", cascade={"persist"})
     * @ORM\JoinTable(name="permissions_roles")
     */
    private $permissions;

    /**
     * @var Collection
     * @ORM\ManyToMany(targetEntity="User", inversedBy="users", cascade={"persist"})
     */
    private $roles;


    /**
     * @return mixed
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id)
    {
        $this->id = $id;
    }


    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return Collection
     */
    public function getPermissions(): Collection
    {
        return $this->permissions;
    }

    /**
     * @param Collection $permissions
     */
    public function setPermissions(Collection $permissions): void
    {
        $this->permissions = null;
        foreach ($permissions as $permission) {
            $this->addPermission($permission);
        }
    }

    /**
     * @return mixed
     */

    public function addPermission(Permission $permission)
    {
        $this->permissions[] = $permission;
    }


    /**
     * @return mixed
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * @param mixed $users
     */
    public function setUsers($users): void
    {
        $this->users = $users;
    }
}
