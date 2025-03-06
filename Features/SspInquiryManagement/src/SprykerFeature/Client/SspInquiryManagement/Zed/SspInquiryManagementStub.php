<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Client\SspInquiryManagement\Zed;

use Generated\Shared\Transfer\FileManagerDataTransfer;
use Generated\Shared\Transfer\SspInquiryCollectionRequestTransfer;
use Generated\Shared\Transfer\SspInquiryCollectionResponseTransfer;
use Generated\Shared\Transfer\SspInquiryCollectionTransfer;
use Generated\Shared\Transfer\SspInquiryCriteriaTransfer;
use Generated\Shared\Transfer\SspInquiryFileDownloadRequestTransfer;
use Spryker\Client\ZedRequest\ZedRequestClientInterface;

class SspInquiryManagementStub implements SspInquiryManagementStubInterface
{
    /**
     * @var \Spryker\Client\ZedRequest\ZedRequestClientInterface
     */
    protected ZedRequestClientInterface $zedRequestClient;

    /**
     * @param \Spryker\Client\ZedRequest\ZedRequestClientInterface $zedRequestClient
     */
    public function __construct(ZedRequestClientInterface $zedRequestClient)
    {
        $this->zedRequestClient = $zedRequestClient;
    }

    /**
     * @param \Generated\Shared\Transfer\SspInquiryCollectionRequestTransfer $sspInquiryCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\SspInquiryCollectionResponseTransfer
     */
    public function createSspInquiryCollection(SspInquiryCollectionRequestTransfer $sspInquiryCollectionRequestTransfer): SspInquiryCollectionResponseTransfer
    {
        /** @var \Generated\Shared\Transfer\SspInquiryCollectionResponseTransfer $sspInquiryCollectionResponseTransfer */
         $sspInquiryCollectionResponseTransfer = $this->zedRequestClient->call('/ssp-inquiry-management/gateway/create-ssp-inquiry-collection', $sspInquiryCollectionRequestTransfer);

        return $sspInquiryCollectionResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SspInquiryCriteriaTransfer $sspInquiryCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\SspInquiryCollectionTransfer
     */
    public function getSspInquiryCollection(SspInquiryCriteriaTransfer $sspInquiryCriteriaTransfer): SspInquiryCollectionTransfer
    {
        /** @var \Generated\Shared\Transfer\SspInquiryCollectionTransfer $sspInquiryCollectionTransfer */
         $sspInquiryCollectionTransfer = $this->zedRequestClient->call('/ssp-inquiry-management/gateway/get-ssp-inquiry-collection', $sspInquiryCriteriaTransfer);

        return $sspInquiryCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SspInquiryCollectionRequestTransfer $sspInquiryCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\SspInquiryCollectionResponseTransfer
     */
    public function cancelSspInquiryCollection(SspInquiryCollectionRequestTransfer $sspInquiryCollectionRequestTransfer): SspInquiryCollectionResponseTransfer
    {
        /** @var \Generated\Shared\Transfer\SspInquiryCollectionResponseTransfer $sspInquiryCollectionResponseTransfer */
         $sspInquiryCollectionResponseTransfer = $this->zedRequestClient->call('/ssp-inquiry-management/gateway/cancel-ssp-inquiry-collection', $sspInquiryCollectionRequestTransfer);

        return $sspInquiryCollectionResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SspInquiryFileDownloadRequestTransfer $sspInquiryFileDownloadRequestTransfer
     *
     * @return \Generated\Shared\Transfer\FileManagerDataTransfer
     */
    public function downloadFile(SspInquiryFileDownloadRequestTransfer $sspInquiryFileDownloadRequestTransfer): FileManagerDataTransfer
    {
        /** @var \Generated\Shared\Transfer\FileManagerDataTransfer $fileManagerDataTransfer */
        $fileManagerDataTransfer = $this->zedRequestClient->call('/ssp-inquiry-management/gateway/download-file', $sspInquiryFileDownloadRequestTransfer);

        return $fileManagerDataTransfer;
    }
}
