<?php

namespace SprykerFeature\Zed\Salesrule\Communication\Controller\Widget;

use SprykerFeature\Shared\Library\TransferLoader;
use SprykerFeature\Zed\Library\Controller\Action\AbstractFormController;
use Symfony\Component\HttpFoundation\Request;
use SprykerFeature\Zed\Salesrule\Communication\Form\CodePool\DataSource;
use SprykerFeature\Zed\Salesrule\Communication\Form\CodePool;


class CodePoolFormController extends AbstractFormController
{

    /**
     * @param Request $request
     * @return mixed|CodePool
     */
    protected function initializeForm(Request $request)
    {
        $codePoolDataSource = new DataSource();

        return new CodePool($codePoolDataSource, $request);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function saveAction(Request $request)
    {
        $this->initialize($request);

        if ($request->getMethod() === 'POST' && $this->form->isValid()) {
            $formData = $this->form->getValues();
            $transfer = Locator::getInstance()->sales()->transferruleCodePool($formData);
            $openTab = 'existing-code-pool-tab';
            if (!$transfer->getIdSalesruleCodepool()) {
                $openTab = 'manage-codes-tab';
            }
            $entity = $this->facadeSalesrule->saveCodePool($transfer);
            $idCodePool = $entity->getPrimaryKey();
            $idSalesRule = $request->request->get('id-sales-rule');
            $addContext = $request->request->get('add-condition');

            if ($request->request->get('add-condition') !== null) {
                $this->facadeSalesrule->saveCondition($this->getConditionData($idSalesRule, $idCodePool));
            }
            $url = $this->createUrl($idSalesRule, $addContext, $idCodePool);
            $response = ['success' => true, 'idCodePool' => $idCodePool, 'url' => $url, 'openTab' => $openTab];

            return $this->jsonResponse($response);
        } else {
            return $this->jsonResponse(['success' => false, 'form' => $this->form->render()]);
        }
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

    /**
     * @param $idSalesRule
     * @param $addContext
     * @param $idCodePool
     * @return string
     */
    protected function createUrl($idSalesRule, $addContext, $idCodePool)
    {
        $url = '/salesrule/condition-ajax/index?condition-form-name=ConditionVoucherCodeInPool&id-sales-rule='
            . $idSalesRule . '&add-condition=' . $addContext;

        if ($addContext) {
            $url .= '&add-codes=1';
        }
        $url .= '&id-code-pool=' . $idCodePool;

        return $url;
    }


}
