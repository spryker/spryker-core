<?php

namespace SprykerFeature\Shared\Calculation\Transfer;

use Generated\Shared\Transfer\Discount\DependencyDiscountItemInterfaceTransfer;
use SprykerFeature\Shared\Library\TransferObject\AbstractTransfer;

class Discount extends AbstractTransfer implements DiscountItemInterface
{

    protected $salesruleId = null;

    protected $displayName = null;

    protected $amount = 0;

    protected $type = null;

    protected $action = null;

    protected $scope = null;

    protected $usedCodes = array(

    );

    /**
     * @param int $salesruleId
     * @return $this
     */
    public function setSalesruleId($salesruleId)
    {
        $this->salesruleId = $salesruleId;
        $this->addModifiedProperty('salesruleId');
        return $this;
    }

    /**
     * @return int
     */
    public function getSalesruleId()
    {
        return $this->salesruleId;
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
     * @param string $type
     * @return $this
     */
    public function setType($type)
    {
        $this->type = $type;
        $this->addModifiedProperty('type');
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
     * @param string $scope
     * @return $this
     */
    public function setScope($scope)
    {
        $this->scope = $scope;
        $this->addModifiedProperty('scope');
        return $this;
    }

    /**
     * @return string
     */
    public function getScope()
    {
        return $this->scope;
    }

    /**
     * @param array $usedCodes
     * @return $this
     */
    public function setUsedCodes(array $usedCodes)
    {
        $this->usedCodes = $usedCodes;
        $this->addModifiedProperty('usedCodes');
        return $this;
    }

    /**
     * @return array
     */
    public function getUsedCodes()
    {
        return $this->usedCodes;
    }

    /**
     * @param mixed $usedCode
     * @return array
     */
    public function addUsedCode($usedCode)
    {
        $this->usedCodes[] = $usedCode;
        return $this;
    }
}
