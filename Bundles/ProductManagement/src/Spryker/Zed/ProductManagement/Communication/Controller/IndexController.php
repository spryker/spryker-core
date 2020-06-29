<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;

/**
 * @method \Spryker\Zed\ProductManagement\Business\ProductManagementFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductManagement\Persistence\ProductManagementQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductManagement\Communication\ProductManagementCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductManagement\Persistence\ProductManagementRepositoryInterface getRepository()
 */
class IndexController extends AbstractController
{
    public const ID_PRODUCT_ABSTRACT = 'id-product-abstract';

    /**
     * @return array
     */
    public function indexAction()
    {
        $productTable = $this
            ->getFactory()
            ->createProductTable();

        $viewData = $this->executeProductAbstractListActionViewDataExpanderPlugins([
            'productTable' => $productTable->render(),
        ]);

        return $this->viewResponse($viewData);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function tableAction()
    {
        $productTable = $this
            ->getFactory()
            ->createProductTable();

        return $this->jsonResponse(
            $productTable->fetchData()
        );
    }

    /**
     * @param array $viewData
     *
     * @return array
     */
    protected function executeProductAbstractListActionViewDataExpanderPlugins(array $viewData): array
    {
        foreach ($this->getFactory()->getProductAbstractListActionViewDataExpanderPlugins() as $productAbstractListActionViewDataExpanderPlugin) {
            $viewData = $productAbstractListActionViewDataExpanderPlugin->expand($viewData);
        }

        return $viewData;
    }
}
