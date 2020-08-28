<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesMerchantPortalGui\Persistence\Propel\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\MerchantOrderItemCollectionTransfer;
use Generated\Shared\Transfer\MerchantOrderItemTransfer;
use Generated\Shared\Transfer\ProductImageTransfer;
use Orm\Zed\Sales\Persistence\Map\SpySalesOrderItemMetadataTableMap;
use Orm\Zed\Sales\Persistence\Map\SpySalesOrderItemTableMap;
use Orm\Zed\StateMachine\Persistence\Map\SpyStateMachineItemStateTableMap;
use Spryker\Zed\SalesMerchantPortalGui\Dependency\Service\SalesMerchantPortalGuiToUtilEncodingServiceInterface;

class MerchantOrderItemTableDataMapper
{
    /**
     * @uses \Spryker\Zed\SalesMerchantPortalGui\Communication\ConfigurationProvider\MerchantOrderItemGuiTableConfigurationProvider::COL_KEY_SKU
     */
    protected const COL_KEY_SKU = 'sku';

    /**
     * @uses \Spryker\Zed\SalesMerchantPortalGui\Communication\ConfigurationProvider\MerchantOrderItemGuiTableConfigurationProvider::COL_KEY_IMAGE
     */
    protected const COL_KEY_IMAGE = 'image';

    /**
     * @uses \Spryker\Zed\SalesMerchantPortalGui\Communication\ConfigurationProvider\MerchantOrderItemGuiTableConfigurationProvider::COL_KEY_NAME
     */
    protected const COL_KEY_NAME = 'name';

    /**
     * @uses \Spryker\Zed\SalesMerchantPortalGui\Communication\ConfigurationProvider\MerchantOrderItemGuiTableConfigurationProvider::COL_KEY_QUANTITY
     */
    protected const COL_KEY_QUANTITY = 'quantity';

    /**
     * @uses \Spryker\Zed\SalesMerchantPortalGui\Communication\ConfigurationProvider\MerchantOrderItemGuiTableConfigurationProvider::COL_KEY_STATE
     */
    protected const COL_KEY_STATE = 'state';

    public const MERCHANT_ORDER_ITEM_DATA_COLUMN_MAP = [
        self::COL_KEY_SKU => SpySalesOrderItemTableMap::COL_SKU,
        self::COL_KEY_IMAGE => SpySalesOrderItemMetadataTableMap::COL_IMAGE,
        self::COL_KEY_NAME => SpySalesOrderItemTableMap::COL_NAME,
        self::COL_KEY_QUANTITY => SpySalesOrderItemTableMap::COL_QUANTITY,
        self::COL_KEY_STATE => SpyStateMachineItemStateTableMap::COL_NAME,
    ];

    /**
     * @var \Spryker\Zed\SalesMerchantPortalGui\Dependency\Service\SalesMerchantPortalGuiToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @param \Spryker\Zed\SalesMerchantPortalGui\Dependency\Service\SalesMerchantPortalGuiToUtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct(SalesMerchantPortalGuiToUtilEncodingServiceInterface $utilEncodingService)
    {
        $this->utilEncodingService = $utilEncodingService;
    }

    /**
     * @param array $merchantOrderItemTableDataArray
     * @param \Generated\Shared\Transfer\MerchantOrderItemCollectionTransfer $merchantOrderItemCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantOrderItemCollectionTransfer
     */
    public function mapMerchantOrderItemTableDataArrayToMerchantOrderCollectionTransfer(
        array $merchantOrderItemTableDataArray,
        MerchantOrderItemCollectionTransfer $merchantOrderItemCollectionTransfer
    ): MerchantOrderItemCollectionTransfer {
        $merchantOrderItemTransfers = [];

        foreach ($merchantOrderItemTableDataArray as $merchantOrderItemTableRowDataArray) {
            $attributes = $merchantOrderItemTableRowDataArray[ItemTransfer::CONCRETE_ATTRIBUTES] ?  $this->utilEncodingService->decodeJson(
                $merchantOrderItemTableRowDataArray[ItemTransfer::CONCRETE_ATTRIBUTES],
                true
            ) : [];
            $merchantOrderItemTableRowDataArray[ItemTransfer::CONCRETE_ATTRIBUTES] = $attributes;

            $itemTransfer = (new ItemTransfer())
                ->fromArray($merchantOrderItemTableRowDataArray, true)
                ->addImage((new ProductImageTransfer())->setExternalUrlSmall($merchantOrderItemTableRowDataArray[ProductImageTransfer::EXTERNAL_URL_SMALL]));

            $merchantOrderItemTransfer = (new MerchantOrderItemTransfer())
                ->fromArray($merchantOrderItemTableRowDataArray, true)
                ->setOrderItem($itemTransfer);

            $merchantOrderItemTransfers[] = $merchantOrderItemTransfer;
        }

        $merchantOrderItemCollectionTransfer->setMerchantOrderItems(new ArrayObject($merchantOrderItemTransfers));

        return $merchantOrderItemCollectionTransfer;
    }
}
