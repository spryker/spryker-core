<?php

namespace SprykerFeature\Zed\Salesrule\Communication\Controller;

use Symfony\Component\HttpFoundation\Request;
use SprykerFeature\Zed\Salesrule\Business\Model\Codepool;
use SprykerFeature\Zed\Salesrule\Communication\Form\CodeBulk;
use SprykerFeature\Zed\Salesrule\Communication\Grid\Code\DataSource;
use SprykerFeature\Zed\Salesrule\Communication\Grid\Code;
use SprykerFeature\Zed\Salesrule\Communication\Form\Code as CodeForm;

use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;

class CodeAjaxController extends AbstractController
{

    /**
     * @param Request $request
     * @return array
     * @throws \ErrorException
     */
    public function indexAction(Request $request)
    {
        $idCodePool = $request->query->get(Codepool::ID_CODE_POOL_URL_PARAMETER);
        $codeForm = $this->getCodeForm($idCodePool);
        $codeBulkForm = $this->getCodeBulkForm($idCodePool);

        $codeGridDataSource = new DataSource();
        $grid = new Code($codeGridDataSource);

        if ($request->getMethod() === 'POST') {
            if ($this->isBulk($request)) {
                if ($codeBulkForm->isValid($request->request->all())) {
                    $idCodePool = $request->request->get(Codepool::ID_CODE_POOL);
                    $prefix = $this->facadeSalesrule->getSalesruleCodePrefixByCodepoolId($idCodePool);
                    $this->facadeSalesrule->createCodes($idCodePool, $request->request->get('amount'), 6, $prefix, null);
                    $this->addMessageSuccess(__('Codes successfully created'));
                    $codeBulkForm = $this->getCodeBulkForm($idCodePool);
                }
            } else {
                if ($codeForm->isValid($request->request->all())) {
                    $idCodePool = $request->query->get(Codepool::ID_CODE_POOL);
                    $this->facadeSalesrule->createCode($idCodePool, $request->query->get('code'));
                    $this->addMessageSuccess(__('Code successfully created'));
                    $codeForm = $this->getCodeForm($idCodePool);
                }
            }
        }

        return $this->viewResponse([
            'grid' => $grid,
            'idCodePool' => $idCodePool,
            'codeForm' => $codeForm,
            'codeBulkForm' => $codeBulkForm
        ]);
    }

    /**
     * @param Request $request
     * @return bool
     */
    protected function isBulk(Request $request)
    {
        return $request->query->get('code') ? false : true;
    }

    /**
     * @param $idCodePool
     * @return CodeForm
     */
    protected function getCodeForm($idCodePool)
    {
        return new CodeForm($idCodePool);
    }

    /**
     * @param $idCodePool
     * @return CodeBulk
     */
    protected function getCodeBulkForm($idCodePool)
    {
        return new CodeBulk($idCodePool);
    }

    /**
     * @param Request $request
     */
    public function downloadAction(Request $request)
    {
        ini_set('memory_limit', '2G');
        ini_set('max_execution_time', '600');


        $this->facadeSalesrule->downloadCodesFromCodePool($request->query->get('sales-rule-codepool'));
    }


}
