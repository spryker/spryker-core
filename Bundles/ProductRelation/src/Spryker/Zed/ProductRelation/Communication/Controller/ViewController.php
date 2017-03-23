<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelation\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ProductRelation\Communication\ProductRelationCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductRelation\Business\ProductRelationFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductRelation\Persistence\ProductRelationQueryContainerInterface getQueryContainer()
 */
class ViewController extends AbstractController
{

    const URL_PARAM_ID_PRODUCT_RELATION = 'id-product-relation';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function indexAction(Request $request)
    {
        $idProductRelation = $this->castId($request->query->get(static::URL_PARAM_ID_PRODUCT_RELATION));

        $productRelationTransfer = $this->getFacade()->findProductRelationById($idProductRelation);

        $productTable = $this->getFactory()->createProductTable($idProductRelation);

        $productAbstractTransfer = $this->getFactory()
            ->getProductFacade()
            ->findProductAbstractById($productRelationTransfer->getFkProductAbstract());

        return [
            'productRelation' => $productRelationTransfer,
            'product' => $productAbstractTransfer,
            'productTable' => $productTable->render(),
        ];
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function tableAction(Request $request)
    {
        $idProductRelation = $this->castId($request->query->get(static::URL_PARAM_ID_PRODUCT_RELATION));

        $productTable = $this->getFactory()->createProductTable($idProductRelation);

        return $this->jsonResponse(
            $productTable->fetchData()
        );
    }

}
