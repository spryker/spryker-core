<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\Controller;

use Generated\Shared\Transfer\FileTransfer;
use Generated\Shared\Transfer\SspAssetConditionsTransfer;
use Generated\Shared\Transfer\SspAssetCriteriaTransfer;
use Generated\Shared\Transfer\SspAssetIncludeTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * @method \SprykerFeature\Zed\SelfServicePortal\Business\SelfServicePortalFacadeInterface getFacade()
 * @method \SprykerFeature\Zed\SelfServicePortal\Communication\SelfServicePortalCommunicationFactory getFactory()
 * @method \SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalRepositoryInterface getRepository()
 */
class AssetImageController extends AbstractController
{
    /**
     * @var string
     */
    protected const MESSAGE_IMAGE_UNAVAILABLE = 'asset.image.unavailable';

    /**
     * @var string
     */
    protected const CONTENT_TYPE = 'Content-Type';

    /**
     * @var string
     */
    protected const DISPOSITION_INLINE = 'inline';

    /**
     * @var string
     */
    protected const HEADER_CONTENT_DISPOSITION = 'Content-Disposition';

    /**
     * @var string
     */
    protected const HEADER_CACHE_CONTROL = 'Cache-Control';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request): Response
    {
        $sspAssetReference = $request->get('ssp-asset-reference');

        $sspAssetCollectionTransfer = $this->getFacade()->getSspAssetCollection(
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

        return $this->createDownloadResponse($sspAssetTransfer->getImageOrFail());
    }

    /**
     * @param \Generated\Shared\Transfer\FileTransfer $fileTransfer
     *
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    protected function createDownloadResponse(FileTransfer $fileTransfer): StreamedResponse
    {
        $chunkSize = $this->getFactory()->getConfig()->getSspAssetImageReadChunkSize();

        return $this->getFactory()
            ->getSelfServicePortalService()
            ->createFileDownloadResponse(
                $fileTransfer,
                $chunkSize,
            );
    }
}
