<?php

namespace SprykerFeature\Zed\Salesrule\Business\Model\Condition;

use SprykerFeature\Shared\Sales\Transfer\Order;
use SprykerFeature\Zed\Salesrule\Business\Model\Logger;
use SprykerFeature\Zed\Salesrule\Communication\Form\Validator\CodeGroupInUse;

final class VoucherCodeInPool extends AbstractCondition
{

    const CONFIG_KEY_NUMBER = 'number';

    /**
     * @var string
     */
    public static $conditionName = 'Code is used';

    /**
     * @var string
     */
    public static $conditionFacadeGetter = 'ConditionVoucherCodeInPool';

    /**
     * @var string
     */
    private $lastMatchedCode = null;

    /**
     * @var array
     */
    protected $allowedConfigKeys = array(
        self::CONFIG_KEY_NUMBER
    );

    /**
     * @param Order $order
     * @return bool|mixed
     */
    public function match(Order $order)
    {
        assert(null !== $this->configuration);
        $this->lastMatchedCode = null;

        foreach ($order->getCouponCodes() as $code) {
            if (!$this->factory->createModelCodeUsage()->canUseCouponCode($code, $order->getCustomer()->getIdCustomer())) {
                continue;
            }

            $configuration = $this->configuration;

            if (!$this->factory->createModelCode()->isCodeInCodePool($code, $configuration[self::CONFIG_KEY_NUMBER])) {
                continue;
            }

            if (!$this->isTestMode()) {
                Logger::getInstance()->log(
                    static::$conditionName . ': ' . $code
                    . ' can be used by customer('
                    . $order->getCustomer()->getIdCustomer()
                    . ') and is in codepool ('
                    .  $configuration[self::CONFIG_KEY_NUMBER]
                    . ')'
                );
            }
            $this->lastMatchedCode = $code;

            return true;
        }
        return false;
    }

    /**
     * @return string|null
     */
    public function getLastMatchedCode()
    {
        return $this->lastMatchedCode;
    }

    /**
     * @return \Zend_Form
     */
    public function getForm()
    {
        $form = $this->getFormTemplate();
        $codePoolArray = $this->getCodePools();
        $codePool = new \Zend_Form_Element_Select(self::CONFIG_KEY_NUMBER);
        $codePool
            ->addMultiOptions($codePoolArray)
            ->setLabel('Code Group')
            ->addValidator(new CodeGroupInUse());

        $form->addElement($codePool);
        $saveButton = new \Zend_Form_Element_Submit('Save');
        $form->addElement($saveButton);

        return $form;
    }

    /**
     * @return array
     */
    protected function getCodePools()
    {
        $codePoolArray = array();

        $codePools = \SprykerFeature\Zed\Salesrule\Persistence\Propel\SpySalesruleCodepoolQuery::create()->find();
        foreach ($codePools as $codePool) {
            /* @var \SprykerFeature\Zed\Salesrule\Persistence\Propel\SpySalesruleCodepool $codePool */
            $codePoolArray[$codePool->getPrimaryKey()] = $codePool->getName();
        }
        return $codePoolArray;
    }
}
