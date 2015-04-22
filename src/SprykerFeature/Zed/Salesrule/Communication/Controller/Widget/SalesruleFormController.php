<?php

namespace SprykerFeature\Zed\Salesrule\Communication\Controller\Widget;

use SprykerFeature\Shared\Library\TransferLoader;
use SprykerFeature\Zed\Library\Controller\Action\AbstractFormController;
use Symfony\Component\HttpFoundation\Request;
use SprykerFeature\Zed\Salesrule\Communication\Form\Salesrule\DataSource;
use SprykerFeature\Zed\Salesrule\Communication\Form\Salesrule;
use SprykerFeature\Zed\Salesrule\Communication\Validator\SalesRule as Validator;
use SprykerFeature\Zed\Salesrule\Business\Model\Salesrule as SalesRuleModel;

class SalesruleFormController extends AbstractFormController
{

    /**
     * @param Request $request
     * @return mixed|Salesrule
     */
    protected function initializeForm(Request $request)
    {
        $dataSource = new DataSource();
        $form = new Salesrule($dataSource);
        $form->addValidatorChain(new Validator());

        return $form;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function saveAction(Request $request)
    {
        $this->initialize($request);
        if ($request->getMethod() === 'POST' && $this->form->isValid()) {
            $transfer = Locator::getInstance()->sales()->transferruleItem();
            $values = $this->form->getValues();
            $transfer->fromArray($this->form->getValues(), true);
            $entity = $this->facadeSalesrule->saveSalesRule($transfer);
            if (!$transfer->getIdSalesrule()) {
                $url = '/salesrule/index/edit?' . SalesRuleModel::ID_SALES_RULE_URL_PARAMETER . '=' . $entity->getIdSalesrule();
            } else {
                $url = '/salesrule/';
            }
            return $this->jsonResponse(['success' => true, 'redirectUrl' => $url]);
        } else {
            return $this->jsonResponse(['success' => false, 'form' => $this->form->render()]);
        }
    }


}
