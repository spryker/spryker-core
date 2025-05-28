<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantGui\Communication\Controller;

use Generated\Shared\Transfer\MerchantTableCriteriaTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\MerchantGui\Communication\MerchantGuiCommunicationFactory getFactory()
 */
class ListMerchantController extends AbstractController
{
    /**
     * @var string
     */
    protected const REQUEST_PARAM_MERCHANT_TABLE_FILTER_FORM = 'merchant_table_filter_form';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function indexAction(Request $request): array
    {
        $merchantTableCriteriaTransfer = $this->createMerchantTableCriteriaTransfer($request);
        $merchantTableFilterForm = $this->getFactory()
            ->createMerchantTableFilterForm()
            ->setData($merchantTableCriteriaTransfer);

        $merchantTable = $this->getFactory()->createMerchantTable();

        return $this->viewResponse([
            'merchants' => $merchantTable->render(),
            'merchantTableFilterForm' => $merchantTableFilterForm->createView(),
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function tableAction(Request $request): JsonResponse
    {
        $merchantTableCriteriaTransfer = $this->createMerchantTableCriteriaTransfer($request);
        $merchantTableFilterForm = $this->getFactory()->createMerchantTableFilterForm($merchantTableCriteriaTransfer);
        $merchantTableCriteriaTransfer = $merchantTableFilterForm->handleRequest($request)->getData();

        $merchantTable = $this->getFactory()->createMerchantTable();
        $merchantTable->applyCriteria($merchantTableCriteriaTransfer);

        return $this->jsonResponse($merchantTable->fetchData());
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\MerchantTableCriteriaTransfer
     */
    protected function createMerchantTableCriteriaTransfer(Request $request): MerchantTableCriteriaTransfer
    {
        return (new MerchantTableCriteriaTransfer())
            ->fromArray($request->query->all()[static::REQUEST_PARAM_MERCHANT_TABLE_FILTER_FORM] ?? [], true);
    }
}
