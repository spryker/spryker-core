<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspAssetManagement\Communication\Controller;

use Generated\Shared\Transfer\SspAssetConditionsTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractGatewayController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerFeature\Zed\SspAssetManagement\Communication\SspAssetManagementCommunicationFactory getFactory()
 * @method \SprykerFeature\Zed\SspAssetManagement\Persistence\SspAssetManagementRepositoryInterface getRepository()
 * @method \SprykerFeature\Zed\SspAssetManagement\Business\SspAssetManagementFacadeInterface getFacade()
 */
class IndexController extends AbstractGatewayController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array<mixed>
     */
    public function indexAction(Request $request): array
    {
        $sspAssetFilterForm = $this->getFactory()->getSspAssetFilterForm(
            (new SspAssetConditionsTransfer()),
        );

        $sspAssetFilterForm->handleRequest($request);

        $sspAssetCriteriaTransfer = $sspAssetFilterForm->isSubmitted() && $sspAssetFilterForm->isValid()
            ? $sspAssetFilterForm->getData()
            : new SspAssetConditionsTransfer();

        $sspAssetTable = $this
            ->getFactory()
            ->createSspAssetTable(new SspAssetConditionsTransfer());

        return $this->viewResponse([
            'sspAssetTable' => $sspAssetTable->render(),
            'sspAssetFilterForm' => $sspAssetFilterForm->createView(),
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function tableAction(Request $request): JsonResponse
    {
        $sspAssetFilterForm = $this->getFactory()->getSspAssetFilterForm(new SspAssetConditionsTransfer());

        $sspAssetFilterForm->handleRequest($request);

        $sspAssetCriteriaTransfer = $sspAssetFilterForm->isSubmitted() && $sspAssetFilterForm->isValid()
            ? $sspAssetFilterForm->getData()
            : new SspAssetConditionsTransfer();

        $sspAssetTable = $this
            ->getFactory()
            ->createSspAssetTable($sspAssetCriteriaTransfer);

        return $this->jsonResponse(
            $sspAssetTable->fetchData(),
        );
    }
}
