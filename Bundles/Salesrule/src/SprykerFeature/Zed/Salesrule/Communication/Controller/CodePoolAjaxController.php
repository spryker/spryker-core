<?php

namespace SprykerFeature\Zed\Salesrule\Communication\Controller;

use SprykerFeature\Shared\Library\TransferLoader;
use Symfony\Component\HttpFoundation\Request;
use SprykerFeature\Zed\Salesrule\Communication\Form\CodePool;

use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;

class CodePoolAjaxController extends AbstractController
{

    /**
     * @param Request $request
     * @return array
     */
    public function indexAction(Request $request)
    {
        $codePoolDataSource = new CodePool\DataSource();
        $codePoolForm = new CodePool($codePoolDataSource, $request);

        return $this->viewResponse([
            'form' => $codePoolForm
        ]);
    }

    /**
     * @param Request $request
     * @return array
     */
    public function infoAction(Request $request)
    {
        $idSalesRule = $request->query->get('id-sales-rule');

        $idCodePool = $request->query->get('id-code-pool');
        $codePool = \SprykerFeature\Zed\Salesrule\Persistence\Propel\SpySalesruleCodepoolQuery::create()->findPk($idCodePool);

        return $this->viewResponse([
            'idSalesRule' => $idSalesRule,
            'codePool' => $codePool
        ]);
    }

    /**
     * @param Request $request
     * @return array|JsonResponse
     * @throws \ErrorException
     */
    public function editAction(Request $request)
    {
        $viewVariables = [];

        $idSalesRule = $request->query->get('id-sales-rule');
        $viewVariables['idSalesRule'] = $idSalesRule;

        $codePoolDataSource = new CodePool\DataSource();
        $form = new CodePool($codePoolDataSource);
        $viewVariables['addContext'] = $request->query->get('add-condition');

        if ($request->getMethod() === 'POST' && $form->isValid($request->request->all())) {
            $this->addMessageSuccess(__('Code group successfully saved'));
            $entity = $this->facadeSalesrule->saveCodePool(new \Generated\Shared\Transfer\SalesRuleCodePool($form->getValuesTransfer()));
            $idCodePool = $entity->getPrimaryKey();
            if ($request->query->get('add-condition')) {
                $this->facadeSalesrule->saveCondition($this->getConditionData($idSalesRule, $idCodePool));
            }
            return $this->jsonResponse(array('idcodepool' => $idCodePool));
        } else {
            $form->populateWithDataSource();
            $viewVariables['form'] = $form;
        }

        return $viewVariables;
    }

    /**
     * @param int $idSalesRule
     * @param int $idCodePool
     * @return array
     */
    protected function getConditionData($idSalesRule, $idCodePool)
    {
        return $this->viewResponse([
            'condition_name' => 'ConditionVoucherCodeInPool',
            'number' => $idCodePool,
            'fk_salesrule' => $idSalesRule,
            'id_sales_rule_condition' => null
        ]);
    }


}
