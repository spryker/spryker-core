<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\Controller;

use Generated\Shared\Transfer\SspAssetTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractGatewayController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerFeature\Zed\SelfServicePortal\Communication\SelfServicePortalCommunicationFactory getFactory()
 * @method \SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalRepositoryInterface getRepository()
 * @method \SprykerFeature\Zed\SelfServicePortal\Business\SelfServicePortalFacadeInterface getFacade()
 */
class ListAssignedBusinessUnitAssetController extends AbstractGatewayController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array<mixed>
     */
    public function indexAction(Request $request): array
    {
        $sspAssignedBusinessUnitTable = $this
            ->getFactory()
            ->createAssignedBusinessUnitTable((new SspAssetTransfer())->setIdSspAsset($request->attributes->getInt('id-ssp-asset')));

        return ['sspAssignedBusinessUnitsTable' => $sspAssignedBusinessUnitTable->render()];
    }

    public function tableAction(Request $request): JsonResponse
    {
        $attachedBusinessUnitTable = $this
            ->getFactory()
            ->createAssignedBusinessUnitTable((new SspAssetTransfer())->setIdSspAsset($request->query->getInt('id-ssp-asset')));

        return $this->jsonResponse(
            $attachedBusinessUnitTable->fetchData(),
        );
    }
}
