<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelation\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;

/**
 * @method \Spryker\Zed\ProductRelation\Communication\ProductRelationCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductRelation\Business\ProductRelationFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductRelation\Persistence\ProductRelationQueryContainerInterface getQueryContainer()
 */
class ListController extends AbstractController
{
    /**
     * @return array
     */
    public function indexAction()
    {
        $productRelationTable = $this->getFactory()
            ->createProductRelationTable();

        return [
            'productRelationTable' => $productRelationTable->render(),
        ];
    }

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function tableAction()
    {
        $productRelationTable = $this->getFactory()
            ->createProductRelationTable();

        return $this->jsonResponse($productRelationTable->fetchData());
    }
}
