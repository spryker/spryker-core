<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractGatewayController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerFeature\Zed\SelfServicePortal\Communication\SelfServicePortalCommunicationFactory getFactory()
 * @method \SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalRepositoryInterface getRepository()
 * @method \SprykerFeature\Zed\SelfServicePortal\Business\SelfServicePortalFacadeInterface getFacade()
 */
class ListAssetServiceController extends AbstractGatewayController
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
            ->createAssetSspServiceTable($request->attributes->get('reference'));

        return ['sspServiceTable' => $sspServiceTable->render()];
    }

    public function tableAction(Request $request): JsonResponse
    {
        $assetReference = (string)$request->query->get('ssp_asset_reference');
        $sspServiceTable = $this
            ->getFactory()
            ->createAssetSspServiceTable($assetReference);

        return $this->jsonResponse(
            $sspServiceTable->fetchData(),
        );
    }
}
