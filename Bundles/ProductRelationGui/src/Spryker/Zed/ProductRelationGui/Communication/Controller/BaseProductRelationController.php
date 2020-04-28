<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelationGui\Communication\Controller;

use Generated\Shared\Transfer\ProductRelationTransfer;
use Generated\Shared\Transfer\PropelQueryBuilderRuleSetTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Spryker\Zed\ProductRelationGui\Communication\Table\AbstractProductTable;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ProductRelationGui\Communication\ProductRelationGuiCommunicationFactory getFactory()
 */
class BaseProductRelationController extends AbstractController
{
    protected const URL_PARAM_DATA = 'data';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function ruleQueryTableAction(Request $request): JsonResponse
    {
        $ruleSetJson = $request->get(static::URL_PARAM_DATA);

        $ruleSet = $this->getFactory()->getUtilEncodingService()
            ->decodeJson($ruleSetJson, true);

        $productRelationTransfer = $this->createProductRelationTransfer($ruleSet);

        $productRuleTable = $this->getFactory()
            ->createProductRuleTable($productRelationTransfer);

        return $this->jsonResponse(
            $productRuleTable->fetchData()
        );
    }

    /**
     * @param array $ruleSet
     *
     * @return \Generated\Shared\Transfer\ProductRelationTransfer
     */
    protected function createProductRelationTransfer(array $ruleSet): ProductRelationTransfer
    {
        $productRelationTransfer = new ProductRelationTransfer();
        $propelQueryBuilderRuleSetTransfer = new PropelQueryBuilderRuleSetTransfer();
        $propelQueryBuilderRuleSetTransfer->fromArray($ruleSet);
        $productRelationTransfer->setQuerySet($propelQueryBuilderRuleSetTransfer);

        return $productRelationTransfer;
    }

    /**
     * @param int|null $idProductRelation
     *
     * @return \Spryker\Zed\ProductRelationGui\Communication\Table\AbstractProductTable
     */
    protected function resolveProductTable(?int $idProductRelation = null): AbstractProductTable
    {
        if (!$this->getFactory()->getConfig()->useOptimizedProductTable()) {
            return $this->getFactory()->createProductTable($idProductRelation);
        }

        return $this->getFactory()->createProductAbstractTable($idProductRelation);
    }
}
