<?php

namespace SprykerFeature\Shared\Acl\Transfer;

class Group extends \SprykerFeature\Shared\Library\TransferObject\AbstractTransfer
{
    protected $idAclGroup = null;
    protected $name = null;

    /**
     * @return int
     */
    public function getIdAclGroup()
    {
        return $this->idAclGroup;
    }

    /**
     * @param int $idAclGroup
     *
     * @return Group
     */
    public function setIdAclGroup($idAclGroup)
    {
        $this->idAclGroup = $idAclGroup;
        $this->addModifiedProperty('idAclGroup');

        return $this;
    }

    /**
     * @param string $name
     *
     * @return Group
     */
    public function setName($name)
    {
        $this->name = $name;
        $this->addModifiedProperty('name');

        return $this;
    }
    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}
