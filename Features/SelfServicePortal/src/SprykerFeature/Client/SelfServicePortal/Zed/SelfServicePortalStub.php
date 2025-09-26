<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Client\SelfServicePortal\Zed;

use Generated\Shared\Transfer\DashboardRequestTransfer;
use Generated\Shared\Transfer\DashboardResponseTransfer;
use Generated\Shared\Transfer\FileAttachmentCollectionTransfer;
use Generated\Shared\Transfer\FileAttachmentCriteriaTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\SalesOrderItemCollectionRequestTransfer;
use Generated\Shared\Transfer\SalesOrderItemCollectionResponseTransfer;
use Generated\Shared\Transfer\SspAssetCollectionRequestTransfer;
use Generated\Shared\Transfer\SspAssetCollectionResponseTransfer;
use Generated\Shared\Transfer\SspAssetCollectionTransfer;
use Generated\Shared\Transfer\SspAssetCriteriaTransfer;
use Generated\Shared\Transfer\SspAssetQuoteItemAttachmentRequestTransfer;
use Generated\Shared\Transfer\SspInquiryCollectionRequestTransfer;
use Generated\Shared\Transfer\SspInquiryCollectionResponseTransfer;
use Generated\Shared\Transfer\SspInquiryCollectionTransfer;
use Generated\Shared\Transfer\SspInquiryCriteriaTransfer;
use Generated\Shared\Transfer\SspServiceCollectionTransfer;
use Generated\Shared\Transfer\SspServiceCriteriaTransfer;
use Spryker\Client\ZedRequest\ZedRequestClientInterface;

class SelfServicePortalStub implements SelfServicePortalStubInterface
{
    public function __construct(protected ZedRequestClientInterface $zedRequestClient)
    {
    }

    /**
     * @uses \SprykerFeature\Zed\SelfServicePortal\Communication\Controller\GatewayController::getSspServiceCollectionAction()
     *
     * @param \Generated\Shared\Transfer\SspServiceCriteriaTransfer $sspServiceCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\SspServiceCollectionTransfer
     */
    public function getSspServiceCollection(SspServiceCriteriaTransfer $sspServiceCriteriaTransfer): SspServiceCollectionTransfer
    {
        /** @var \Generated\Shared\Transfer\SspServiceCollectionTransfer $sspServiceCollectionTransfer */
        $sspServiceCollectionTransfer = $this->zedRequestClient->call(
            '/self-service-portal/gateway/get-ssp-service-collection',
            $sspServiceCriteriaTransfer,
        );

        return $sspServiceCollectionTransfer;
    }

    /**
     * @uses \SprykerFeature\Zed\SelfServicePortal\Communication\Controller\GatewayController::updateSalesOrderItemCollectionAction()
     *
     * @param \Generated\Shared\Transfer\SalesOrderItemCollectionRequestTransfer $salesOrderItemCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderItemCollectionResponseTransfer
     */
    public function updateSalesOrderItemCollection(
        SalesOrderItemCollectionRequestTransfer $salesOrderItemCollectionRequestTransfer
    ): SalesOrderItemCollectionResponseTransfer {
        /** @var \Generated\Shared\Transfer\SalesOrderItemCollectionResponseTransfer $salesOrderItemCollectionResponseTransfer */
        $salesOrderItemCollectionResponseTransfer = $this->zedRequestClient->call(
            '/self-service-portal/gateway/update-sales-order-item-collection',
            $salesOrderItemCollectionRequestTransfer,
        );

        return $salesOrderItemCollectionResponseTransfer;
    }

    /**
     * @uses \SprykerFeature\Zed\SelfServicePortal\Communication\Controller\GatewayController::cancelSalesOrderItemCollectionAction()
     *
     * @param \Generated\Shared\Transfer\SalesOrderItemCollectionRequestTransfer $salesOrderItemCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderItemCollectionResponseTransfer
     */
    public function cancelSalesOrderItemCollection(
        SalesOrderItemCollectionRequestTransfer $salesOrderItemCollectionRequestTransfer
    ): SalesOrderItemCollectionResponseTransfer {
        /** @var \Generated\Shared\Transfer\SalesOrderItemCollectionResponseTransfer $salesOrderItemCollectionResponseTransfer */
        $salesOrderItemCollectionResponseTransfer = $this->zedRequestClient->call(
            '/self-service-portal/gateway/cancel-sales-order-item-collection',
            $salesOrderItemCollectionRequestTransfer,
        );

        return $salesOrderItemCollectionResponseTransfer;
    }

    /**
     * @uses \SprykerFeature\Zed\SelfServicePortal\Communication\Controller\GatewayController::getFileAttachmentCollectionAccordingToPermissionsAction()
     *
     * @param \Generated\Shared\Transfer\FileAttachmentCriteriaTransfer $fileAttachmentCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\FileAttachmentCollectionTransfer
     */
    public function getFileAttachmentCollection(
        FileAttachmentCriteriaTransfer $fileAttachmentCriteriaTransfer
    ): FileAttachmentCollectionTransfer {
        /** @var \Generated\Shared\Transfer\FileAttachmentCollectionTransfer $fileAttachmentCollectionTransfer */
        $fileAttachmentCollectionTransfer = $this->zedRequestClient->call(
            '/self-service-portal/gateway/get-file-attachment-collection',
            $fileAttachmentCriteriaTransfer,
        );

        return $fileAttachmentCollectionTransfer;
    }

    /**
     * @uses \SprykerFeature\Zed\SelfServicePortal\Communication\Controller\GatewayController::getDashboardAction()
     *
     * @param \Generated\Shared\Transfer\DashboardRequestTransfer $dashboardRequestTransfer
     *
     * @return \Generated\Shared\Transfer\DashboardResponseTransfer
     */
    public function getDashboard(DashboardRequestTransfer $dashboardRequestTransfer): DashboardResponseTransfer
    {
        /** @var \Generated\Shared\Transfer\DashboardResponseTransfer $dashboardResponseTransfer */
        $dashboardResponseTransfer = $this->zedRequestClient->call('/self-service-portal/gateway/get-dashboard', $dashboardRequestTransfer);

        return $dashboardResponseTransfer;
    }

    /**
     * @uses \SprykerFeature\Zed\SelfServicePortal\Communication\Controller\GatewayController::createSspInquiryCollectionAction()
     *
     * @param \Generated\Shared\Transfer\SspInquiryCollectionRequestTransfer $sspInquiryCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\SspInquiryCollectionResponseTransfer
     */
    public function createSspInquiryCollection(SspInquiryCollectionRequestTransfer $sspInquiryCollectionRequestTransfer): SspInquiryCollectionResponseTransfer
    {
        /** @var \Generated\Shared\Transfer\SspInquiryCollectionResponseTransfer $sspInquiryCollectionResponseTransfer */
         $sspInquiryCollectionResponseTransfer = $this->zedRequestClient->call('/self-service-portal/gateway/create-ssp-inquiry-collection', $sspInquiryCollectionRequestTransfer);

        return $sspInquiryCollectionResponseTransfer;
    }

    /**
     * @uses \SprykerFeature\Zed\SelfServicePortal\Communication\Controller\GatewayController::getSspInquiryCollectionAction()
     *
     * @param \Generated\Shared\Transfer\SspInquiryCriteriaTransfer $sspInquiryCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\SspInquiryCollectionTransfer
     */
    public function getSspInquiryCollection(SspInquiryCriteriaTransfer $sspInquiryCriteriaTransfer): SspInquiryCollectionTransfer
    {
        /** @var \Generated\Shared\Transfer\SspInquiryCollectionTransfer $sspInquiryCollectionTransfer */
         $sspInquiryCollectionTransfer = $this->zedRequestClient->call('/self-service-portal/gateway/get-ssp-inquiry-collection', $sspInquiryCriteriaTransfer);

        return $sspInquiryCollectionTransfer;
    }

    /**
     * @uses \SprykerFeature\Zed\SelfServicePortal\Communication\Controller\GatewayController::cancelSspInquiryCollectionAction()
     *
     * @param \Generated\Shared\Transfer\SspInquiryCollectionRequestTransfer $sspInquiryCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\SspInquiryCollectionResponseTransfer
     */
    public function cancelSspInquiryCollection(SspInquiryCollectionRequestTransfer $sspInquiryCollectionRequestTransfer): SspInquiryCollectionResponseTransfer
    {
        /** @var \Generated\Shared\Transfer\SspInquiryCollectionResponseTransfer $sspInquiryCollectionResponseTransfer */
         $sspInquiryCollectionResponseTransfer = $this->zedRequestClient->call('/self-service-portal/gateway/cancel-ssp-inquiry-collection', $sspInquiryCollectionRequestTransfer);

        return $sspInquiryCollectionResponseTransfer;
    }

    /**
     * @uses \SprykerFeature\Zed\SelfServicePortal\Communication\Controller\GatewayController::createSspAssetCollectionAction()
     *
     * @param \Generated\Shared\Transfer\SspAssetCollectionRequestTransfer $sspAssetCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\SspAssetCollectionResponseTransfer
     */
    public function createSspAssetCollection(SspAssetCollectionRequestTransfer $sspAssetCollectionRequestTransfer): SspAssetCollectionResponseTransfer
    {
        /** @var \Generated\Shared\Transfer\SspAssetCollectionResponseTransfer $sspAssetCollectionResponseTransfer */
        $sspAssetCollectionResponseTransfer = $this->zedRequestClient->call('/self-service-portal/gateway/create-ssp-asset-collection', $sspAssetCollectionRequestTransfer);

        return $sspAssetCollectionResponseTransfer;
    }

    /**
     * @uses \SprykerFeature\Zed\SelfServicePortal\Communication\Controller\GatewayController::getSspAssetCollectionAction()
     *
     * @param \Generated\Shared\Transfer\SspAssetCriteriaTransfer $sspAssetCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\SspAssetCollectionTransfer
     */
    public function getSspAssetCollection(SspAssetCriteriaTransfer $sspAssetCriteriaTransfer): SspAssetCollectionTransfer
    {
        /** @var \Generated\Shared\Transfer\SspAssetCollectionTransfer $sspAssetCollectionTransfer */
        $sspAssetCollectionTransfer = $this->zedRequestClient->call('/self-service-portal/gateway/get-ssp-asset-collection', $sspAssetCriteriaTransfer);

        return $sspAssetCollectionTransfer;
    }

    /**
     * @uses \SprykerFeature\Zed\SelfServicePortal\Communication\Controller\GatewayController::updateSspAssetCollectionAction()
     *
     * @param \Generated\Shared\Transfer\SspAssetCollectionRequestTransfer $sspAssetCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\SspAssetCollectionResponseTransfer
     */
    public function updateSspAssetCollection(SspAssetCollectionRequestTransfer $sspAssetCollectionRequestTransfer): SspAssetCollectionResponseTransfer
    {
        /** @var \Generated\Shared\Transfer\SspAssetCollectionResponseTransfer $sspAssetCollectionResponseTransfer */
        $sspAssetCollectionResponseTransfer = $this->zedRequestClient->call('/self-service-portal/gateway/update-ssp-asset-collection', $sspAssetCollectionRequestTransfer);

        return $sspAssetCollectionResponseTransfer;
    }

    /**
     * @uses \SprykerFeature\Zed\SelfServicePortal\Communication\Controller\GatewayController::attachSspAssetToQuoteItemAction()
     *
     * @param \Generated\Shared\Transfer\SspAssetQuoteItemAttachmentRequestTransfer $sspAssetQuoteItemAttachmentRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function attachSspAssetToQuoteItem(SspAssetQuoteItemAttachmentRequestTransfer $sspAssetQuoteItemAttachmentRequestTransfer): QuoteResponseTransfer
    {
        /** @var \Generated\Shared\Transfer\QuoteResponseTransfer $quoteResponseTransfer */
        $quoteResponseTransfer = $this->zedRequestClient->call(
            '/self-service-portal/gateway/attach-ssp-asset-to-quote-item',
            $sspAssetQuoteItemAttachmentRequestTransfer,
        );

        return $quoteResponseTransfer;
    }
}
