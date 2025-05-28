<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Controller;

use Generated\Shared\Transfer\ProductTableCriteriaTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ProductManagement\Business\ProductManagementFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductManagement\Persistence\ProductManagementQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductManagement\Communication\ProductManagementCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductManagement\Persistence\ProductManagementRepositoryInterface getRepository()
 */
class IndexController extends AbstractController
{
    /**
     * @var string
     */
    public const ID_PRODUCT_ABSTRACT = 'id-product-abstract';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array<string, mixed>
     */
    public function indexAction(Request $request)
    {
        $productTableCriteriaTransfer = $this->handleTableFilter($request);
        $table = $this->getFactory()->createProductTable()->applyCriteria($productTableCriteriaTransfer);

        $viewData = [
            'externalFields' => $this->getFactory()->getConfig()->getProductTableFilterFormExternalFieldNames(),
            'tableFilterForm' => $this->getFactory()->createTableFilterForm($productTableCriteriaTransfer, $this->getFactory()->createTableFilterFormDataProvider()->getOptions())->createView(),
            'productTable' => $table->render(),
        ];

        $viewData = $this->executeProductAbstractListActionViewDataExpanderPlugins($viewData);

        return $this->viewResponse($viewData);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function tableAction(Request $request)
    {
        $productTableCriteriaTransfer = $this->handleTableFilter($request);
        $table = $this->getFactory()->createProductTable();
        $table->applyCriteria($productTableCriteriaTransfer);

        return $this->jsonResponse(
            $table->fetchData(),
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\ProductTableCriteriaTransfer
     */
    protected function handleTableFilter(Request $request): ProductTableCriteriaTransfer
    {
        $productTableCriteriaTransfer = (new ProductTableCriteriaTransfer())->fromArray($request->query->all(), true);
        $tableFilterFormDataProvider = $this->getFactory()->createTableFilterFormDataProvider();
        $tableFilterForm = $this->getFactory()->createTableFilterForm($productTableCriteriaTransfer, $tableFilterFormDataProvider->getOptions());

        return $tableFilterForm->handleRequest($request)->getData();
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
