<?php

namespace SprykerFeature\Shared\Acl\Transfer;

/**
 *
 */
class Rule extends \SprykerFeature\Shared\Library\TransferObject\AbstractTransfer
{

    protected $idAclRule = null;
    protected $bundle = null;
    protected $controller = null;
    protected $action = null;
    protected $type = null;
    protected $fk_acl_role = null;

    /**
     * @return int
     */
    public function getIdAclRule()
    {
        return $this->idAclRule;
    }

    /**
     * @param int $idAclRule
     *
     * @return Rule
     */
    public function setIdAclRule($idAclRule)
    {
        $this->idAclRule = $idAclRule;
        $this->addModifiedProperty('idAclRule');

        return $this;
    }

    /**
     * @return null
     */
    public function getBundle()
    {
        return $this->bundle;
    }

    /**
     * @param string $bundle
     *
     * @return Rule
     */
    public function setBundle($bundle)
    {
        $this->bundle = $bundle;
        $this->addModifiedProperty('bundle');

        return $this;
    }

    /**
     * @return null
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * @param string $controller
     * @return Rule
     */
    public function setController($controller)
    {
        $this->controller = $controller;
        $this->addModifiedProperty('controller');

        return $this;
    }

    /**
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @param string $action
     *
     * @return Rule
     */
    public function setAction($action)
    {
        $this->action = $action;
        $this->addModifiedProperty('action');

        return $this;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     *
     * @return Rule
     */
    public function setType($type)
    {
        $this->type = $type;
        $this->addModifiedProperty('type');

        return $this;
    }

    /**
     * @return int
     */
    public function getFkAclRole()
    {
        return $this->fk_acl_role;
    }

    /**
     * @param int $roleFk
     *
     * @return Rule
     */
    public function setFkAclRole($roleFk)
    {
        $this->fk_acl_role = $roleFk;
        $this->addModifiedProperty('fk_acl_role');

        return $this;
    }
}
