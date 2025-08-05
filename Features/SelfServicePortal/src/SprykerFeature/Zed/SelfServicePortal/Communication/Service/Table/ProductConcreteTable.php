<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\Service\Table;

use Generated\Shared\Transfer\ProductImageTransfer;
use Orm\Zed\Product\Persistence\Map\SpyProductLocalizedAttributesTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Orm\Zed\Product\Persistence\SpyProduct;
use Orm\Zed\Product\Persistence\SpyProductQuery;
use Orm\Zed\ProductImage\Persistence\Map\SpyProductImageSetTableMap;
use Orm\Zed\ProductImage\Persistence\Map\SpyProductImageTableMap;
use Orm\Zed\ProductImage\Persistence\SpyProductImageQuery;
use Orm\Zed\ProductValidity\Persistence\Map\SpyProductValidityTableMap;
use Orm\Zed\Store\Persistence\Map\SpyStoreTableMap;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\Locale\Business\LocaleFacade;
use SprykerFeature\Zed\SelfServicePortal\Communication\Controller\CreateOfferController;

class ProductConcreteTable extends AbstractTable
{
    /**
     * @var string
     */
    protected const COL_ACTIONS = 'actions';

    /**
     * @var string
     */
    protected const COL_NAME = 'name';

    /**
     * @uses \SprykerFeature\Zed\SelfServicePortal\Communication\Controller\CreateOfferController::formAction()
     *
     * @var string
     */
    protected const URL_PATH_SELF_SERVICE_PORTAL_CREATE_OFFER_FORM = '/self-service-portal/create-offer/form';

    public function __construct(
        protected SpyProductQuery $productQuery,
        protected SpyProductImageQuery $productImageQuery,
        protected LocaleFacade $localeFacade
    ) {
    }

    /**
     * @var string
     */
    protected const COL_STORES = 'stores';

    /**
     * @var string
     */
    protected const COL_VALID_FROM = 'valid_from';

    /**
     * @var string
     */
    protected const COL_VALID_TO = 'valid_to';

    /**
     * @var string
     */
    protected const COL_IMAGE = 'image';

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return mixed
     */
    protected function configure(TableConfiguration $config)
    {
        $config->setHeader([
            SpyProductTableMap::COL_SKU => 'Sku',
            static::COL_IMAGE => 'Image',
            SpyProductLocalizedAttributesTableMap::COL_NAME => 'Name',
            static::COL_STORES => 'Stores',
            SpyProductValidityTableMap::COL_VALID_FROM => 'Valid From (Time in UTC)',
            SpyProductValidityTableMap::COL_VALID_TO => 'Valid To (Time in UTC)',
            SpyProductTableMap::COL_IS_ACTIVE => 'Status',
            static::COL_ACTIONS => 'Actions',
        ]);

        $config->setRawColumns([
            static::COL_ACTIONS,
            SpyProductTableMap::COL_IS_ACTIVE,
            static::COL_IMAGE,
            static::COL_STORES,
        ]);

        $config->setSearchable([
            SpyProductTableMap::COL_SKU,
            SpyProductLocalizedAttributesTableMap::COL_NAME,
            SpyProductValidityTableMap::COL_VALID_FROM,
            SpyProductValidityTableMap::COL_VALID_TO,
        ]);

        $config->setSortable([
            SpyProductTableMap::COL_SKU,
            SpyProductLocalizedAttributesTableMap::COL_NAME,
            SpyProductValidityTableMap::COL_VALID_FROM,
            SpyProductValidityTableMap::COL_VALID_TO,
            SpyProductTableMap::COL_IS_ACTIVE,
        ]);

        return $config;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return mixed
     */
    protected function prepareData(TableConfiguration $config)
    {
        $idLocale = (int)$this->localeFacade->getCurrentLocale()->getIdLocale();

        $query = $this
            ->productQuery
            ->leftJoinSpyProductValidity()
            ->useSpyProductAbstractQuery()
                ->useSpyProductAbstractStoreQuery()
                    ->useSpyStoreQuery()
                    ->endUse()
                ->endUse()
            ->endUse()
            ->useSpyProductLocalizedAttributesQuery()
            ->filterByFkLocale($idLocale)
            ->endUse()
            ->withColumn(SpyProductLocalizedAttributesTableMap::COL_NAME, static::COL_NAME)
            ->withColumn(sprintf('GROUP_CONCAT(%s)', SpyStoreTableMap::COL_NAME), static::COL_STORES)
            ->withColumn(SpyProductValidityTableMap::COL_VALID_FROM, static::COL_VALID_FROM)
            ->withColumn(SpyProductValidityTableMap::COL_VALID_TO, static::COL_VALID_TO)
            ->addAsColumn(ProductImageTransfer::EXTERNAL_URL_SMALL, sprintf('(%s)', $this->createProductImagesSubquery($idLocale)))
            ->groupByIdProduct();

        $queryResults = $this->runQuery($query, $config, true);

        $productConcreteCollection = [];
        foreach ($queryResults as $productConcreteEntity) {
            $productConcreteCollection[] = $this->generateItem($productConcreteEntity);
        }

        return $productConcreteCollection;
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProduct $productEntity
     *
     * @return array<string, mixed>
     */
    protected function generateItem(SpyProduct $productEntity): array
    {
        $stores = explode(',', $productEntity->getVirtualColumn(static::COL_STORES));
        $storeColumnHtml = '';

        foreach ($stores as $store) {
            $storeColumnHtml .= sprintf('<span class="label label-info">%s</span>', $store);
        }

        return [
            SpyProductTableMap::COL_SKU => (string)$productEntity->getSku(),
            static::COL_IMAGE => sprintf('<img src="%s" style="max-width: 100px;max-height: 100px"/>', $productEntity->getVirtualColumn(ProductImageTransfer::EXTERNAL_URL_SMALL)),
            SpyProductLocalizedAttributesTableMap::COL_NAME => $productEntity->getVirtualColumn(static::COL_NAME),
            static::COL_STORES => $storeColumnHtml,
            SpyProductTableMap::COL_IS_ACTIVE => $this->getStatusLabel($productEntity->getIsActive()),
            SpyProductValidityTableMap::COL_VALID_FROM => $productEntity->getVirtualColumn(static::COL_VALID_FROM) ?: '-',
            SpyProductValidityTableMap::COL_VALID_TO => $productEntity->getVirtualColumn(static::COL_VALID_TO) ?: '-',
            static::COL_ACTIONS => implode(' ', $this->createActionColumn($productEntity)),
        ];
    }

    protected function createProductImagesSubquery(int $idLocale): string
    {
        /** @var literal-string $where */
        $where = sprintf(
            '%1$s = %2$s AND (%3$s = %4$d OR %3$s IS NULL)',
            SpyProductImageSetTableMap::COL_FK_PRODUCT,
            SpyProductTableMap::COL_ID_PRODUCT,
            SpyProductImageSetTableMap::COL_FK_LOCALE,
            $idLocale,
        );
        $productImagesSubquery = $this->productImageQuery
            ->joinSpyProductImageSetToProductImage()
            ->useSpyProductImageSetToProductImageQuery()
            ->joinSpyProductImageSet()
            ->endUse()
            ->where($where)
            ->addSelectColumn(SpyProductImageTableMap::COL_EXTERNAL_URL_SMALL)
            ->orderBy(SpyProductImageSetTableMap::COL_FK_LOCALE)
            ->limit(1);

        $params = [];

        return $productImagesSubquery->createSelectSql($params);
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProduct $productEntity
     *
     * @return array<int, string>
     */
    protected function createActionColumn(SpyProduct $productEntity): array
    {
        $urls = [];

        $urls[] = $this->generateEditButton(
            Url::generate(static::URL_PATH_SELF_SERVICE_PORTAL_CREATE_OFFER_FORM, [
                CreateOfferController::PARAM_ID_PRODUCT_CONCRETE => $productEntity->getIdProduct(),
            ]),
            'Create Offer',
        );

        return $urls;
    }

    protected function getStatusLabel(bool $status): string
    {
        if (!$status) {
            return $this->generateLabel('Deactivated', 'label-danger');
        }

        return $this->generateLabel('Active', 'label-info');
    }
}
