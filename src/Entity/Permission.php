<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Permission
 * @ORM\Table(name="permissions")
 * @ORM\Entity
 */
class Permission
{
    public function __construct()
    {
        $this->roles = new ArrayCollection();
    }

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", name="name")
     */
    protected $name;

    /**
     * @return Collection
     */
    public function getRoles(): Collection
    {
        return $this->roles;
    }

    /**
     * @param Collection $roles
     */
    public function setRoles(Collection $roles): void
    {
        $this->roles = $roles;
    }

    /**
     * @var Collection
     *
     * @ORM\ManyToMany(targetEntity="Role", mappedBy="permissions")
     */
    private $roles;

    /**
     * @return mixed
     */

    public function addGroup(Role $role)
    {
        $role->addPermission($this); // synchronously updating inverse side
        $this->roles[] = $role;
    }

    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }
    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }
}
