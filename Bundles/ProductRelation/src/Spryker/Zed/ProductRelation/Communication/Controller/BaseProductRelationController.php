<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelation\Communication\Controller;

use Generated\Shared\Transfer\ProductRelationTransfer;
use Generated\Shared\Transfer\PropelQueryBuilderRuleSetTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ProductRelation\Communication\ProductRelationCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductRelation\Business\ProductRelationFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductRelation\Persistence\ProductRelationQueryContainerInterface getQueryContainer()
 */
class BaseProductRelationController extends AbstractController
{
    public const URL_PARAM_DATA = 'data';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function ruleQueryTableAction(Request $request)
    {
        $ruleSetJson = $request->get(static::URL_PARAM_DATA);

        $utilEncodingService = $this->getFactory()->getUtilEncodingService();
        $ruleSet = $utilEncodingService->decodeJson($ruleSetJson, true);

        $productRelationTransfer = new ProductRelationTransfer();
        $propelQueryBuilderRuleSetTransfer = new PropelQueryBuilderRuleSetTransfer();
        $propelQueryBuilderRuleSetTransfer->fromArray($ruleSet);
        $productRelationTransfer->setQuerySet($propelQueryBuilderRuleSetTransfer);

        $productRuleTable = $this->getFactory()
            ->createProductRuleTable($productRelationTransfer);

        return $this->jsonResponse(
            $productRuleTable->fetchData()
        );
    }
}
