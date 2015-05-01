<?php

namespace SprykerFeature\Zed\Salesrule\Communication\Controller;

use Symfony\Component\HttpFoundation\Request;

use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;

class CodePoolInfoController extends AbstractController{

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


}
