<?php

namespace SprykerFeature\Zed\Salesrule\Business\Model\Condition;

use SprykerFeature\Shared\Sales\Transfer\Order;
use SprykerFeature\Zed\Salesrule\Business\Model\Logger;

final class MinimumOrderSubtotal extends AbstractCondition
{
    const CONFIG_KEY_NUMBER = 'number';

    /**
     * @var string
     */
    public static $conditionName = 'Minimum order subtotal amount';

    /**
     * @var string
     */
    public static $conditionFacadeGetter = 'ConditionMinimumOrderSubtotal';

    /**
     * @var array
     */
    protected $allowedConfigKeys = array(
        self::CONFIG_KEY_NUMBER
    );

    /**
     * @param \SprykerFeature\Shared\Sales\Transfer\Order $order
     * @return bool
     */
    public function match(Order $order)
    {
        assert(null !== $this->configuration);
        $configuration = $this->configuration;

        if ($order->getTotals()->getSubtotalWithoutItemExpenses() >= $configuration[self::CONFIG_KEY_NUMBER] * 100) {
            Logger::getInstance()->log(
                static::$conditionName . ': '. $order->getTotals()->getSubtotalWithoutItemExpenses()
                . ' >= ' . $configuration[self::CONFIG_KEY_NUMBER] * 100
            );
            return true;
        }
        return false;
    }

    /**
     * @return \Zend_Form
     */
    public function getForm()
    {
        $form = $this->getFormTemplate();
        $amount = new \Zend_Form_Element_Text(self::CONFIG_KEY_NUMBER);
        $amount->setLabel('Minimum Order Subtotal')
            ->setRequired(true)
            ->addValidator(new \Zend_Validate_Int())
            ->addValidator(new \Zend_Validate_GreaterThan(-1)); // TODO This causes an awkward error message, a dedicated, new Validator could solve this
        $form->addElement($amount);
        $saveButton = new \Zend_Form_Element_Submit(__('Save'));
        $saveButton->setAttrib('id', 'condition-save-button');
        $form->addElement($saveButton);

        return $form;
    }
}
