<?php 

namespace SprykerFeature\Shared\Salesrule\Transfer;

/**
 *
 */
class CodePool extends \SprykerFeature\Shared\Library\TransferObject\AbstractTransfer
{

    protected $idSalesruleCodepool = null;

    protected $name = null;

    protected $prefix = null;

    protected $isReusable = null;

    protected $isOncePerCustomer = null;

    protected $isRefundable = null;

    protected $isBalanced = null;

    protected $isVoucher = null;

    protected $isActive = null;

    /**
     * @param int $idSalesruleCodepool
     * @return $this
     */
    public function setIdSalesruleCodepool($idSalesruleCodepool)
    {
        $this->idSalesruleCodepool = $idSalesruleCodepool;
        $this->addModifiedProperty('idSalesruleCodepool');
        return $this;
    }

    /**
     * @return int
     */
    public function getIdSalesruleCodepool()
    {
        return $this->idSalesruleCodepool;
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
     * @param string $prefix
     * @return $this
     */
    public function setPrefix($prefix)
    {
        $this->prefix = $prefix;
        $this->addModifiedProperty('prefix');
        return $this;
    }

    /**
     * @return string
     */
    public function getPrefix()
    {
        return $this->prefix;
    }

    /**
     * @param int $isReusable
     * @return $this
     */
    public function setIsReusable($isReusable)
    {
        $this->isReusable = $isReusable;
        $this->addModifiedProperty('isReusable');
        return $this;
    }

    /**
     * @return int
     */
    public function getIsReusable()
    {
        return $this->isReusable;
    }

    /**
     * @param int $isOncePerCustomer
     * @return $this
     */
    public function setIsOncePerCustomer($isOncePerCustomer)
    {
        $this->isOncePerCustomer = $isOncePerCustomer;
        $this->addModifiedProperty('isOncePerCustomer');
        return $this;
    }

    /**
     * @return int
     */
    public function getIsOncePerCustomer()
    {
        return $this->isOncePerCustomer;
    }

    /**
     * @param int $isRefundable
     * @return $this
     */
    public function setIsRefundable($isRefundable)
    {
        $this->isRefundable = $isRefundable;
        $this->addModifiedProperty('isRefundable');
        return $this;
    }

    /**
     * @return int
     */
    public function getIsRefundable()
    {
        return $this->isRefundable;
    }

    /**
     * @param int $isBalanced
     * @return $this
     */
    public function setIsBalanced($isBalanced)
    {
        $this->isBalanced = $isBalanced;
        $this->addModifiedProperty('isBalanced');
        return $this;
    }

    /**
     * @return int
     */
    public function getIsBalanced()
    {
        return $this->isBalanced;
    }

    /**
     * @param int $isVoucher
     * @return $this
     */
    public function setIsVoucher($isVoucher)
    {
        $this->isVoucher = $isVoucher;
        $this->addModifiedProperty('isVoucher');
        return $this;
    }

    /**
     * @return int
     */
    public function getIsVoucher()
    {
        return $this->isVoucher;
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


}
