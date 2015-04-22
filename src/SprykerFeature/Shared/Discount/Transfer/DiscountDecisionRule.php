<?php 

namespace SprykerFeature\Shared\Discount\Transfer;

use SprykerFeature\Shared\Library\TransferObject\AbstractTransfer;
use SprykerFeature\Zed\Discount\Dependency\Plugin\DiscountDecisionRulePluginInterface;

class DiscountDecisionRule extends AbstractTransfer
{
    /**
     * @var int
     */
    protected $idDiscountDecisionRule = null;

    /**
     * @var int
     */
    protected $fkDiscount = null;

    /**
     * @var string
     */
    protected $name = null;

    /**
     * @var DiscountDecisionRulePluginInterface
     */
    protected $decisionRulePlugin = null;

    protected $value = null;

    /**
     * @param $decisionRulePlugin
     * @return DiscountDecisionRule
     */
    public function setDecisionRulePlugin($decisionRulePlugin)
    {
        $this->decisionRulePlugin = $decisionRulePlugin;
        $this->addModifiedProperty('decisionRulePlugin');

        return $this;
    }

    /**
     * @return null
     */
    public function getDecisionRulePlugin()
    {
        return $this->decisionRulePlugin;
    }

    /**
     * @param $fkDiscount
     * @return DiscountDecisionRule
     */
    public function setFkDiscount($fkDiscount)
    {
        $this->fkDiscount = $fkDiscount;
        $this->addModifiedProperty('fkDiscount');

        return $this;
    }

    /**
     * @return null
     */
    public function getFkDiscount()
    {
        return $this->fkDiscount;
    }

    /**
     * @param $idDiscountDecisionRule
     * @return DiscountDecisionRule
     */
    public function setIdDiscountDecisionRule($idDiscountDecisionRule)
    {
        $this->idDiscountDecisionRule = $idDiscountDecisionRule;
        $this->addModifiedProperty('idDiscountDecisionRule');

        return $this;
    }

    /**
     * @return null
     */
    public function getIdDiscountDecisionRule()
    {
        return $this->idDiscountDecisionRule;
    }

    /**
     * @param $name
     * @return DiscountDecisionRule
     */
    public function setName($name)
    {
        $this->name = $name;
        $this->addModifiedProperty('name');

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
     * @param $value
     * @return DiscountDecisionRule
     */
    public function setValue($value)
    {
        $this->value = $value;
        $this->addModifiedProperty('value');

        return $this;
    }

    /**
     * @return null
     */
    public function getValue()
    {
        return $this->value;
    }
}
