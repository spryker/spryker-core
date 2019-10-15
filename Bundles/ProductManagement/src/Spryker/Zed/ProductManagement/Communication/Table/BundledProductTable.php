<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Table;

use Generated\Shared\Transfer\LocaleTransfer;
use Orm\Zed\Product\Persistence\Map\SpyProductLocalizedAttributesTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Orm\Zed\Product\Persistence\SpyProduct;
use Orm\Zed\ProductBundle\Persistence\Map\SpyProductBundleTableMap;
use Orm\Zed\Stock\Persistence\Map\SpyStockProductTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\DecimalObject\Decimal;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\Product\Persistence\ProductQueryContainerInterface;
use Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToAvailabilityInterface;
use Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToMoneyInterface;
use Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToPriceInterface;
use Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToPriceProductInterface;
use Spryker\Zed\ProductManagement\Dependency\Service\ProductManagementToUtilEncodingInterface;

class BundledProductTable extends AbstractTable
{
    public const COL_SELECT = 'select';
    public const COL_PRICE = 'price';
    public const COL_AVAILABILITY = 'availability';
    public const COL_ID_PRODUCT_CONCRETE = 'id_product_concrete';
    public const SPY_PRODUCT_LOCALIZED_ATTRIBUTE_ALIAS_NAME = 'Name';
    public const SPY_STOCK_PRODUCT_ALIAS_QUANTITY = 'stockQuantity';
    public const IS_NEVER_OUT_OF_STOCK = 'isNeverOutOfStock';

    /**
     * @var \Spryker\Zed\Product\Persistence\ProductQueryContainerInterface
     */
    protected $productQueryContainer;

    /**
     * @var \Spryker\Zed\ProductManagement\Dependency\Service\ProductManagementToUtilEncodingInterface
     */
    protected $utilEncodingService;

    /**
     * @var \Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToPriceProductInterface
     */
    protected $priceProductFacade;

    /**
     * @var \Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToMoneyInterface
     */
    protected $moneyFacade;

    /**
     * @var \Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToAvailabilityInterface
     */
    protected $availabilityFacade;

    /**
     * @var int
     */
    protected $idProductConcrete;

    /**
     * @var \Generated\Shared\Transfer\LocaleTransfer
     */
    protected $localeTransfer;

    /**
     * @var \Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToPriceInterface
     */
    protected $priceFacade;

    /**
     * @param \Spryker\Zed\Product\Persistence\ProductQueryContainerInterface $productQueryContainer
     * @param \Spryker\Zed\ProductManagement\Dependency\Service\ProductManagementToUtilEncodingInterface $utilEncodingService
     * @param \Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToPriceProductInterface $priceProductFacade
     * @param \Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToMoneyInterface $moneyFacade
     * @param \Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToAvailabilityInterface $availabilityFacade
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param \Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToPriceInterface $priceFacade
     * @param int|null $idProductConcrete
     */
    public function __construct(
        ProductQueryContainerInterface $productQueryContainer,
        ProductManagementToUtilEncodingInterface $utilEncodingService,
        ProductManagementToPriceProductInterface $priceProductFacade,
        ProductManagementToMoneyInterface $moneyFacade,
        ProductManagementToAvailabilityInterface $availabilityFacade,
        LocaleTransfer $localeTransfer,
        ProductManagementToPriceInterface $priceFacade,
        $idProductConcrete = null
    ) {
        $this->setTableIdentifier('bundled-product-table');
        $this->productQueryContainer = $productQueryContainer;
        $this->utilEncodingService = $utilEncodingService;
        $this->priceProductFacade = $priceProductFacade;
        $this->moneyFacade = $moneyFacade;
        $this->availabilityFacade = $availabilityFacade;
        $this->idProductConcrete = $idProductConcrete;
        $this->localeTransfer = $localeTransfer;
        $this->priceFacade = $priceFacade;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config)
    {
        $config->setUrl(
            sprintf(
                'bundled-product-table?id-product-concrete=%d',
                $this->idProductConcrete
            )
        );

        $defaultPriceMode = $this->priceFacade->getDefaultPriceMode();

        $priceLabel = sprintf('Price (%s)', $defaultPriceMode);

        $config->setHeader([
            static::COL_SELECT => 'Select',
            static::COL_ID_PRODUCT_CONCRETE => 'id product',
            SpyProductLocalizedAttributesTableMap::COL_NAME => 'Product name',
            SpyProductTableMap::COL_SKU => 'SKU',
            static::COL_PRICE => $priceLabel,
            static::SPY_STOCK_PRODUCT_ALIAS_QUANTITY => 'Stock',
            static::COL_AVAILABILITY => 'Availability',
            SpyStockProductTableMap::COL_IS_NEVER_OUT_OF_STOCK => 'Is never out of stock',
        ]);

        $config->setRawColumns([
            static::COL_SELECT,
            static::COL_PRICE,
            static::COL_AVAILABILITY,
            SpyProductTableMap::COL_SKU,
        ]);

        $config->setSearchable([
            SpyProductLocalizedAttributesTableMap::COL_NAME,
            SpyProductTableMap::COL_SKU,
        ]);

        $config->setSortable([
            SpyProductLocalizedAttributesTableMap::COL_NAME,
            SpyProductTableMap::COL_SKU,
            static::SPY_STOCK_PRODUCT_ALIAS_QUANTITY,
            SpyStockProductTableMap::COL_IS_NEVER_OUT_OF_STOCK,
        ]);

        return $config;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array
     */
    protected function prepareData(TableConfiguration $config)
    {
        $query = $this
            ->productQueryContainer
            ->queryProduct()
            ->leftJoinSpyProductBundleRelatedByFkProduct()
            ->joinSpyProductLocalizedAttributes()
            ->joinStockProduct()
            ->withColumn(SpyProductLocalizedAttributesTableMap::COL_NAME, static::SPY_PRODUCT_LOCALIZED_ATTRIBUTE_ALIAS_NAME)
            ->withColumn(sprintf('SUM(%s)', SpyStockProductTableMap::COL_QUANTITY), static::SPY_STOCK_PRODUCT_ALIAS_QUANTITY)
            ->withColumn(SpyStockProductTableMap::COL_IS_NEVER_OUT_OF_STOCK, static::IS_NEVER_OUT_OF_STOCK)
            ->where(SpyProductLocalizedAttributesTableMap::COL_FK_LOCALE . ' = ?', $this->localeTransfer->getIdLocale())
            ->add(SpyProductBundleTableMap::COL_ID_PRODUCT_BUNDLE, null, Criteria::ISNULL)
            ->groupBy(SpyProductTableMap::COL_ID_PRODUCT)
            ->addGroupByColumn(static::SPY_PRODUCT_LOCALIZED_ATTRIBUTE_ALIAS_NAME)
            ->addGroupByColumn(static::IS_NEVER_OUT_OF_STOCK);

        /** @var \Orm\Zed\Product\Persistence\SpyProduct[] $queryResults */
        $queryResults = $this->runQuery($query, $config, true);

        $productAbstractCollection = [];
        foreach ($queryResults as $productEntity) {
            $productAbstractCollection[] = [
                static::COL_SELECT => $this->addCheckBox($productEntity),
                static::COL_ID_PRODUCT_CONCRETE => $productEntity->getIdProduct(),
                SpyProductLocalizedAttributesTableMap::COL_NAME => $productEntity->getVirtualColumn(static::SPY_PRODUCT_LOCALIZED_ATTRIBUTE_ALIAS_NAME),
                SpyProductTableMap::COL_SKU => $this->getProductEditPageLink($productEntity->getSku(), $productEntity->getFkProductAbstract(), $productEntity->getIdProduct()),
                static::COL_PRICE => $this->getFormattedPrice($productEntity->getSku()),
                static::SPY_STOCK_PRODUCT_ALIAS_QUANTITY => (new Decimal($productEntity->getVirtualColumn(static::SPY_STOCK_PRODUCT_ALIAS_QUANTITY) ?? 0))->trim(),
                static::COL_AVAILABILITY => $this->getAvailability($productEntity)->trim(),
                SpyStockProductTableMap::COL_IS_NEVER_OUT_OF_STOCK => $productEntity->getIsNeverOutOfStock(),
            ];
        }

        return $productAbstractCollection;
    }

    /**
     * @param string $sku
     * @param int $idProductAbstract
     * @param int $idProductConcrete
     *
     * @return string
     */
    protected function getProductEditPageLink($sku, $idProductAbstract, $idProductConcrete)
    {
        $pageEditUrl = Url::generate('/product-management/edit/variant', [
            'id-product-abstract' => $idProductAbstract,
            'id-product' => $idProductConcrete,
        ])->build();

        $pageEditLink = '<a target="_blank" href="' . $pageEditUrl . '">' . $sku . '</a>';

        return $pageEditLink;
    }

    /**
     * @param string $sku
     *
     * @return string
     */
    protected function getFormattedPrice($sku)
    {
        $priceInCents = $this->priceProductFacade->findPriceBySku($sku);

        if ($priceInCents === null) {
            return 'N/A';
        }

        $moneyTransfer = $this->moneyFacade->fromInteger($priceInCents);

        return $this->moneyFacade->formatWithSymbol($moneyTransfer);
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProduct $productConcreteEntity
     *
     * @return string
     */
    protected function addCheckBox(SpyProduct $productConcreteEntity)
    {
        $checked = '';
        if ($this->idProductConcrete) {
            $criteria = new Criteria();
            $criteria->add(SpyProductBundleTableMap::COL_FK_PRODUCT, $this->idProductConcrete);

            if ($productConcreteEntity->getSpyProductBundlesRelatedByFkBundledProduct($criteria)->count() > 0) {
                $checked = 'checked="checked"';
            }
        }

        return sprintf(
            "<input id='product_assign_checkbox_%d' class='product_assign_checkbox' type='checkbox' data-info='%s' %s >",
            $productConcreteEntity->getIdProduct(),
            $this->utilEncodingService->encodeJson($productConcreteEntity->toArray()),
            $checked
        );
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProduct $productConcreteEntity
     *
     * @return \Spryker\DecimalObject\Decimal
     */
    protected function getAvailability(SpyProduct $productConcreteEntity): Decimal
    {
        if (!$productConcreteEntity->getIsNeverOutOfStock()) {
            return $this->availabilityFacade->calculateAvailabilityForProduct($productConcreteEntity->getSku());
        }

        return new Decimal(0);
    }
}
