<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityMerchantPortalGui\Communication\Expander;

use Generated\Shared\Transfer\GuiTableColumnConfigurationTransfer;
use Generated\Shared\Transfer\GuiTableConfigurationTransfer;
use Generated\Shared\Transfer\GuiTableDataResponseTransfer;
use Generated\Shared\Transfer\GuiTableRowDataResponseTransfer;
use Generated\Shared\Transfer\MerchantStockCriteriaTransfer;
use Generated\Shared\Transfer\ProductAvailabilityCriteriaTransfer;
use Generated\Shared\Transfer\ProductConcreteAvailabilityCollectionTransfer;
use Spryker\Zed\AvailabilityMerchantPortalGui\Dependency\Facade\AvailabilityMerchantPortalGuiToAvailabilityFacadeInterface;
use Spryker\Zed\AvailabilityMerchantPortalGui\Dependency\Facade\AvailabilityMerchantPortalGuiToMerchantStockFacadeInterface;
use Spryker\Zed\AvailabilityMerchantPortalGui\Dependency\Facade\AvailabilityMerchantPortalGuiToMerchantUserFacadeInterface;

class ProductConcreteTableExpander implements ProductConcreteTableExpanderInterface
{
    protected const COL_KEY_AVAILABLE_STOCK = 'availableStock';

    /**
     * @uses \Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\ProductGuiTableConfigurationProvider::COL_KEY_SKU
     */
    protected const COL_KEY_SKU = 'sku';

    /**
     * @uses \Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface::COLUMN_TYPE_TEXT
     */
    protected const COLUMN_TYPE_TEXT = 'text';

    /**
     * @var \Spryker\Zed\AvailabilityMerchantPortalGui\Dependency\Facade\AvailabilityMerchantPortalGuiToAvailabilityFacadeInterface
     */
    protected $availabilityFacade;

    /**
     * @var \Spryker\Zed\AvailabilityMerchantPortalGui\Dependency\Facade\AvailabilityMerchantPortalGuiToMerchantStockFacadeInterface
     */
    protected $merchantStockFacade;

    /**
     * @var \Spryker\Zed\AvailabilityMerchantPortalGui\Dependency\Facade\AvailabilityMerchantPortalGuiToMerchantUserFacadeInterface
     */
    protected $merchantUserFacade;

    /**
     * @param \Spryker\Zed\AvailabilityMerchantPortalGui\Dependency\Facade\AvailabilityMerchantPortalGuiToAvailabilityFacadeInterface $availabilityFacade
     * @param \Spryker\Zed\AvailabilityMerchantPortalGui\Dependency\Facade\AvailabilityMerchantPortalGuiToMerchantStockFacadeInterface $merchantStockFacade
     * @param \Spryker\Zed\AvailabilityMerchantPortalGui\Dependency\Facade\AvailabilityMerchantPortalGuiToMerchantUserFacadeInterface $merchantUserFacade
     */
    public function __construct(
        AvailabilityMerchantPortalGuiToAvailabilityFacadeInterface $availabilityFacade,
        AvailabilityMerchantPortalGuiToMerchantStockFacadeInterface $merchantStockFacade,
        AvailabilityMerchantPortalGuiToMerchantUserFacadeInterface $merchantUserFacade
    ) {
        $this->availabilityFacade = $availabilityFacade;
        $this->merchantStockFacade = $merchantStockFacade;
        $this->merchantUserFacade = $merchantUserFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\GuiTableConfigurationTransfer $guiTableConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\GuiTableConfigurationTransfer
     */
    public function expandConfiguration(
        GuiTableConfigurationTransfer $guiTableConfigurationTransfer
    ): GuiTableConfigurationTransfer {
        $guiTableColumnConfigurationTransfer = (new GuiTableColumnConfigurationTransfer())
            ->setId(static::COL_KEY_AVAILABLE_STOCK)
            ->setTitle('Available Stock')
            ->setType(static::COLUMN_TYPE_TEXT)
            ->setSortable(false)
            ->setHideable(true);

        $guiTableConfigurationTransfer->addColumn($guiTableColumnConfigurationTransfer);

        return $guiTableConfigurationTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\GuiTableDataResponseTransfer $guiTableDataResponseTransfer
     *
     * @return \Generated\Shared\Transfer\GuiTableDataResponseTransfer
     */
    public function expandDataResponse(
        GuiTableDataResponseTransfer $guiTableDataResponseTransfer
    ): GuiTableDataResponseTransfer {
        $productConcreteSkus = array_map(function (GuiTableRowDataResponseTransfer $guiTableRowDataResponseTransfer): string {
            return $guiTableRowDataResponseTransfer->getResponseData()[static::COL_KEY_SKU];
        }, $guiTableDataResponseTransfer->getRows()->getArrayCopy());
        $idMerchant = $this->merchantUserFacade->getCurrentMerchantUser()->getIdMerchant();
        $stockTransfers = $this->merchantStockFacade->get(
            ((new MerchantStockCriteriaTransfer())->setIdMerchant($idMerchant)->setIsDefault(true))
        )->getStocks();

        if (!$stockTransfers->count()) {
            return $guiTableDataResponseTransfer;
        }

        $productConcreteAvailabilityCollectionTransfer = $this->availabilityFacade->getProductConcreteAvailabilityCollection(
            (new ProductAvailabilityCriteriaTransfer())
                ->setProductConcreteSkus($productConcreteSkus)
                ->setStoreIds($stockTransfers->offsetGet(0)->getStoreRelationOrFail()->getIdStores())
        );
        $productAvailabilities = $this->getProductAvailabilitiesGroupedByProductSkus(
            $productConcreteAvailabilityCollectionTransfer
        );

        foreach ($guiTableDataResponseTransfer->getRows() as $guiTableRowDataResponseTransfer) {
            $responseData = $guiTableRowDataResponseTransfer->getResponseData();
            $sku = $guiTableRowDataResponseTransfer->getResponseData()[static::COL_KEY_SKU];

            $responseData[static::COL_KEY_AVAILABLE_STOCK] = $productAvailabilities[$sku][0] ?? null;

            $guiTableRowDataResponseTransfer->setResponseData($responseData);
        }

        return $guiTableDataResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteAvailabilityCollectionTransfer $productConcreteAvailabilityCollectionTransfer
     *
     * @return float[][]
     */
    protected function getProductAvailabilitiesGroupedByProductSkus(
        ProductConcreteAvailabilityCollectionTransfer $productConcreteAvailabilityCollectionTransfer
    ): array {
        $productAvailabilities = [];
        $productConcreteAvailabilityTransfers = $productConcreteAvailabilityCollectionTransfer->getProductConcreteAvailabilities();
        foreach ($productConcreteAvailabilityTransfers as $productConcreteAvailabilityTransfer) {
            $sku = $productConcreteAvailabilityTransfer->getSku();

            if ($productConcreteAvailabilityTransfer->getAvailability() === null) {
                continue;
            }

            $productAvailabilities[$sku][] = $productConcreteAvailabilityTransfer->getAvailability()->toFloat();
        }

        return $productAvailabilities;
    }
}
