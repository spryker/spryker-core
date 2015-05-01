<?php

namespace SprykerFeature\Shared\Acl\Transfer;

/**
 *
 */
class Role extends \SprykerFeature\Shared\Library\TransferObject\AbstractTransfer
{

    protected $idAclRole;
    protected $name = null;
    protected $idGroup = null;
    protected $rules = null;

    /**
     * @return int
     */
    public function getIdAclRole()
    {
        return $this->idAclRole;
    }

    /**
     * @param int $idAclRole
     *
     * @return Role
     */
    public function setIdAclRole($idAclRole)
    {
        $this->idAclRole = $idAclRole;
        $this->addModifiedProperty('idAclRole');
        return $this;
    }

    /**
     * @return null
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return Role
     */
    public function setName($name)
    {
        $this->name = $name;
        $this->addModifiedProperty('name');
        return $this;
    }

    /**
     * @param int $idGroup
     *
     * @return Role
     */
    public function setIdGroup($idGroup)
    {
        $this->idGroup = $idGroup;
        $this->addModifiedProperty('name');
        return $this;
    }

    /**
     * @return null
     */
    public function getIdGroup()
    {
        return $this->idGroup;
    }

    /**
     * @return null
     */
    public function getRules()
    {
        return $this->rules;
    }
}
