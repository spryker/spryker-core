<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Persistence\Propel;

use ArrayObject;
use Generated\Shared\Transfer\ProductAbstractCollectionTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductImageSetTransfer;
use Generated\Shared\Transfer\ProductImageTransfer;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractLocalizedAttributesTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Service\ProductMerchantPortalGuiToUtilEncodingServiceInterface;

class ProductAbstractTableDataMapper
{
    /**
     * @uses \Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\ProductAbstractGuiTableConfigurationProvider::COL_KEY_SKU
     *
     * @var string
     */
    protected const COL_KEY_SKU = 'sku';

    /**
     * @uses \Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\ProductAbstractGuiTableConfigurationProvider::COL_KEY_IMAGE
     *
     * @var string
     */
    protected const COL_KEY_IMAGE = 'image';

    /**
     * @uses \Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\ProductAbstractGuiTableConfigurationProvider::COL_KEY_NAME
     *
     * @var string
     */
    protected const COL_KEY_NAME = 'name';

    /**
     * @uses \Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\ProductAbstractGuiTableConfigurationProvider::COL_KEY_SUPER_ATTRIBUTES
     *
     * @var string
     */
    protected const COL_KEY_SUPER_ATTRIBUTES = 'superAttributes';

    /**
     * @uses \Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\ProductAbstractGuiTableConfigurationProvider::COL_KEY_VARIANTS
     *
     * @var string
     */
    protected const COL_KEY_VARIANTS = 'variants';

    /**
     * @uses \Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\ProductAbstractGuiTableConfigurationProvider::COL_KEY_CATEGORIES
     *
     * @var string
     */
    protected const COL_KEY_CATEGORIES = 'categories';

    /**
     * @uses \Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\ProductAbstractGuiTableConfigurationProvider::COL_KEY_STORES
     *
     * @var string
     */
    protected const COL_KEY_STORES = 'stores';

    /**
     * @uses \Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\ProductAbstractGuiTableConfigurationProvider::COL_KEY_VISIBILITY
     *
     * @var string
     */
    protected const COL_KEY_VISIBILITY = 'visibility';

    /**
     * @uses \Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\ProductAbstractGuiTableConfigurationProvider::COL_KEY_APPROVAL
     *
     * @var string
     */
    protected const COL_KEY_APPROVAL = 'approval';

    /**
     * @uses \Spryker\Zed\ProductMerchantPortalGui\Persistence\ProductMerchantPortalGuiRepository::COL_NAME_FALLBACK
     *
     * @var string
     */
    protected const COL_NAME_FALLBACK = 'name_fallback';

    /**
     * @var \Spryker\Zed\ProductMerchantPortalGui\Dependency\Service\ProductMerchantPortalGuiToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @var array<string, string>
     */
    public const PRODUCT_ABSTRACT_DATA_COLUMN_MAP = [
        self::COL_KEY_SKU => SpyProductAbstractTableMap::COL_SKU,
        self::COL_KEY_IMAGE => ProductImageTransfer::EXTERNAL_URL_SMALL,
        self::COL_KEY_NAME => SpyProductAbstractLocalizedAttributesTableMap::COL_NAME,
        self::COL_KEY_SUPER_ATTRIBUTES => ProductAbstractTransfer::ATTRIBUTES,
        self::COL_KEY_VARIANTS => ProductAbstractTransfer::CONCRETE_PRODUCT_COUNT,
        self::COL_KEY_CATEGORIES => ProductAbstractTransfer::CATEGORY_NAMES,
        self::COL_KEY_STORES => ProductAbstractTransfer::STORE_NAMES,
        self::COL_KEY_VISIBILITY => ProductAbstractTransfer::IS_ACTIVE,
        self::COL_KEY_APPROVAL => SpyProductAbstractTableMap::COL_APPROVAL_STATUS,
    ];

    /**
     * @param \Spryker\Zed\ProductMerchantPortalGui\Dependency\Service\ProductMerchantPortalGuiToUtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct(ProductMerchantPortalGuiToUtilEncodingServiceInterface $utilEncodingService)
    {
        $this->utilEncodingService = $utilEncodingService;
    }

    /**
     * @param array<mixed> $productAbstractTableDataArray
     * @param \Generated\Shared\Transfer\ProductAbstractCollectionTransfer $productAbstractCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractCollectionTransfer
     */
    public function mapProductAbstractTableDataArrayToProductAbstractCollectionTransfer(
        array $productAbstractTableDataArray,
        ProductAbstractCollectionTransfer $productAbstractCollectionTransfer
    ): ProductAbstractCollectionTransfer {
        $productAbstractTransfers = [];

        foreach ($productAbstractTableDataArray as $productAbstractTableRowDataArray) {
            $productAbstractTableRowDataArray[ProductAbstractTransfer::ATTRIBUTES] = $this->utilEncodingService->decodeJson(
                $productAbstractTableRowDataArray[ProductAbstractTransfer::ATTRIBUTES],
                true,
            );
            $productAbstractTableRowDataArray[ProductAbstractTransfer::STORE_NAMES] = array_filter(explode(
                ',',
                $productAbstractTableRowDataArray[ProductAbstractTransfer::STORE_NAMES],
            ));
            $productAbstractTableRowDataArray[ProductAbstractTransfer::CATEGORY_NAMES] = array_filter(explode(
                ',',
                $productAbstractTableRowDataArray[ProductAbstractTransfer::CATEGORY_NAMES],
            ));

            $productAbstractTransfer = (new ProductAbstractTransfer())->fromArray($productAbstractTableRowDataArray, true);
            $productAbstractTransfer = $this->mapImageToProductAbstract($productAbstractTableRowDataArray, $productAbstractTransfer);

            if ($productAbstractTableRowDataArray[static::COL_NAME_FALLBACK]) {
                $productAbstractTransfer->setName($productAbstractTableRowDataArray[static::COL_NAME_FALLBACK]);
            }

            $productAbstractTransfers[] = $productAbstractTransfer;
        }

        $productAbstractCollectionTransfer->setProductAbstracts(new ArrayObject($productAbstractTransfers));

        return $productAbstractCollectionTransfer;
    }

    /**
     * @param array<mixed> $productAbstractTableRowDataArray
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    protected function mapImageToProductAbstract(
        array $productAbstractTableRowDataArray,
        ProductAbstractTransfer $productAbstractTransfer
    ): ProductAbstractTransfer {
        $productImageSetTransfer = (new ProductImageSetTransfer())
            ->addProductImage((new ProductImageTransfer())->setExternalUrlSmall($productAbstractTableRowDataArray[ProductImageTransfer::EXTERNAL_URL_SMALL]));

        $productAbstractTransfer->setImageSets(new ArrayObject([$productImageSetTransfer]));

        return $productAbstractTransfer;
    }
}
