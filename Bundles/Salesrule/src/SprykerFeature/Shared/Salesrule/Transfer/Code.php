<?php 

namespace SprykerFeature\Shared\Salesrule\Transfer;

/**
 *
 */
class Code extends \SprykerFeature\Shared\Library\TransferObject\AbstractTransfer
{

    protected $idSalesruleCode = null;

    protected $fkSalesruleCodepool = null;

    protected $fkCustomer = null;

    protected $code = null;

    protected $isActive = null;

    protected $createdAt = null;

    protected $updatedAt = null;

    /**
     * @param int $idSalesruleCode
     * @return $this
     */
    public function setIdSalesruleCode($idSalesruleCode)
    {
        $this->idSalesruleCode = $idSalesruleCode;
        $this->addModifiedProperty('idSalesruleCode');
        return $this;
    }

    /**
     * @return int
     */
    public function getIdSalesruleCode()
    {
        return $this->idSalesruleCode;
    }

    /**
     * @param int $fkSalesruleCodepool
     * @return $this
     */
    public function setFkSalesruleCodepool($fkSalesruleCodepool)
    {
        $this->fkSalesruleCodepool = $fkSalesruleCodepool;
        $this->addModifiedProperty('fkSalesruleCodepool');
        return $this;
    }

    /**
     * @return int
     */
    public function getFkSalesruleCodepool()
    {
        return $this->fkSalesruleCodepool;
    }

    /**
     * @param int $fkCustomer
     * @return $this
     */
    public function setFkCustomer($fkCustomer)
    {
        $this->fkCustomer = $fkCustomer;
        $this->addModifiedProperty('fkCustomer');
        return $this;
    }

    /**
     * @return int
     */
    public function getFkCustomer()
    {
        return $this->fkCustomer;
    }

    /**
     * @param string $code
     * @return $this
     */
    public function setCode($code)
    {
        $this->code = $code;
        $this->addModifiedProperty('code');
        return $this;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param bool $isActive
     * @return $this
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;
        $this->addModifiedProperty('isActive');
        return $this;
    }

    /**
     * @return bool
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
