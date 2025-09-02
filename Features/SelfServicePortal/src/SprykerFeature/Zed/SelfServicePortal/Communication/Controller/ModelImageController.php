<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\Controller;

use Generated\Shared\Transfer\FileTransfer;
use Generated\Shared\Transfer\SspModelConditionsTransfer;
use Generated\Shared\Transfer\SspModelCriteriaTransfer;
use Generated\Shared\Transfer\SspModelIncludeTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * @method \SprykerFeature\Zed\SelfServicePortal\Business\SelfServicePortalFacadeInterface getFacade()
 * @method \SprykerFeature\Zed\SelfServicePortal\Communication\SelfServicePortalCommunicationFactory getFactory()
 * @method \SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalRepositoryInterface getRepository()
 */
class ModelImageController extends AbstractController
{
    /**
     * @var string
     */
    protected const DISPOSITION_INLINE = 'inline';

    /**
     * @var string
     */
    protected const PARAMETER_SSP_MODEL_REFERENCE = 'ssp-model-reference';

    public function indexAction(Request $request): Response
    {
        $sspModelReference = $request->get(static::PARAMETER_SSP_MODEL_REFERENCE);

        $sspModelCollectionTransfer = $this->getFacade()->getSspModelCollection(
            (new SspModelCriteriaTransfer())
                ->setInclude(
                    (new SspModelIncludeTransfer())
                        ->setWithImageFile(true),
                )
                ->setSspModelConditions(
                    (new SspModelConditionsTransfer())
                        ->setReferences([$sspModelReference]),
                ),
        );

        if ($sspModelCollectionTransfer->getSspModels()->count() === 0) {
            return $this->createNotFoundResponse();
        }

        /** @var \Generated\Shared\Transfer\SspModelTransfer $sspModelTransfer */
        $sspModelTransfer = $sspModelCollectionTransfer->getSspModels()->getIterator()->current();

        if (!$sspModelTransfer->getImage()) {
            return $this->createNotFoundResponse();
        }

        return $this->createDownloadResponse($sspModelTransfer->getImageOrFail());
    }

    protected function createDownloadResponse(FileTransfer $fileTransfer): StreamedResponse
    {
        $chunkSize = $this->getFactory()->getConfig()->getSspAssetImageReadChunkSize();

        return $this->getFactory()
            ->getSelfServicePortalService()
            ->createFileDownloadResponse(
                $fileTransfer,
                $chunkSize,
                static::DISPOSITION_INLINE,
            );
    }

    protected function createNotFoundResponse(): Response
    {
        return new Response('Image not found', 404);
    }
}
