<?php

namespace SprykerFeature\Zed\Salesrule\Communication\Form\Validator;

class CodeGroupInUse extends \Zend_Validate_Abstract
{
    const CODE_GROUP_ALREADY_EXISTS = 'code_group_already_exists';

    /**
     * @var array
     */
    protected $_messageTemplates = array(
        self::CODE_GROUP_ALREADY_EXISTS => "The code group is already applied as a condition",
    );

    /**
     * @param string $value
     * @param array $context
     * @return bool
     */
    public function isValid($value, $context = null)
    {
        if ($context['condition_name'] !== 'ConditionVoucherCodeInPool') {
            return true;
        }
        $configuration = [
            'number' => $context['number']
        ];
        $configuration = \Zend_Json::encode($configuration);

        $entity = (new \SprykerFeature\Zed\Salesrule\Persistence\Propel\SpySalesruleConditionQuery())
            ->filterByFkSalesrule($context['fk_salesrule'])
            ->filterByConfiguration($configuration)
            ->findOne();

        if ($entity) {
            $this->_error(self::CODE_GROUP_ALREADY_EXISTS);
            return false;
        } else {
            return true;
        }
    }
}
