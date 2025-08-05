<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SelfServicePortal\Controller;

use Generated\Shared\Transfer\FileTransfer;
use Generated\Shared\Transfer\SspAssetConditionsTransfer;
use Generated\Shared\Transfer\SspAssetCriteriaTransfer;
use Generated\Shared\Transfer\SspAssetIncludeTransfer;
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

/**
 * @method \SprykerFeature\Yves\SelfServicePortal\SelfServicePortalFactory getFactory()
 * @method \SprykerFeature\Client\SelfServicePortal\SelfServicePortalClientInterface getClient()
 * @method \SprykerFeature\Yves\SelfServicePortal\SelfServicePortalConfig getConfig()
 */
class DownloadAssetImageController extends AbstractController
{
    /**
     * @var string
     */
    protected const REQUEST_PARAM_SSP_ASSET_REFERENCE = 'ssp-asset-reference';

    public function viewImageAction(Request $request): Response
    {
        $sspAssetReference = $request->get(static::REQUEST_PARAM_SSP_ASSET_REFERENCE);

        $sspAssetCollectionTransfer = $this->getClient()->getSspAssetCollection(
            (new SspAssetCriteriaTransfer())
                ->setInclude(
                    (new SspAssetIncludeTransfer())
                        ->setWithImageFile(true),
                )
                ->setSspAssetConditions(
                    (new SspAssetConditionsTransfer())
                        ->addReference($sspAssetReference),
                ),
        );

        /** @var \Generated\Shared\Transfer\SspAssetTransfer $sspAssetTransfer */
        $sspAssetTransfer = $sspAssetCollectionTransfer->getSspAssets()->getIterator()->current();

        return $this->createResponse($sspAssetTransfer->getImageOrFail());
    }

    protected function createResponse(FileTransfer $fileTransfer): Response
    {
        $chunkSize = $this->getFactory()->getConfig()->getSspAssetImageDownloadChunkSize();

        return $this->getFactory()
            ->getSelfServicePortalService()
            ->createFileDownloadResponse(
                $fileTransfer,
                $chunkSize,
                ResponseHeaderBag::DISPOSITION_INLINE,
            );
    }
}
