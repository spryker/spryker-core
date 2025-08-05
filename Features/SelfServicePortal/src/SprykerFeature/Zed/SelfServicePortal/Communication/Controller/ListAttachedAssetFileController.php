<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\Controller;

use Generated\Shared\Transfer\SspAssetTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerFeature\Zed\SelfServicePortal\Communication\SelfServicePortalCommunicationFactory getFactory()
 * @method \SprykerFeature\Zed\SelfServicePortal\Business\SelfServicePortalFacadeInterface getFacade()
 * @method \SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalRepositoryInterface getRepository()
 */
class ListAttachedAssetFileController extends AbstractController
{
    /**
     * @var string
     */
    public const REQUEST_PARAM_ID_SSP_ASSET = 'id-ssp-asset';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array<mixed>
     */
    public function indexAction(Request $request): array
    {
        $attachedFileTable = $this
            ->getFactory()
            ->createAttachedSspAssetFileTable((new SspAssetTransfer())->setIdSspAsset($request->attributes->getInt(static::REQUEST_PARAM_ID_SSP_ASSET)));

        return ['attachedFileTable' => $attachedFileTable->render()];
    }

    public function tableAction(Request $request): JsonResponse
    {
        $attachedFileTable = $this
            ->getFactory()
            ->createAttachedSspAssetFileTable((new SspAssetTransfer())->setIdSspAsset($request->query->getInt(static::REQUEST_PARAM_ID_SSP_ASSET)));

        return $this->jsonResponse(
            $attachedFileTable->fetchData(),
        );
    }
}
