<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Client\SspInquiryManagement;

use Generated\Shared\Transfer\FileManagerDataTransfer;
use Generated\Shared\Transfer\SspInquiryCollectionRequestTransfer;
use Generated\Shared\Transfer\SspInquiryCollectionResponseTransfer;
use Generated\Shared\Transfer\SspInquiryCollectionTransfer;
use Generated\Shared\Transfer\SspInquiryCriteriaTransfer;
use Generated\Shared\Transfer\SspInquiryFileDownloadRequestTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \SprykerFeature\Client\SspInquiryManagement\SspInquiryManagementFactory getFactory()
 */
class SspInquiryManagementClient extends AbstractClient implements SspInquiryManagementClientInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SspInquiryCollectionRequestTransfer $sspInquiryCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\SspInquiryCollectionResponseTransfer
     */
    public function createSspInquiryCollection(SspInquiryCollectionRequestTransfer $sspInquiryCollectionRequestTransfer): SspInquiryCollectionResponseTransfer
    {
        return $this->getFactory()->createSspInquiryManagementStub()->createSspInquiryCollection($sspInquiryCollectionRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SspInquiryCriteriaTransfer $sspInquiryCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\SspInquiryCollectionTransfer
     */
    public function getSspInquiryCollection(SspInquiryCriteriaTransfer $sspInquiryCriteriaTransfer): SspInquiryCollectionTransfer
    {
        return $this->getFactory()->createSspInquiryManagementStub()->getSspInquiryCollection($sspInquiryCriteriaTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SspInquiryCollectionRequestTransfer $sspInquiryCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\SspInquiryCollectionResponseTransfer
     */
    public function cancelSspInquiryCollection(SspInquiryCollectionRequestTransfer $sspInquiryCollectionRequestTransfer): SspInquiryCollectionResponseTransfer
    {
        return $this->getFactory()->createSspInquiryManagementStub()->cancelSspInquiryCollection($sspInquiryCollectionRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SspInquiryFileDownloadRequestTransfer $sspInquiryFileDownloadRequestTransfer
     *
     * @return \Generated\Shared\Transfer\FileManagerDataTransfer
     */
    public function downloadSspInquiryFile(SspInquiryFileDownloadRequestTransfer $sspInquiryFileDownloadRequestTransfer): FileManagerDataTransfer
    {
        return $this->getFactory()->createSspInquiryManagementStub()->downloadFile($sspInquiryFileDownloadRequestTransfer);
    }
}
