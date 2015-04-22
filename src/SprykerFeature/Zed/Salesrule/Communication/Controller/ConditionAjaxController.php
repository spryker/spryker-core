<?php

namespace SprykerFeature\Zed\Salesrule\Communication\Controller;

use Symfony\Component\HttpFoundation\Request;
use SprykerFeature\Zed\Salesrule\Communication\Form\CodePool;

use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;

class ConditionAjaxController extends AbstractController
{

    /**
     * @param Request $request
     * @return array
     */
    public function indexAction(Request $request)
    {
        $form = $this->getForm($request);
        $codePoolDataSource = new CodePool\DataSource();
        $codePoolForm = new CodePool($codePoolDataSource, $request);

        return $this->viewResponse([
            'form' => $form,
            'idSalesRule' => $request->query->get('id-sales-rule'),
            'addCodes' => (int) $request->query->get('add-codes'),
            'codePoolForm' => $codePoolForm,
            'addContext' => $this->isAddContext($request),
            'idCodePool' => $request->query->get('id-code-pool')
        ]);
    }

    /**
     * @param Request $request
     * @return array
     * @throws \ErrorException
     * @throws \Zend_Form_Exception
     */
    public function editAction(Request $request)
    {
        $viewVariables = [];
        $form = $this->getForm($request);
        $viewVariables['idSalesRule'] = $request->query->get('id-sales-rule');

        if ($request->getMethod() === 'POST') {
            if ($form) {
                if ($form->isValid($request->request->all())) {
                    $this->addMessageSuccess(__('Condition successfully saved'));
                    $this->facadeSalesrule->saveCondition($form->getValues());
                } else {
                    $viewVariables['form'] = $form;
                }
            }
        } else {
            if ($request->query->get('id-sales-rule-condition')) {
                $idSalesRuleCondition = $request->query->get('id-sales-rule-condition');
                $entity = \SprykerFeature\Zed\Salesrule\Persistence\Propel\SpySalesruleConditionQuery::create()->findOneByIdSalesruleCondition($idSalesRuleCondition);
                $form = $this->facadeSalesrule->getConditionFormByName($entity->getCondition());
                $form->populate($this->facadeSalesrule->getFormDataFromConfiguration($entity));
            } else {
                $form = $this->getForm($request);
            }
            $viewVariables['form'] = $form;
        }

        return $viewVariables;
    }

    /**
     * @param Request $request
     * @return null|\Zend_Form
     */
    protected function getForm(Request $request)
    {
        if ($request->query->get('condition-form-name')) {
            $conditionName = $request->query->get('condition-form-name');
            return $this->facadeSalesrule->getConditionFormByName($conditionName);
        }

        if ($request->query->get('id-sales-rule-condition')) {
            $idSalesRuleCondition = $request->query->get('id-sales-rule-condition');
            $entity = \SprykerFeature\Zed\Salesrule\Persistence\Propel\SpySalesruleConditionQuery::create()->findOneByIdSalesruleCondition($idSalesRuleCondition);
            $form = $this->facadeSalesrule->getConditionFormByName($entity->getCondition());
            if (!$request->getMethod() === 'POST') {
                return $form->populate($this->facadeSalesrule->getFormDataFromConfiguration($entity));
            } else {
                return $form;
            }
        }

        return null;
    }

    /**
     * @param Request $request
     * @return bool
     */
    protected function isAddContext(Request $request)
    {
        return (bool)$request->query->get('add-condition');
    }


}
