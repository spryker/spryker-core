<?php 

namespace SprykerFeature\Shared\Salesrule\Transfer;

/**
 *
 */
class Item extends \SprykerFeature\Shared\Library\TransferObject\AbstractTransfer
{

    protected $idSalesrule = null;

    protected $name = null;

    protected $description = null;

    protected $displayName = null;

    protected $scope = null;

    protected $action = null;

    protected $amount = null;

    protected $isActive = null;

    protected $createdAt = null;

    protected $updatedAt = null;

    /**
     * @param int $idSalesrule
     * @return $this
     */
    public function setIdSalesrule($idSalesrule)
    {
        $this->idSalesrule = $idSalesrule;
        $this->addModifiedProperty('idSalesrule');
        return $this;
    }

    /**
     * @return int
     */
    public function getIdSalesrule()
    {
        return $this->idSalesrule;
    }

    /**
     * @param string $name
     * @return $this
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

    /**
     * @param string $description
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = $description;
        $this->addModifiedProperty('description');
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $displayName
     * @return $this
     */
    public function setDisplayName($displayName)
    {
        $this->displayName = $displayName;
        $this->addModifiedProperty('displayName');
        return $this;
    }

    /**
     * @return string
     */
    public function getDisplayName()
    {
        return $this->displayName;
    }

    /**
     * @param int $scope
     * @return $this
     */
    public function setScope($scope)
    {
        $this->scope = $scope;
        $this->addModifiedProperty('scope');
        return $this;
    }

    /**
     * @return int
     */
    public function getScope()
    {
        return $this->scope;
    }

    /**
     * @param string $action
     * @return $this
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
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @param int $amount
     * @return $this
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
        $this->addModifiedProperty('amount');
        return $this;
    }

    /**
     * @return int
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param int $isActive
     * @return $this
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;
        $this->addModifiedProperty('isActive');
        return $this;
    }

    /**
     * @return int
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * @param string $createdAt
     * @return $this
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
        $this->addModifiedProperty('createdAt');
        return $this;
    }

    /**
     * @return string
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param string $updatedAt
     * @return $this
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
        $this->addModifiedProperty('updatedAt');
        return $this;
    }

    /**
     * @return string
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }


}
