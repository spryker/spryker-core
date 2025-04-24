<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspAssetManagement\Communication\Controller;

use Generated\Shared\Transfer\SspAssetTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractGatewayController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerFeature\Zed\SspAssetManagement\Communication\SspAssetManagementCommunicationFactory getFactory()
 * @method \SprykerFeature\Zed\SspAssetManagement\Persistence\SspAssetManagementRepositoryInterface getRepository()
 * @method \SprykerFeature\Zed\SspAssetManagement\Business\SspAssetManagementFacadeInterface getFacade()
 */
class SspAssetInquiryListController extends AbstractGatewayController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array<mixed>
     */
    public function indexAction(Request $request): array
    {
        $sspInquiryTable = $this
            ->getFactory()
            ->createSspInquiryTable((new SspAssetTransfer())->setIdSspAsset($request->attributes->getInt('id-ssp-asset')));

        return ['sspInquiryTable' => $sspInquiryTable->render()];
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function tableAction(Request $request): JsonResponse
    {
        $sspInquiryTable = $this
            ->getFactory()
            ->createSspInquiryTable((new SspAssetTransfer())->setIdSspAsset($request->query->getInt('id-ssp-asset')));

        return $this->jsonResponse(
            $sspInquiryTable->fetchData(),
        );
    }
}
