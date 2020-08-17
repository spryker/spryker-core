<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesMerchantPortalGui\Communication\DataProvider;

use Generated\Shared\Transfer\GuiTableDataRequestTransfer;
use Generated\Shared\Transfer\GuiTableDataResponsePayloadTransfer;
use Generated\Shared\Transfer\GuiTableDataResponseTransfer;
use Generated\Shared\Transfer\GuiTableRowDataResponseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\MerchantOrderItemTableCriteriaTransfer;
use Generated\Shared\Transfer\MerchantOrderItemTransfer;
use Generated\Shared\Transfer\OrderItemFilterTransfer;
use Spryker\Shared\GuiTable\DataProvider\AbstractGuiTableDataProvider;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Zed\SalesMerchantPortalGui\Communication\ConfigurationProvider\MerchantOrderItemGuiTableConfigurationProvider;
use Spryker\Zed\SalesMerchantPortalGui\Dependency\Facade\SalesMerchantPortalGuiToMerchantOmsFacadeInterface;
use Spryker\Zed\SalesMerchantPortalGui\Dependency\Facade\SalesMerchantPortalGuiToMerchantUserFacadeInterface;
use Spryker\Zed\SalesMerchantPortalGui\Dependency\Facade\SalesMerchantPortalGuiToSalesFacadeInterface;
use Spryker\Zed\SalesMerchantPortalGui\Persistence\SalesMerchantPortalGuiRepositoryInterface;

class MerchantOrderItemGuiTableDataProvider extends AbstractGuiTableDataProvider
{
    /**
     * @var \Spryker\Zed\SalesMerchantPortalGui\Persistence\SalesMerchantPortalGuiRepositoryInterface
     */
    protected $salesMerchantPortalGuiRepository;

    /**
     * @var \Spryker\Zed\SalesMerchantPortalGui\Dependency\Facade\SalesMerchantPortalGuiToMerchantUserFacadeInterface
     */
    protected $merchantUserFacade;

    /**
     * @var \Spryker\Zed\SalesMerchantPortalGui\Dependency\Facade\SalesMerchantPortalGuiToMerchantOmsFacadeInterface
     */
    protected $merchantOmsFacade;

    /**
     * @var \Spryker\Zed\SalesMerchantPortalGui\Dependency\Facade\SalesMerchantPortalGuiToSalesFacadeInterface
     */
    protected $salesFacade;

    /**
     * @var int[]
     */
    protected $merchantOrderItemIds;

    /**
     * @var array|\Spryker\Zed\SalesMerchantPortalGuiExtension\Dependency\Plugin\MerchantOrderItemTableExpanderPluginInterface[]
     */
    protected $merchantOrderItemTableExpanderPlugins;

    /**
     * @param \Spryker\Zed\SalesMerchantPortalGui\Persistence\SalesMerchantPortalGuiRepositoryInterface $salesMerchantPortalGuiRepository
     * @param \Spryker\Zed\SalesMerchantPortalGui\Dependency\Facade\SalesMerchantPortalGuiToMerchantUserFacadeInterface $merchantUserFacade
     * @param \Spryker\Zed\SalesMerchantPortalGui\Dependency\Facade\SalesMerchantPortalGuiToMerchantOmsFacadeInterface $merchantOmsFacade
     * @param \Spryker\Zed\SalesMerchantPortalGui\Dependency\Facade\SalesMerchantPortalGuiToSalesFacadeInterface $salesFacade
     * @param int[] $merchantOrderItemIds
     * @param \Spryker\Zed\SalesMerchantPortalGuiExtension\Dependency\Plugin\MerchantOrderItemTableExpanderPluginInterface[] $merchantOrderItemTableExpanderPlugins
     */
    public function __construct(
        SalesMerchantPortalGuiRepositoryInterface $salesMerchantPortalGuiRepository,
        SalesMerchantPortalGuiToMerchantUserFacadeInterface $merchantUserFacade,
        SalesMerchantPortalGuiToMerchantOmsFacadeInterface $merchantOmsFacade,
        SalesMerchantPortalGuiToSalesFacadeInterface $salesFacade,
        array $merchantOrderItemIds,
        array $merchantOrderItemTableExpanderPlugins = []
    ) {
        $this->salesMerchantPortalGuiRepository = $salesMerchantPortalGuiRepository;
        $this->merchantUserFacade = $merchantUserFacade;
        $this->merchantOmsFacade = $merchantOmsFacade;
        $this->salesFacade = $salesFacade;
        $this->merchantOrderItemIds = $merchantOrderItemIds;
        $this->merchantOrderItemTableExpanderPlugins = $merchantOrderItemTableExpanderPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\GuiTableDataRequestTransfer $guiTableDataRequestTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    protected function createCriteria(GuiTableDataRequestTransfer $guiTableDataRequestTransfer): AbstractTransfer
    {
        return (new MerchantOrderItemTableCriteriaTransfer())
            ->setIdMerchant($this->merchantUserFacade->getCurrentMerchantUser()->getIdMerchant())
            ->setMerchantOrderItemIds($this->merchantOrderItemIds);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantOrderItemTableCriteriaTransfer $criteriaTransfer
     *
     * @return \Generated\Shared\Transfer\GuiTableDataResponseTransfer
     */
    protected function fetchData(AbstractTransfer $criteriaTransfer): GuiTableDataResponseTransfer
    {
        $merchantOrderItemCollectionTransfer = $this->salesMerchantPortalGuiRepository
            ->getMerchantOrderItemTableData($criteriaTransfer);
        $merchantOrderItemCollectionTransfer = $this->merchantOmsFacade->expandMerchantOrderItemsWithManualEvents(
            $merchantOrderItemCollectionTransfer
        );

        $guiTableDataResponseTransfer = new GuiTableDataResponseTransfer();
        $salesOrderItemIds = [];

        foreach ($merchantOrderItemCollectionTransfer->getMerchantOrderItems() as $merchantOrderItemTransfer) {
            $itemTransfer = $merchantOrderItemTransfer->getOrderItem();

            $responseData = [
                ItemTransfer::ID_SALES_ORDER_ITEM => $itemTransfer->getIdSalesOrderItem(),
                MerchantOrderItemTransfer::ID_MERCHANT_ORDER_ITEM => $merchantOrderItemTransfer->getIdMerchantOrderItem(),
                MerchantOrderItemTransfer::ID_MERCHANT_ORDER => $merchantOrderItemTransfer->getIdMerchantOrder(),
                MerchantOrderItemGuiTableConfigurationProvider::COL_KEY_SKU => $itemTransfer->getSku(),
                MerchantOrderItemGuiTableConfigurationProvider::COL_KEY_IMAGE => $this->getImageUrl($itemTransfer),
                MerchantOrderItemGuiTableConfigurationProvider::COL_KEY_NAME => $itemTransfer->getName(),
                MerchantOrderItemGuiTableConfigurationProvider::COL_KEY_QUANTITY => $itemTransfer->getQuantity(),
                MerchantOrderItemGuiTableConfigurationProvider::COL_KEY_STATE => $merchantOrderItemTransfer->getState(),
                MerchantOrderItemGuiTableConfigurationProvider::COL_KEY_ACTION_IDS => $merchantOrderItemTransfer->getManualEvents(),
            ];

            $guiTableDataResponseTransfer->addRow((new GuiTableRowDataResponseTransfer())->setResponseData($responseData));
            $salesOrderItemIds[] = $itemTransfer->getIdSalesOrderItem();
        }

        $paginationTransfer = $merchantOrderItemCollectionTransfer->getPagination();

        $guiTableDataResponseTransfer = $guiTableDataResponseTransfer
            ->setPage($paginationTransfer->getPage())
            ->setPageSize($paginationTransfer->getMaxPerPage())
            ->setTotal($paginationTransfer->getNbResults());
        $guiTableDataResponseTransfer = $this->expandDataResponse($guiTableDataResponseTransfer, $salesOrderItemIds);

        return $guiTableDataResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return string|null
     */
    protected function getImageUrl(ItemTransfer $itemTransfer): ?string
    {
        return isset($itemTransfer->getImages()[0]) ? $itemTransfer->getImages()[0]->getExternalUrlSmall() : null;
    }

    /**
     * @param \Generated\Shared\Transfer\GuiTableDataResponseTransfer $guiTableDataResponseTransfer
     * @param int[] $salesOrderItemIds
     *
     * @return \Generated\Shared\Transfer\GuiTableDataResponseTransfer
     */
    protected function expandDataResponse(
        GuiTableDataResponseTransfer $guiTableDataResponseTransfer,
        array $salesOrderItemIds
    ): GuiTableDataResponseTransfer {
        $guiTableDataResponseTransfer = $this->addOrderItemsToPayload($guiTableDataResponseTransfer, $salesOrderItemIds);

        foreach ($this->merchantOrderItemTableExpanderPlugins as $merchantOrderItemTableExpanderPlugin) {
            $guiTableDataResponseTransfer = $merchantOrderItemTableExpanderPlugin->expandDataResponse($guiTableDataResponseTransfer);
        }

        return $guiTableDataResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\GuiTableDataResponseTransfer $guiTableDataResponseTransfer
     * @param int[] $salesOrderItemIds
     *
     * @return \Generated\Shared\Transfer\GuiTableDataResponseTransfer
     */
    protected function addOrderItemsToPayload(
        GuiTableDataResponseTransfer $guiTableDataResponseTransfer,
        array $salesOrderItemIds
    ): GuiTableDataResponseTransfer {
        $itemCollectionTransfer = $this->salesFacade->getOrderItems(
            (new OrderItemFilterTransfer())->setSalesOrderItemIds($salesOrderItemIds)
        );

        $indexedItemTransfers = [];
        foreach ($itemCollectionTransfer->getItems() as $itemTransfer) {
            $indexedItemTransfers[$itemTransfer->getIdSalesOrderItem()] = $itemTransfer;
        }

        foreach ($guiTableDataResponseTransfer->getRows() as $guiTableRowDataResponseTransfer) {
            $itemTransfer = $indexedItemTransfers[$guiTableRowDataResponseTransfer->getResponseData()[ItemTransfer::ID_SALES_ORDER_ITEM]];
            $guiTableRowDataResponseTransfer->setPayload((new GuiTableDataResponsePayloadTransfer())->setItem($itemTransfer));
        }

        return $guiTableDataResponseTransfer;
    }
}
