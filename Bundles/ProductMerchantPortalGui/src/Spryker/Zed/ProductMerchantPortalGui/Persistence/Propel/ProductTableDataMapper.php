<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Persistence\Propel;

use ArrayObject;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\LocalizedAttributesTransfer;
use Generated\Shared\Transfer\ProductConcreteCollectionTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductImageSetTransfer;
use Generated\Shared\Transfer\ProductImageTransfer;
use Orm\Zed\Product\Persistence\Map\SpyProductLocalizedAttributesTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Orm\Zed\ProductValidity\Persistence\Map\SpyProductValidityTableMap;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Service\ProductMerchantPortalGuiToUtilEncodingServiceInterface;

class ProductTableDataMapper
{
    /**
     * @uses \Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\ProductGuiTableConfigurationProvider::COL_KEY_SKU
     *
     * @var string
     */
    protected const COL_KEY_SKU = 'sku';

    /**
     * @uses \Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\ProductGuiTableConfigurationProvider::COL_KEY_NAME
     *
     * @var string
     */
    protected const COL_KEY_NAME = 'name';

    /**
     * @uses \Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\ProductGuiTableConfigurationProvider::COL_KEY_STATUS
     *
     * @var string
     */
    protected const COL_KEY_STATUS = 'status';

    /**
     * @uses \Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\ProductGuiTableConfigurationProvider::COL_KEY_IMAGE
     *
     * @var string
     */
    protected const COL_KEY_IMAGE = 'image';

    /**
     * @uses \Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\ProductGuiTableConfigurationProvider::COL_KEY_SUPER_ATTRIBUTES
     *
     * @var string
     */
    protected const COL_KEY_SUPER_ATTRIBUTES = 'superAttributes';

    /**
     * @uses \Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\ProductGuiTableConfigurationProvider::COL_KEY_VALID_FROM
     *
     * @var string
     */
    protected const COL_KEY_VALID_FROM = 'validFrom';

    /**
     * @uses \Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\ProductGuiTableConfigurationProvider::COL_KEY_VALID_TO
     *
     * @var string
     */
    protected const COL_KEY_VALID_TO = 'validTo';

    /**
     * @var array<string, string>
     */
    public const PRODUCT_DATA_COLUMN_MAP = [
        self::COL_KEY_SKU => SpyProductTableMap::COL_SKU,
        self::COL_KEY_NAME => SpyProductLocalizedAttributesTableMap::COL_NAME,
        self::COL_KEY_STATUS => SpyProductTableMap::COL_IS_ACTIVE,
        self::COL_KEY_IMAGE => ProductImageTransfer::EXTERNAL_URL_SMALL,
        self::COL_KEY_SUPER_ATTRIBUTES => SpyProductLocalizedAttributesTableMap::COL_ATTRIBUTES,
        self::COL_KEY_VALID_FROM => SpyProductValidityTableMap::COL_VALID_FROM,
        self::COL_KEY_VALID_TO => SpyProductValidityTableMap::COL_VALID_TO,
    ];

    /**
     * @var \Spryker\Zed\ProductMerchantPortalGui\Dependency\Service\ProductMerchantPortalGuiToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @param \Spryker\Zed\ProductMerchantPortalGui\Dependency\Service\ProductMerchantPortalGuiToUtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct(ProductMerchantPortalGuiToUtilEncodingServiceInterface $utilEncodingService)
    {
        $this->utilEncodingService = $utilEncodingService;
    }

    /**
     * @param array<mixed> $productTableDataArray
     * @param \Generated\Shared\Transfer\ProductConcreteCollectionTransfer $productConcreteCollectionTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteCollectionTransfer
     */
    public function mapProductTableDataArrayToProductConcreteCollectionTransfer(
        array $productTableDataArray,
        ProductConcreteCollectionTransfer $productConcreteCollectionTransfer,
        LocaleTransfer $localeTransfer
    ): ProductConcreteCollectionTransfer {
        foreach ($productTableDataArray as $productTableRowDataArray) {
            $productTableRowDataArray = $this->prepareProductAttributesTableData($productTableRowDataArray, $localeTransfer);

            $productConcreteTransfer = (new ProductConcreteTransfer())->fromArray($productTableRowDataArray, true);
            $productConcreteTransfer->setAttributes($productTableRowDataArray[ProductConcreteTransfer::ATTRIBUTES]);
            $productConcreteTransfer->setLocalizedAttributes($productTableRowDataArray[ProductConcreteTransfer::LOCALIZED_ATTRIBUTES]);
            $productConcreteTransfer = $this->mapImageToProductConcrete($productTableRowDataArray, $productConcreteTransfer);

            $productConcreteCollectionTransfer->addProduct($productConcreteTransfer);
        }

        return $productConcreteCollectionTransfer;
    }

    /**
     * @param array<mixed> $productTableRowDataArray
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return array<mixed>
     */
    protected function prepareProductAttributesTableData(array $productTableRowDataArray, LocaleTransfer $localeTransfer): array
    {
        $productConcreteAttributes = $this->utilEncodingService->decodeJson(
            $productTableRowDataArray[ProductConcreteTransfer::ATTRIBUTES] ?? null,
            true,
        );
        $productConcreteAttributes = is_array($productConcreteAttributes) ? $productConcreteAttributes : [];

        $productConcreteLocalizedAttributes = $this->utilEncodingService->decodeJson(
            $productTableRowDataArray[ProductConcreteTransfer::LOCALIZED_ATTRIBUTES] ?? null,
            true,
        );
        $productConcreteLocalizedAttributes = is_array($productConcreteLocalizedAttributes) ? $productConcreteLocalizedAttributes : [];

        $localizedAttributesTransfer = (new LocalizedAttributesTransfer())
            ->setAttributes($productConcreteLocalizedAttributes)
            ->setName($productTableRowDataArray[LocalizedAttributesTransfer::NAME])
            ->setLocale($localeTransfer);

        $productTableRowDataArray[ProductConcreteTransfer::ATTRIBUTES] = $productConcreteAttributes;
        $productTableRowDataArray[ProductConcreteTransfer::LOCALIZED_ATTRIBUTES] = new ArrayObject([$localizedAttributesTransfer]);

        return $productTableRowDataArray;
    }

    /**
     * @param array<mixed> $productTableRowDataArray
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    protected function mapImageToProductConcrete(
        array $productTableRowDataArray,
        ProductConcreteTransfer $productConcreteTransfer
    ): ProductConcreteTransfer {
        $productImageSetTransfer = (new ProductImageSetTransfer())
            ->addProductImage((new ProductImageTransfer())->setExternalUrlSmall(
                $productTableRowDataArray[ProductImageTransfer::EXTERNAL_URL_SMALL],
            ));

        $productConcreteTransfer->setImageSets(new ArrayObject([$productImageSetTransfer]));

        return $productConcreteTransfer;
    }
}
