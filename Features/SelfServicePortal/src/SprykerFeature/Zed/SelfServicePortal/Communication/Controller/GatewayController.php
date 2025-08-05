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
    public function getSspServiceCollectionAction(SspServiceCriteriaTransfer $sspServiceCriteriaTransfer): SspServiceCollectionTransfer
    {
        return $this->getFacade()->getSspServiceCollection($sspServiceCriteriaTransfer);
    }

    public function getFileAttachmentCollectionAction(
        FileAttachmentCriteriaTransfer $fileAttachmentCriteriaTransfer
    ): FileAttachmentCollectionTransfer {
        return $this->getFacade()->getFileAttachmentCollection($fileAttachmentCriteriaTransfer);
    }

    public function updateSalesOrderItemCollectionAction(
        SalesOrderItemCollectionRequestTransfer $salesOrderItemCollectionRequestTransfer
    ): SalesOrderItemCollectionResponseTransfer {
        return $this->getFacade()->updateSalesOrderItemCollection($salesOrderItemCollectionRequestTransfer);
    }

    public function cancelSalesOrderItemCollectionAction(
        SalesOrderItemCollectionRequestTransfer $salesOrderItemCollectionRequestTransfer
    ): SalesOrderItemCollectionResponseTransfer {
        return $this->getFacade()->cancelSalesOrderItemCollection($salesOrderItemCollectionRequestTransfer);
    }

    public function getDashboardAction(DashboardRequestTransfer $dashboardRequestTransfer): DashboardResponseTransfer
    {
        return $this->getFacade()->getDashboard($dashboardRequestTransfer);
    }

    public function createSspInquiryCollectionAction(
        SspInquiryCollectionRequestTransfer $sspInquiryCollectionRequestTransfer
    ): SspInquiryCollectionResponseTransfer {
        return $this->getFacade()->createSspInquiryCollection($sspInquiryCollectionRequestTransfer);
    }

    public function getSspInquiryCollectionAction(SspInquiryCriteriaTransfer $sspInquiryCriteriaTransfer): SspInquiryCollectionTransfer
    {
        return $this->getFacade()->getSspInquiryCollection($sspInquiryCriteriaTransfer);
    }

    public function cancelSspInquiryCollectionAction(
        SspInquiryCollectionRequestTransfer $sspInquiryCollectionRequestTransfer
    ): SspInquiryCollectionResponseTransfer {
        return $this->getFacade()->cancelSspInquiryCollection($sspInquiryCollectionRequestTransfer);
    }

    public function createSspAssetCollectionAction(
        SspAssetCollectionRequestTransfer $sspAssetCollectionRequestTransfer
    ): SspAssetCollectionResponseTransfer {
        return $this->getFacade()->createSspAssetCollection($sspAssetCollectionRequestTransfer);
    }

    public function updateSspAssetCollectionAction(
        SspAssetCollectionRequestTransfer $sspAssetCollectionRequestTransfer
    ): SspAssetCollectionResponseTransfer {
        return $this->getFacade()->updateSspAssetCollection($sspAssetCollectionRequestTransfer);
    }

    public function getSspAssetCollectionAction(
        SspAssetCriteriaTransfer $sspAssetCriteriaTransfer
    ): SspAssetCollectionTransfer {
        return $this->getFacade()->getSspAssetCollection($sspAssetCriteriaTransfer);
    }
}
