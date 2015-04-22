<?php 

namespace SprykerFeature\Shared\Salesrule\Transfer;

/**
 *
 */
class Condition extends \SprykerFeature\Shared\Library\TransferObject\AbstractTransfer
{

    protected $idSalesruleCondition = null;

    protected $fkSalesrule = null;

    protected $conditionn = null;

    protected $condition = null;

    protected $configuration = null;

    protected $createdAt = null;

    protected $updatedAt = null;

    /**
     * @param int $idSalesruleCondition
     * @return $this
     */
    public function setIdSalesruleCondition($idSalesruleCondition)
    {
        $this->idSalesruleCondition = $idSalesruleCondition;
        $this->addModifiedProperty('idSalesruleCondition');
        return $this;
    }

    /**
     * @return int
     */
    public function getIdSalesruleCondition()
    {
        return $this->idSalesruleCondition;
    }

    /**
     * @param int $fkSalesrule
     * @return $this
     */
    public function setFkSalesrule($fkSalesrule)
    {
        $this->fkSalesrule = $fkSalesrule;
        $this->addModifiedProperty('fkSalesrule');
        return $this;
    }

    /**
     * @return int
     */
    public function getFkSalesrule()
    {
        return $this->fkSalesrule;
    }

    /**
     * @param string $conditionn
     * @return $this
     */
    public function setConditionn($conditionn)
    {
        $this->conditionn = $conditionn;
        $this->addModifiedProperty('conditionn');
        return $this;
    }

    /**
     * @return string
     */
    public function getConditionn()
    {
        return $this->conditionn;
    }

    /**
     * @param string $condition
     * @return $this
     */
    public function setCondition($condition)
    {
        $this->condition = $condition;
        $this->addModifiedProperty('condition');
        return $this;
    }

    /**
     * @return string
     */
    public function getCondition()
    {
        return $this->condition;
    }

    /**
     * @param string $configuration
     * @return $this
     */
    public function setConfiguration($configuration)
    {
        $this->configuration = $configuration;
        $this->addModifiedProperty('configuration');
        return $this;
    }

    /**
     * @return string
     */
    public function getConfiguration()
    {
        return $this->configuration;
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
