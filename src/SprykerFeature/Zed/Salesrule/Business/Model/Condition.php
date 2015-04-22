<?php

namespace SprykerFeature\Zed\Salesrule\Business\Model;

class Condition
{

    const CONDITION_NAME_ARRAY_KEY = 'condition_name';

    /**
     * Used as URL GET parameter
     */
    const ID_SALES_RULE_PARAMETER = 'id-sales-rule';
    const ID_SALES_RULE_CONDITION_PARAMETER = 'id-sales-rule-condition';

    /**
     * Used in the forms and for persistence purposes
     */
    const ID_SALES_RULE_CONDITION = 'id_sales_rule_condition';
    const FK_SALESRULE_KEY = 'fk_salesrule';

    /**
     * @return array
     */
    public function getAvailableConditions()
    {
        return $this->factory->createSettings()->getAvailableConditions();
    }

    /**
     * @param string $name
     * @return \Zend_Form
     */
    public function getConditionFormByName($name)
    {
        /* @var AbstractCondition $condition */
        $conditionFacadeGetter = 'createModel' . $name;
        $condition = $this->factory->$conditionFacadeGetter();
        return $condition->getForm();
    }

    /**
     * @param \SprykerFeature\Zed\Salesrule\Persistence\Propel\SpySalesruleCondition $data
     * @return array
     */
    public function getFormDataFromConfiguration(\SprykerFeature\Zed\Salesrule\Persistence\Propel\SpySalesruleCondition $data)
    {
        $configuration = json_decode($data->getConfiguration());
        return (array) $configuration;
    }

    /**
     * @param array $data
     * @return array
     */
    protected function getPersistenceDataFromFormData(array $data)
    {
        $persistenceData = array();
        $configuration = $this->getConfiguration($data);
        $persistenceData['condition'] = $this->getConditionClassName($data);
        $configVars = $this->getKeyValuePairs((array) ($configuration));
        $persistenceData['configuration'] = json_encode($configVars);
        $persistenceData['fk_salesrule'] = $data[self::FK_SALESRULE_KEY];
        return $persistenceData;
    }

    /**
     * Removes '*' from protected attribute keys
     * @param $data
     * @param array $data
     * @return array
     */
    protected function getKeyValuePairs(array $data)
    {
        $result = array();
        foreach ($data as $key => $value) {
            $newKey = str_replace('*', '', $key);
            $result[trim($newKey)] = $value;
        }
        return $result;
    }

    /**
     * @param array $data
     * @return array
     */
    protected function getConfiguration(array $data)
    {
        $conditionFacadeGetter = $data[self::CONDITION_NAME_ARRAY_KEY];
        unset($data[self::CONDITION_NAME_ARRAY_KEY]);

        $methodName = 'createModel' . $conditionFacadeGetter;
        $condition = $this->factory->$methodName();
        $allowedConfigKeys = $condition->getAllowedConfigKeys();

        /**
         * remove all keys from the form post data array which are not
         * needed for the configuration of the condition
         */

        foreach ($data as $key => $value) {
            if (! in_array($key, $allowedConfigKeys)) {
                unset($data[$key]);
            }
        }

        return $data;
    }

    /**
     * @param array $data
     * @return string
     */
    protected function getConditionClassName(array $data)
    {
        return $data[self::CONDITION_NAME_ARRAY_KEY];
    }

    /**
     * @param array $conditionFormData
     * @return \SprykerFeature\Zed\Salesrule\Persistence\Propel\SpySalesruleCondition
     */
    public function saveCondition(array $conditionFormData)
    {
        $idSalesRuleCondition = $conditionFormData[self::ID_SALES_RULE_CONDITION] ? : null;

        $entity = \SprykerFeature\Zed\Salesrule\Persistence\Propel\SpySalesruleConditionQuery::create()
            ->filterByIdSalesruleCondition($idSalesRuleCondition)->findOneOrCreate();

        $data = $this->getPersistenceDataFromFormData($conditionFormData);
        $entity->fromArray($data);
        $entity->save();

        return $entity;
    }

    /**
     * @param int $conditionId
     */
    public function deleteSalesRuleCondition($conditionId)
    {
        $entity = \SprykerFeature\Zed\Salesrule\Persistence\Propel\SpySalesruleConditionQuery::create()->findPk($conditionId);
        if ($entity) {
            $entity->delete();
        }
    }
}
