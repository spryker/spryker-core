<?php

namespace SprykerFeature\Zed\Salesrule\Communication\Controller;

use Symfony\Component\HttpFoundation\Request;
use SprykerFeature\Zed\Salesrule\Communication\Grid\Condition;
use SprykerFeature\Zed\Salesrule\Business\Model\Salesrule as SalesRuleModel;

use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;

class IndexController extends AbstractController
{

    public function indexAction()
    {
    }

    /**
     * @param Request $request
     * @return array
     */
    public function editAction(Request $request)
    {
        $viewVariables = [];
        $idSalesRule = $request->query->get(SalesRuleModel::ID_SALES_RULE_URL_PARAMETER);
        $viewVariables['idSalesRule'] = $idSalesRule;
        if ($idSalesRule) {
            $viewVariables['showConditionGrid'] = true;
        }
        $viewVariables['availableConditions'] = $this->getConditions(
            $request->query->get('id-sales-rule'),
            $this->facadeSalesrule->getAvailableConditions()
        );

        return $viewVariables;
    }

    /**
     * @param $idSalesRule
     * @param array $conditions
     * @return array
     */
    protected function getConditions($idSalesRule, array $conditions)
    {
        $availableConditions = [];
        foreach ($conditions as $condition) {
            $conditionConfig = [];
            $conditionConfig['linkName'] = $condition::getConditionName();
            $conditionConfig['ajaxUrl'] = '/salesrule/condition-ajax/index?' . SalesRuleModel::ID_SALES_RULE_URL_PARAMETER . '=' . $idSalesRule . '&condition-form-name=' . $condition::$conditionFacadeGetter;
            $conditionConfig['conditionName'] = $condition::$conditionName;
            $availableConditions[] = $conditionConfig;
        }
        return $availableConditions;
    }


}
