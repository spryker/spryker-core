<?php

namespace SprykerFeature\Zed\Salesrule\Communication\Form\CodePool;

use Symfony\Component\HttpFoundation\Request;
use SprykerFeature\Zed\Salesrule\Business\Model\Codepool;

class DataSource
{
    /**
     * @return \SprykerFeature\Zed\Salesrule\Persistence\Propel\SpySalesruleCodepoolQuery
     */
    protected function getQuery()
    {
        $idCodePool = Request::createFromGlobals()->query->get(Codepool::ID_CODE_POOL_URL_PARAMETER, false);
        if ($idCodePool) {
            return \SprykerFeature\Zed\Salesrule\Persistence\Propel\SpySalesruleCodepoolQuery::create()->filterByPrimaryKey($idCodePool);
        } else {
            return false;
        }
    }
}
