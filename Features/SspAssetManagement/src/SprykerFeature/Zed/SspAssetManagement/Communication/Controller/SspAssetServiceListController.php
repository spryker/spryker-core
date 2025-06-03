<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspAssetManagement\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractGatewayController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerFeature\Zed\SspAssetManagement\Communication\SspAssetManagementCommunicationFactory getFactory()
 * @method \SprykerFeature\Zed\SspAssetManagement\Persistence\SspAssetManagementRepositoryInterface getRepository()
 * @method \SprykerFeature\Zed\SspAssetManagement\Business\SspAssetManagementFacadeInterface getFacade()
 */
class SspAssetServiceListController extends AbstractGatewayController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array<mixed>
     */
    public function indexAction(Request $request): array
    {
        $sspServiceTable = $this
            ->getFactory()
            ->createSspServiceTable($request->attributes->get('reference'));

        return ['sspServiceTable' => $sspServiceTable->render()];
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function tableAction(Request $request): JsonResponse
    {
        $assetReference = (string)$request->query->get('ssp_asset_reference');
        $sspServiceTable = $this
            ->getFactory()
            ->createSspServiceTable($assetReference);

        return $this->jsonResponse(
            $sspServiceTable->fetchData(),
        );
    }
}
