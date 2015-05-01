<?php

namespace SprykerFeature\Zed\Salesrule\Communication\Form\Salesrule;

use SprykerFeature\Zed\Salesrule\Business\Model\Salesrule;

class DataSource
{

    /**
     * @return \SprykerFeature\Zed\Salesrule\Persistence\Propel\SpySalesruleQuery
     */
    protected function getQuery()
    {
        $idSalesRule = $this->request->query->get(Salesrule::ID_SALES_RULE_URL_PARAMETER);
        return \SprykerFeature\Zed\Salesrule\Persistence\Propel\SpySalesruleQuery::create()->filterByPrimaryKey($idSalesRule);
    }
}
