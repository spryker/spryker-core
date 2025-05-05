<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspFileManagement\Communication\Controller;

use Generated\Shared\Transfer\SspAssetTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractGatewayController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerFeature\Zed\SspFileManagement\Communication\SspFileManagementCommunicationFactory getFactory()
 * @method \SprykerFeature\Zed\SspFileManagement\Persistence\SspFileManagementRepositoryInterface getRepository()
 * @method \SprykerFeature\Zed\SspFileManagement\Business\SspFileManagementFacadeInterface getFacade()
 */
class AttachedSspAssetFileListController extends AbstractGatewayController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array<mixed>
     */
    public function indexAction(Request $request): array
    {
        $attachedFileTable = $this
            ->getFactory()
            ->createAttachedSspAssetFileTable((new SspAssetTransfer())->setIdSspAsset($request->attributes->getInt('id-ssp-asset')));

        return ['attachedFileTable' => $attachedFileTable->render()];
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function tableAction(Request $request): JsonResponse
    {
        $attachedFileTable = $this
            ->getFactory()
            ->createAttachedSspAssetFileTable((new SspAssetTransfer())->setIdSspAsset($request->query->getInt('id-ssp-asset')));

        return $this->jsonResponse(
            $attachedFileTable->fetchData(),
        );
    }
}
