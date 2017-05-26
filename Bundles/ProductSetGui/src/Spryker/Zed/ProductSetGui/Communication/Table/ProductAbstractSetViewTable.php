<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSetGui\Communication\Table;

use Generated\Shared\Transfer\LocaleTransfer;
use Orm\Zed\Product\Persistence\SpyProductAbstract;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\Money\Business\MoneyFacade;
use Spryker\Zed\Price\Business\PriceFacade;
use Spryker\Zed\ProductImage\Business\ProductImageFacade;
use Spryker\Zed\ProductSetGui\Communication\Controller\AbstractProductSetController;
use Spryker\Zed\ProductSetGui\Persistence\ProductSetGuiQueryContainer;
use Spryker\Zed\ProductSetGui\Persistence\ProductSetGuiQueryContainerInterface;

class ProductAbstractSetViewTable extends AbstractTable
{

    const TABLE_IDENTIFIER = 'product-abstract-set-view-table';

    const COL_ID_PRODUCT_ABSTRACT = 'id_product_abstract';
    const COL_IMAGE = 'image';
    const COL_DETAILS = 'details';
    const COL_NAME = ProductSetGuiQueryContainer::COL_ALIAS_NAME;
    const COL_ORDER = ProductSetGuiQueryContainer::COL_ALIAS_POSITION;

    /**
     * @var \Spryker\Zed\ProductSetGui\Persistence\ProductSetGuiQueryContainerInterface
     */
    protected $productSetGuiQueryContainer;

    /**
     * @var int
     */
    protected $idProductSet;

    /**
     * @var \Generated\Shared\Transfer\LocaleTransfer
     */
    protected $localeTransfer;

    /**
     * @param \Spryker\Zed\ProductSetGui\Persistence\ProductSetGuiQueryContainerInterface $productSetGuiQueryContainer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param int $idProductSet
     */
    public function __construct(
        ProductSetGuiQueryContainerInterface $productSetGuiQueryContainer,
        LocaleTransfer $localeTransfer,
        $idProductSet
    ) {
        $this->productSetGuiQueryContainer = $productSetGuiQueryContainer;
        $this->localeTransfer = $localeTransfer;
        $this->idProductSet = $idProductSet;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config)
    {
        $urlSuffix = sprintf('?%s=%d', AbstractProductSetController::PARAM_ID, $this->idProductSet);
        $this->defaultUrl = self::TABLE_IDENTIFIER . $urlSuffix;
        $this->setTableIdentifier(self::TABLE_IDENTIFIER);

        $this->disableSearch();

        $config->setHeader([
            self::COL_ID_PRODUCT_ABSTRACT => 'ID',
            self::COL_IMAGE => 'Preview',
            self::COL_DETAILS => 'Product details',
            self::COL_ORDER => 'Order',
        ]);

        $config->setSortable([
            self::COL_ID_PRODUCT_ABSTRACT,
            self::COL_ORDER,
        ]);

        $config->setRawColumns([
            self::COL_IMAGE,
            self::COL_DETAILS,
        ]);

        $config->setDefaultSortField(self::COL_ORDER, TableConfiguration::SORT_ASC);
        $config->setStateSave(false);

        return $config;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array
     */
    protected function prepareData(TableConfiguration $config)
    {
        $query = $this->productSetGuiQueryContainer->queryProductAbstractByIdProductSet($this->idProductSet, $this->localeTransfer);

        $queryResults = $this->runQuery($query, $config, true);

        $results = [];
        foreach ($queryResults as $productAbstractEntity) {
            $results[] = $this->formatRow($productAbstractEntity);
        }

        return $results;
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstract $productAbstractEntity
     *
     * @return array
     */
    protected function formatRow(SpyProductAbstract $productAbstractEntity)
    {
        return [
            self::COL_ID_PRODUCT_ABSTRACT => $productAbstractEntity->getIdProductAbstract(),
            self::COL_IMAGE => $this->getProductPreview($productAbstractEntity),
            self::COL_DETAILS => $this->generateDetailsColumn($productAbstractEntity),
            self::COL_ORDER => $productAbstractEntity->getVirtualColumn(self::COL_ORDER),
        ];
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstract $productAbstractEntity
     *
     * @return string
     */
    protected function getProductPreview(SpyProductAbstract $productAbstractEntity)
    {
        return sprintf(
            '<img src="%s">',
            $this->getProductPreviewUrl($productAbstractEntity)
        );
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstract $productAbstractEntity
     *
     * @return null|string
     */
    protected function getProductPreviewUrl(SpyProductAbstract $productAbstractEntity)
    {
        $productImageFacade = new ProductImageFacade(); // FIXME

        $productImageSetTransferCollection = $productImageFacade->getProductImagesSetCollectionByProductAbstractId($productAbstractEntity->getIdProductAbstract());

        foreach ($productImageSetTransferCollection as $productImageSetTransfer) {
            foreach ($productImageSetTransfer->getProductImages() as $productImageTransfer) {
                $previewUrl = $productImageTransfer->getExternalUrlSmall();

                if ($previewUrl) {
                    return $previewUrl;
                }
            }
        }

        return null;
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstract $productAbstractEntity
     *
     * @return string
     */
    protected function generateDetailsColumn(SpyProductAbstract $productAbstractEntity)
    {
        $rawContent = '<p>' .
            '<strong><a href="%s">%s</a></strong><br />' .
            '<small>SKU: %s</small><br/>' .
            '<small>Price: %s</small>' .
            '</p> %s';

        $content = sprintf(
            $rawContent,
            Url::generate('/product-management/view', ['id-product-abstract' => $productAbstractEntity->getIdProductAbstract()])->build(),
            $productAbstractEntity->getVirtualColumn(self::COL_NAME),
            $productAbstractEntity->getSku(),
            $this->getProductPrice($productAbstractEntity),
            $this->getAbstractProductStatusLabel($productAbstractEntity)
        );

        return $content;
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstract $productAbstractEntity
     *
     * @return string
     */
    protected function getAbstractProductStatusLabel(SpyProductAbstract $productAbstractEntity)
    {
        $isActive = false;
        foreach ($productAbstractEntity->getSpyProducts() as $spyProductEntity) {
            if ($spyProductEntity->getIsActive()) {
                $isActive = true;
            }
        }

        return $this->getStatusLabel($isActive);
    }

    /**
     * @param string $status
     *
     * @return string
     */
    protected function getStatusLabel($status)
    {
        if (!$status) {
            return '<span class="label label-danger">Inactive</span>';
        }

        return '<span class="label label-info">Active</span>';
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstract $productAbstractEntity
     *
     * @return string|null
     */
    protected function getProductPrice(SpyProductAbstract $productAbstractEntity)
    {
        $priceFacade = new PriceFacade(); // FIXME
        $priceProductTransfer = $priceFacade->findProductAbstractPrice($productAbstractEntity->getIdProductAbstract());

        if (!$priceProductTransfer) {
            return null;
        }

        $moneyFacade = new MoneyFacade(); // FIXME
        $moneyTransfer = $moneyFacade->fromInteger($priceProductTransfer->getPrice());

        return $moneyFacade->formatWithSymbol($moneyTransfer);
    }

}
