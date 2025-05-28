<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferGui\Communication\Controller;

use Generated\Shared\Transfer\ProductOfferTableCriteriaTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ProductOfferGui\Communication\ProductOfferGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductOfferGui\Persistence\ProductOfferGuiRepositoryInterface getRepository()
 */
class ListController extends AbstractController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array<string, mixed>
     */
    public function indexAction(Request $request): array
    {
        $productOfferTableCriteriaTransfer = (new ProductOfferTableCriteriaTransfer())->fromArray($request->query->all(), true);
        $tableFilterFormDataProvider = $this->getFactory()->createTableFilterFormDataProvider();
        $tableFilterForm = $this->getFactory()->createTableFilterForm($productOfferTableCriteriaTransfer, $tableFilterFormDataProvider->getOptions());
        $productOfferTableCriteriaTransfer = $tableFilterForm->handleRequest($request)->getData();

        $productOfferTable = $this->getFactory()->createProductOfferTable()->applyCriteria($productOfferTableCriteriaTransfer);

        $viewData = $this->executeProductOfferListActionViewDataExpanderPlugins([
            'productOfferTable' => $productOfferTable->render(),
            'tableFilterForm' => $tableFilterForm->createView(),
            'externalFields' => $this->getFactory()->getConfig()->getProductOfferTableFilterFormExternalFieldNames(),
        ]);

        return $this->viewResponse($viewData);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function tableAction(Request $request): JsonResponse
    {
        $productOfferTableCriteriaTransfer = (new ProductOfferTableCriteriaTransfer())->fromArray($request->query->all(), true);
        $tableFilterFormDataProvider = $this->getFactory()->createTableFilterFormDataProvider();
        $tableFilterForm = $this->getFactory()->createTableFilterForm($productOfferTableCriteriaTransfer, $tableFilterFormDataProvider->getOptions());
        $productOfferTableCriteriaTransfer = $tableFilterForm->handleRequest($request)->getData();

        $productOfferTable = $this->getFactory()->createProductOfferTable();
        $productOfferTable->applyCriteria($productOfferTableCriteriaTransfer);

        return $this->jsonResponse($productOfferTable->fetchData());
    }

    /**
     * @param array<string, mixed> $viewData
     *
     * @return array<string, mixed>
     */
    protected function executeProductOfferListActionViewDataExpanderPlugins(array $viewData): array
    {
        foreach ($this->getFactory()->getProductOfferListActionViewDataExpanderPlugins() as $productOfferListActionViewDataExpanderPlugin) {
            $viewData = $productOfferListActionViewDataExpanderPlugin->expand($viewData);
        }

        return $viewData;
    }
}
