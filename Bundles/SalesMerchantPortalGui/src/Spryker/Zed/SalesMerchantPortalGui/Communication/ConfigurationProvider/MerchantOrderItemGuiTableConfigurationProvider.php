<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesMerchantPortalGui\Communication\ConfigurationProvider;

use ArrayObject;
use Generated\Shared\Transfer\GuiTableBatchActionsConfigurationTransfer;
use Generated\Shared\Transfer\GuiTableBatchActionTransfer;
use Generated\Shared\Transfer\GuiTableConfigurationTransfer;
use Generated\Shared\Transfer\GuiTableDataSourceConfigurationTransfer;
use Generated\Shared\Transfer\GuiTableFiltersConfigurationTransfer;
use Generated\Shared\Transfer\GuiTableItemSelectionConfigurationTransfer;
use Generated\Shared\Transfer\GuiTablePaginationConfigurationTransfer;
use Generated\Shared\Transfer\GuiTableRowActionsConfigurationTransfer;
use Generated\Shared\Transfer\GuiTableRowActionTransfer;
use Generated\Shared\Transfer\MerchantCriteriaTransfer;
use Generated\Shared\Transfer\MerchantOrderItemTransfer;
use Generated\Shared\Transfer\MerchantOrderTransfer;
use Spryker\Zed\GuiTable\Communication\ConfigurationProvider\AbstractGuiTableConfigurationProvider;
use Spryker\Zed\SalesMerchantPortalGui\Dependency\Facade\SalesMerchantPortalGuiToMerchantOmsFacadeInterface;
use Spryker\Zed\SalesMerchantPortalGui\Dependency\Facade\SalesMerchantPortalGuiToMerchantUserFacadeInterface;

class MerchantOrderItemGuiTableConfigurationProvider extends AbstractGuiTableConfigurationProvider implements MerchantOrderItemGuiTableConfigurationProviderInterface
{
    public const COL_KEY_SKU = 'sku';
    public const COL_KEY_IMAGE = 'image';
    public const COL_KEY_NAME = 'name';
    public const COL_KEY_QUANTITY = 'quantity';
    public const COL_KEY_STATE = 'state';
    public const COL_KEY_ACTION_IDS = 'actionIds';

    /**
     * @uses \Spryker\Zed\SalesMerchantPortalGui\Communication\Controller\ItemListController::tableDataAction()
     */
    protected const DATA_URL = '/sales-merchant-portal-gui/item-list/table-data';

    /**
     * @var \Spryker\Zed\SalesMerchantPortalGui\Dependency\Facade\SalesMerchantPortalGuiToMerchantOmsFacadeInterface
     */
    protected $merchantOmsFacade;

    /**
     * @var \Spryker\Zed\SalesMerchantPortalGui\Dependency\Facade\SalesMerchantPortalGuiToMerchantUserFacadeInterface
     */
    protected $merchantUserFacade;

    /**
     * @var array|\Spryker\Zed\SalesMerchantPortalGuiExtension\Dependency\Plugin\MerchantOrderItemTableExpanderPluginInterface[]
     */
    protected $merchantOrderItemTableExpanderPlugins;

    /**
     * @param \Spryker\Zed\SalesMerchantPortalGui\Dependency\Facade\SalesMerchantPortalGuiToMerchantOmsFacadeInterface $merchantOmsFacade
     * @param \Spryker\Zed\SalesMerchantPortalGui\Dependency\Facade\SalesMerchantPortalGuiToMerchantUserFacadeInterface $merchantUserFacade
     * @param \Spryker\Zed\SalesMerchantPortalGuiExtension\Dependency\Plugin\MerchantOrderItemTableExpanderPluginInterface[] $merchantOrderItemTableExpanderPlugins
     */
    public function __construct(
        SalesMerchantPortalGuiToMerchantOmsFacadeInterface $merchantOmsFacade,
        SalesMerchantPortalGuiToMerchantUserFacadeInterface $merchantUserFacade,
        array $merchantOrderItemTableExpanderPlugins = []
    ) {
        $this->merchantOmsFacade = $merchantOmsFacade;
        $this->merchantUserFacade = $merchantUserFacade;
        $this->merchantOrderItemTableExpanderPlugins = $merchantOrderItemTableExpanderPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantOrderTransfer $merchantOrderTransfer
     *
     * @return \Generated\Shared\Transfer\GuiTableConfigurationTransfer
     */
    public function getConfiguration(MerchantOrderTransfer $merchantOrderTransfer): GuiTableConfigurationTransfer
    {
        $guiTableConfigurationTransfer = new GuiTableConfigurationTransfer();
        $guiTableConfigurationTransfer = $this->addColumnsToConfiguration($guiTableConfigurationTransfer);
        $guiTableConfigurationTransfer = $this->addPaginationToConfiguration($guiTableConfigurationTransfer);
        $guiTableConfigurationTransfer = $this->addFiltersToConfiguration($guiTableConfigurationTransfer);
        $guiTableConfigurationTransfer = $this->addRowActionsToConfiguration($guiTableConfigurationTransfer, $merchantOrderTransfer);
        $guiTableConfigurationTransfer = $this->addBatchActionsToConfiguration($guiTableConfigurationTransfer, $merchantOrderTransfer);
        $guiTableConfigurationTransfer->setDataSource(
            (new GuiTableDataSourceConfigurationTransfer())->setUrl(static::DATA_URL)
        );
        $guiTableConfigurationTransfer->setItemSelection(
            (new GuiTableItemSelectionConfigurationTransfer())->setIsEnabled(true)
        );

        foreach ($this->merchantOrderItemTableExpanderPlugins as $merchantOrderItemTableExpanderPlugin) {
            $guiTableConfigurationTransfer = $merchantOrderItemTableExpanderPlugin->expandConfiguration($guiTableConfigurationTransfer);
        }

        return $guiTableConfigurationTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\GuiTableConfigurationTransfer $guiTableConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\GuiTableConfigurationTransfer
     */
    protected function addColumnsToConfiguration(GuiTableConfigurationTransfer $guiTableConfigurationTransfer): GuiTableConfigurationTransfer
    {
        $columns = new ArrayObject([
            $this->createColumnText(static::COL_KEY_SKU, 'SKU', true, false),
            $this->createColumnImage(static::COL_KEY_IMAGE, 'Image', false, false),
            $this->createColumnText(static::COL_KEY_NAME, 'Name', true, false),
            $this->createColumnText(static::COL_KEY_QUANTITY, 'Quantity', false, false),
            $this->createColumnChip(static::COL_KEY_STATE, 'State', true, false, [
                'color' => 'green',
            ]),
        ]);

        $guiTableConfigurationTransfer->setColumns($columns);

        return $guiTableConfigurationTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\GuiTableConfigurationTransfer $guiTableConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\GuiTableConfigurationTransfer
     */
    protected function addPaginationToConfiguration(GuiTableConfigurationTransfer $guiTableConfigurationTransfer): GuiTableConfigurationTransfer
    {
        $guiTableConfigurationTransfer->setPagination(
            (new GuiTablePaginationConfigurationTransfer())->setDefaultSize(10)
        );

        return $guiTableConfigurationTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\GuiTableConfigurationTransfer $guiTableConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\GuiTableConfigurationTransfer
     */
    protected function addFiltersToConfiguration(GuiTableConfigurationTransfer $guiTableConfigurationTransfer): GuiTableConfigurationTransfer
    {
        $filters = new ArrayObject([
            $this->createFilterSelect('orderItemStates', 'States', true, $this->getStatesOptions()),
        ]);
        $guiTableConfigurationTransfer->setFilters(
            (new GuiTableFiltersConfigurationTransfer())->setItems($filters)
        );

        return $guiTableConfigurationTransfer;
    }

    /**
     * @return string[]
     */
    protected function getStatesOptions(): array
    {
        $idMerchant = $this->merchantUserFacade->getCurrentMerchantUser()->getIdMerchant();
        $merchantCriteriaTransfer = (new MerchantCriteriaTransfer())->setIdMerchant($idMerchant);
        $stateMachineProcessTransfer = $this->merchantOmsFacade->getMerchantOmsProcessByMerchant($merchantCriteriaTransfer);

        return $stateMachineProcessTransfer->getStateNames();
    }

    /**
     * @param \Generated\Shared\Transfer\GuiTableConfigurationTransfer $guiTableConfigurationTransfer
     * @param \Generated\Shared\Transfer\MerchantOrderTransfer $merchantOrderTransfer
     *
     * @return \Generated\Shared\Transfer\GuiTableConfigurationTransfer
     */
    protected function addRowActionsToConfiguration(
        GuiTableConfigurationTransfer $guiTableConfigurationTransfer,
        MerchantOrderTransfer $merchantOrderTransfer
    ): GuiTableConfigurationTransfer {
        $guiTableRowActionTransfers = new ArrayObject();
        foreach ($merchantOrderTransfer->getManualEvents() as $manualEvent) {
            $guiTableRowActionTransfer = (new GuiTableRowActionTransfer())
                ->setId($manualEvent)
                ->setTitle($manualEvent)
                ->setType('url')
                ->addTypeOption(
                    'url',
                    sprintf(
                        '/sales-merchant-portal-gui/trigger-merchant-oms/batch/?merchant-order-id=%d&event-name=%s&merchant-order-ids=[${rowId}]',
                        $merchantOrderTransfer->getIdMerchantOrder(),
                        $manualEvent
                    )
                );

            $guiTableRowActionTransfers->append($guiTableRowActionTransfer);
        }

        $guiTableConfigurationTransfer->setRowActions(
            (new GuiTableRowActionsConfigurationTransfer())
                ->setRowIdPath(MerchantOrderItemTransfer::ID_MERCHANT_ORDER_ITEM)
                ->setActions($guiTableRowActionTransfers)
                ->setAvailableActionsPath(static::COL_KEY_ACTION_IDS)
        );

        return $guiTableConfigurationTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\GuiTableConfigurationTransfer $guiTableConfigurationTransfer
     * @param \Generated\Shared\Transfer\MerchantOrderTransfer $merchantOrderTransfer
     *
     * @return \Generated\Shared\Transfer\GuiTableConfigurationTransfer
     */
    protected function addBatchActionsToConfiguration(
        GuiTableConfigurationTransfer $guiTableConfigurationTransfer,
        MerchantOrderTransfer $merchantOrderTransfer
    ): GuiTableConfigurationTransfer {
        $guiTableBatchActionTransfers = new ArrayObject();
        foreach ($merchantOrderTransfer->getManualEvents() as $manualEvent) {
            $guiTableBatchActionTransfer = (new GuiTableBatchActionTransfer())
                ->setId($manualEvent)
                ->setTitle($manualEvent)
                ->setType('url')
                ->addTypeOption(
                    'url',
                    sprintf(
                        '/sales-merchant-portal-gui/trigger-merchant-oms/batch/?merchant-order-id=%d&event-name=%s&merchant-order-ids=${rowIds}',
                        $merchantOrderTransfer->getIdMerchantOrder(),
                        $manualEvent
                    )
                );

            $guiTableBatchActionTransfers->append($guiTableBatchActionTransfer);
        }

        $guiTableConfigurationTransfer->setBatchActions(
            (new GuiTableBatchActionsConfigurationTransfer())
                ->setIsEnabled(true)
                ->setActions($guiTableBatchActionTransfers)
                ->setRowIdPath(MerchantOrderItemTransfer::ID_MERCHANT_ORDER_ITEM)
                ->setAvailableActionsPath(static::COL_KEY_ACTION_IDS)
                ->setNoActionsMessage('There are no applicable actions for the selected items.')
        );

        return $guiTableConfigurationTransfer;
    }
}
