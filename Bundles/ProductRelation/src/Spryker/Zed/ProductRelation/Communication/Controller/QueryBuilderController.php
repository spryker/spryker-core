<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelation\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @method \Spryker\Zed\ProductRelation\Communication\ProductRelationCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductRelation\Business\ProductRelationFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductRelation\Persistence\ProductRelationQueryContainerInterface getQueryContainer()
 */
class QueryBuilderController extends AbstractController
{
    public const URL_PARAM_ID_PRODUCT_RELATION = 'id-product-relation';

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function loadFilterSetAction()
    {
        $queryBuilderFilterProvider = $this->getFactory()
            ->createQueryBuilderFilterProvider();

        return new JsonResponse($queryBuilderFilterProvider->getFilters());
    }
}
