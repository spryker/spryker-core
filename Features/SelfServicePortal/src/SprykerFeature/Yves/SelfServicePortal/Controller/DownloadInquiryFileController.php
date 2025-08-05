<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SelfServicePortal\Controller;

use Exception;
use Generated\Shared\Transfer\FileTransfer;
use Generated\Shared\Transfer\SspInquiryTransfer;
use Spryker\Shared\Log\LoggerTrait;
use Spryker\Yves\Kernel\PermissionAwareTrait;
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @method \SprykerFeature\Client\SelfServicePortal\SelfServicePortalClientInterface getClient()
 * @method \SprykerFeature\Yves\SelfServicePortal\SelfServicePortalFactory getFactory()
 * @method \SprykerFeature\Yves\SelfServicePortal\SelfServicePortalConfig getConfig()
 */
class DownloadInquiryFileController extends AbstractController
{
    use PermissionAwareTrait;
    use LoggerTrait;

    /**
     * @var string
     */
    protected const REQUEST_PARAM_UUID = 'uuid';

    /**
     * @var string
     */
    protected const REQUEST_PARAM_SSP_INQUIRY_REFERENCE = 'ssp-inquiry-reference';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @throws \Exception
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function downloadAction(Request $request): Response|RedirectResponse
    {
        $customerTransfer = $this->getFactory()->getCustomerClient()->getCustomer();

        if (!$customerTransfer) {
            throw new Exception('Customer not found');
        }

        $sspInquiryFileUuid = $request->get(static::REQUEST_PARAM_UUID);
        $sspInquiryReference = $request->get(static::REQUEST_PARAM_SSP_INQUIRY_REFERENCE);

        $sspInquiryTransfer = $this->getFactory()
            ->createSspInquiryReader()
            ->getSspInquiry($sspInquiryReference, $customerTransfer->getCompanyUserTransferOrFail());

        if (!$sspInquiryTransfer) {
            throw new NotFoundHttpException(sprintf(
                "Ssp Inquiry with provided Reference %s doesn't exist",
                $sspInquiryReference,
            ));
        }

        $fileTransfer = $this->findFileTransferByUuid($sspInquiryTransfer, $sspInquiryFileUuid);

        if (!$fileTransfer) {
            throw new NotFoundHttpException(sprintf(
                "File with provided UUID %s doesn't exist",
                $sspInquiryFileUuid,
            ));
        }

        return $this->createResponse($fileTransfer);
    }

    protected function findFileTransferByUuid(SspInquiryTransfer $sspInquiryTransfer, string $fileUuid): ?FileTransfer
    {
        foreach ($sspInquiryTransfer->getFiles() as $fileTransfer) {
            if ($fileTransfer->getUuid() === $fileUuid) {
                return $fileTransfer;
            }
        }

        return null;
    }

    protected function createResponse(FileTransfer $fileTransfer): Response
    {
        $chunkSize = $this->getFactory()->getConfig()->getInquiryFileDownloadChunkSize();

        return $this->getFactory()
            ->getSelfServicePortalService()
            ->createFileDownloadResponse($fileTransfer, $chunkSize);
    }
}
