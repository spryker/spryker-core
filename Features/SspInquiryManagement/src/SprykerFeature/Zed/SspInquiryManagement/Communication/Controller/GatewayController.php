<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspInquiryManagement\Communication\Controller;

use Generated\Shared\Transfer\FileManagerDataTransfer;
use Generated\Shared\Transfer\SspInquiryCollectionRequestTransfer;
use Generated\Shared\Transfer\SspInquiryCollectionResponseTransfer;
use Generated\Shared\Transfer\SspInquiryCollectionTransfer;
use Generated\Shared\Transfer\SspInquiryCriteriaTransfer;
use Generated\Shared\Transfer\SspInquiryFileDownloadRequestTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractGatewayController;

/**
 * @method \SprykerFeature\Zed\SspInquiryManagement\Business\SspInquiryManagementFacadeInterface getFacade()
 * @method \SprykerFeature\Zed\SspInquiryManagement\Communication\SspInquiryManagementCommunicationFactory getFactory()
 * @method \SprykerFeature\Zed\SspInquiryManagement\Persistence\SspInquiryManagementRepositoryInterface getRepository()
 */
class GatewayController extends AbstractGatewayController
{
    /**
     * @param \Generated\Shared\Transfer\SspInquiryCollectionRequestTransfer $sspInquiryCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\SspInquiryCollectionResponseTransfer
     */
    public function createSspInquiryCollectionAction(
        SspInquiryCollectionRequestTransfer $sspInquiryCollectionRequestTransfer
    ): SspInquiryCollectionResponseTransfer {
        return $this->getFacade()->createSspInquiryCollection($sspInquiryCollectionRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\SspInquiryCriteriaTransfer $sspInquiryCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\SspInquiryCollectionTransfer
     */
    public function getSspInquiryCollectionAction(SspInquiryCriteriaTransfer $sspInquiryCriteriaTransfer): SspInquiryCollectionTransfer
    {
        return $this->getFacade()->getSspInquiryCollection($sspInquiryCriteriaTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\SspInquiryCollectionRequestTransfer $sspInquiryCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\SspInquiryCollectionResponseTransfer
     */
    public function cancelSspInquiryCollectionAction(
        SspInquiryCollectionRequestTransfer $sspInquiryCollectionRequestTransfer
    ): SspInquiryCollectionResponseTransfer {
        return $this->getFacade()
            ->cancelSspInquiryCollection($sspInquiryCollectionRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\SspInquiryFileDownloadRequestTransfer $sspInquiryFileDownloadRequest
     *
     * @return \Generated\Shared\Transfer\FileManagerDataTransfer
     */
    public function downloadFileAction(SspInquiryFileDownloadRequestTransfer $sspInquiryFileDownloadRequest): FileManagerDataTransfer
    {
        $fileManagerDataTransfer = $this->getFactory()->getFileManagerFacade()->findFileByIdFile($sspInquiryFileDownloadRequest->getFileIdOrFail());

        $fileManagerDataTransfer->setContent(null);

        return $fileManagerDataTransfer;
    }
}
