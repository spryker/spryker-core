<?php

namespace SprykerFeature\Zed\Salesrule\Communication\Grid\Condition;

use Symfony\Component\HttpFoundation\Request;
use SprykerFeature\Zed\Salesrule\Business\Model\Salesrule;

class DataSource
{

    /**
     * @return \SprykerFeature\Zed\Salesrule\Persistence\Propel\SpySalesruleConditionQuery
     */
    protected function getQuery()
    {
        $request = Request::createFromGlobals();
        $id = $request->query->get(Salesrule::ID_SALES_RULE_URL_PARAMETER);

        return \SprykerFeature\Zed\Salesrule\Persistence\Propel\SpySalesruleConditionQuery::create()
            ->withColumn('spy_salesrule_condition.condition', 'conditionn')
            ->filterByFkSalesrule($id);
    }

    /**
     * @param $field
     * @param $value
     * @param $row
     * @return mixed|string
     */
    public function formatOutputValue($field, $value, $row)
    {
        switch ($field) {
            case 'configuration':
                return $this->renderConfiguration($value);
                break;
            case 'conditionn':
                return __($value);
                break;
            default:
                return $value;
        }
    }

    /**
     * @param string $value
     * @return string
     */
    protected function renderConfiguration($value)
    {
        $data = json_decode($value, true);
        $string = '';

        foreach ($data as $key => $value) {
            $string .= __($key) . ' => ' . $value . '<br/>';
        }
        return $string;
    }

}
