<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\Controller;

use Generated\Shared\Transfer\DashboardRequestTransfer;
use Generated\Shared\Transfer\DashboardResponseTransfer;
use Generated\Shared\Transfer\FileAttachmentCollectionTransfer;
use Generated\Shared\Transfer\FileAttachmentCriteriaTransfer;
use Generated\Shared\Transfer\FileManagerDataTransfer;
use Generated\Shared\Transfer\SalesOrderItemCollectionRequestTransfer;
use Generated\Shared\Transfer\SalesOrderItemCollectionResponseTransfer;
use Generated\Shared\Transfer\SspAssetCollectionRequestTransfer;
use Generated\Shared\Transfer\SspAssetCollectionResponseTransfer;
use Generated\Shared\Transfer\SspAssetCollectionTransfer;
use Generated\Shared\Transfer\SspAssetCriteriaTransfer;
use Generated\Shared\Transfer\SspInquiryCollectionRequestTransfer;
use Generated\Shared\Transfer\SspInquiryCollectionResponseTransfer;
use Generated\Shared\Transfer\SspInquiryCollectionTransfer;
use Generated\Shared\Transfer\SspInquiryCriteriaTransfer;
use Generated\Shared\Transfer\SspInquiryFileDownloadRequestTransfer;
use Generated\Shared\Transfer\SspServiceCollectionTransfer;
use Generated\Shared\Transfer\SspServiceCriteriaTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractGatewayController;

/**
 * @method \SprykerFeature\Zed\SelfServicePortal\Business\SelfServicePortalFacadeInterface getFacade()
 * @method \SprykerFeature\Zed\SelfServicePortal\Communication\SelfServicePortalCommunicationFactory getFactory()
 * @method \SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalRepositoryInterface getRepository()
 */
class GatewayController extends AbstractGatewayController
{
    /**
     * @param \Generated\Shared\Transfer\SspServiceCriteriaTransfer $sspServiceCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\SspServiceCollectionTransfer
     */
    public function getServiceCollectionAction(SspServiceCriteriaTransfer $sspServiceCriteriaTransfer): SspServiceCollectionTransfer
    {
        return $this->getFacade()->getServiceCollection($sspServiceCriteriaTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\FileAttachmentCriteriaTransfer $fileAttachmentCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\FileAttachmentCollectionTransfer
     */
    public function getFileAttachmentCollectionAction(
        FileAttachmentCriteriaTransfer $fileAttachmentCriteriaTransfer
    ): FileAttachmentCollectionTransfer {
        return $this->getFacade()->getFileAttachmentCollection($fileAttachmentCriteriaTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\SalesOrderItemCollectionRequestTransfer $salesOrderItemCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderItemCollectionResponseTransfer
     */
    public function updateSalesOrderItemCollectionAction(
        SalesOrderItemCollectionRequestTransfer $salesOrderItemCollectionRequestTransfer
    ): SalesOrderItemCollectionResponseTransfer {
        return $this->getFacade()->updateSalesOrderItemCollection($salesOrderItemCollectionRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\SalesOrderItemCollectionRequestTransfer $salesOrderItemCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderItemCollectionResponseTransfer
     */
    public function cancelSalesOrderItemCollectionAction(
        SalesOrderItemCollectionRequestTransfer $salesOrderItemCollectionRequestTransfer
    ): SalesOrderItemCollectionResponseTransfer {
        return $this->getFacade()->cancelSalesOrderItemCollection($salesOrderItemCollectionRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\DashboardRequestTransfer $dashboardRequestTransfer
     *
     * @return \Generated\Shared\Transfer\DashboardResponseTransfer
     */
    public function getDashboardAction(DashboardRequestTransfer $dashboardRequestTransfer): DashboardResponseTransfer
    {
        return $this->getFacade()->getDashboard($dashboardRequestTransfer);
    }

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
        return $this->getFacade()->cancelSspInquiryCollection($sspInquiryCollectionRequestTransfer);
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

    /**
     * @param \Generated\Shared\Transfer\SspAssetCollectionRequestTransfer $sspAssetCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\SspAssetCollectionResponseTransfer
     */
    public function createSspAssetCollectionAction(
        SspAssetCollectionRequestTransfer $sspAssetCollectionRequestTransfer
    ): SspAssetCollectionResponseTransfer {
        return $this->getFacade()->createSspAssetCollection($sspAssetCollectionRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\SspAssetCollectionRequestTransfer $sspAssetCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\SspAssetCollectionResponseTransfer
     */
    public function updateSspAssetCollectionAction(
        SspAssetCollectionRequestTransfer $sspAssetCollectionRequestTransfer
    ): SspAssetCollectionResponseTransfer {
        return $this->getFacade()->updateSspAssetCollection($sspAssetCollectionRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\SspAssetCriteriaTransfer $sspAssetCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\SspAssetCollectionTransfer
     */
    public function getSspAssetCollectionAction(
        SspAssetCriteriaTransfer $sspAssetCriteriaTransfer
    ): SspAssetCollectionTransfer {
        return $this->getFacade()->getSspAssetCollection($sspAssetCriteriaTransfer);
    }
}
