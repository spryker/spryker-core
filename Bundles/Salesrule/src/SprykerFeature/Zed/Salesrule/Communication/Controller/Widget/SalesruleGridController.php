<?php

namespace SprykerFeature\Zed\Salesrule\Communication\Controller\Widget;

use SprykerFeature\Zed\Library\Controller\Action\AbstractGridController;
use Symfony\Component\HttpFoundation\Request;
use SprykerFeature\Zed\Salesrule\Communication\Grid\Salesrule\DataSource;
use SprykerFeature\Zed\Salesrule\Communication\Grid\Salesrule;

class SalesruleGridController extends AbstractGridController
{

    /**
     * @param Request $request
     * @return mixed|Salesrule
     */
    protected function initializeGrid(Request $request)
    {
        $salesRuleGridDataSource = new DataSource();

        return new Salesrule($salesRuleGridDataSource, null, '/salesrule/salesrule-grid');
    }


}
