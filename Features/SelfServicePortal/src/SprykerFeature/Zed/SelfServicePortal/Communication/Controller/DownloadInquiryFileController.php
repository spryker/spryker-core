<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\Controller;

use ArrayObject;
use Generated\Shared\Transfer\FileConditionsTransfer;
use Generated\Shared\Transfer\FileCriteriaTransfer;
use Generated\Shared\Transfer\FileTransfer;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * @method \SprykerFeature\Zed\SelfServicePortal\Communication\SelfServicePortalCommunicationFactory getFactory()
 * @method \SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalRepositoryInterface getRepository()
 * @method \SprykerFeature\Zed\SelfServicePortal\Business\SelfServicePortalFacadeInterface getFacade()
 */
class DownloadInquiryFileController extends AbstractController
{
    /**
     * @var string
     */
    protected const MESSAGE_FILE_UNAVAILABLE = 'File was not found';

    /**
     * @var string
     */
    protected const REQUEST_PARAM_ID_FILE = 'id-file';

    /**
     * @uses \SprykerFeature\Zed\SelfServicePortal\Communication\Controller\ListInquiryController::indexAction()
     *
     * @var string
     */
    protected const REDIRECT_URL = '/self-service-portal/list-inquiry';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function downloadAction(Request $request): Response|RedirectResponse
    {
        $idFile = $request->get(static::REQUEST_PARAM_ID_FILE);
        $fileTransfers = $this->getFileTransfersByFileIds([$idFile]);

        if ($fileTransfers->count() === 0) {
            $this->addErrorMessage(static::MESSAGE_FILE_UNAVAILABLE);
            $redirectUrl = Url::generate(static::REDIRECT_URL)->build();

            return $this->redirectResponse($redirectUrl);
        }

        $fileTransfer = $fileTransfers->getIterator()->current();

        return $this->createDownloadResponse($fileTransfer);
    }

    protected function createDownloadResponse(FileTransfer $fileTransfer): StreamedResponse
    {
        $chunkSize = $this->getFactory()->getConfig()->getSspInquiryFileReadChunkSize();

        return $this->getFactory()
            ->getSelfServicePortalService()
            ->createFileDownloadResponse(
                $fileTransfer,
                $chunkSize,
            );
    }

    /**
     * @param list<int> $fileIds
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\FileTransfer>
     */
    protected function getFileTransfersByFileIds(array $fileIds): ArrayObject
    {
        $fileCriteriaTransfer = (new FileCriteriaTransfer())
            ->setFileConditions(
                (new FileConditionsTransfer())->setFileIds($fileIds),
            );

        $fileCollectionTransfer = $this->getFactory()->getFileManagerFacade()->getFileCollection($fileCriteriaTransfer);

        return $fileCollectionTransfer->getFiles();
    }
}
