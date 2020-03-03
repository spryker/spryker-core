<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelationGui\Communication\Table;

use Generated\Shared\Transfer\ProductAbstractTransfer;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractLocalizedAttributesTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Orm\Zed\ProductRelation\Persistence\Map\SpyProductRelationTableMap;
use Orm\Zed\ProductRelation\Persistence\Map\SpyProductRelationTypeTableMap;
use Orm\Zed\ProductRelation\Persistence\SpyProductRelationQuery;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\ProductRelationGui\Communication\Controller\DeleteController;
use Spryker\Zed\ProductRelationGui\Communication\Controller\EditController;
use Spryker\Zed\ProductRelationGui\Communication\Controller\ViewController;
use Spryker\Zed\ProductRelationGui\Dependency\Facade\ProductRelationGuiToLocaleFacadeInterface;
use Spryker\Zed\ProductRelationGui\Dependency\Facade\ProductRelationGuiToProductFacadeInterface;
use Spryker\Zed\ProductRelationGui\ProductRelationGuiConfig;

class ProductRelationTable extends AbstractTable
{
    public const COL_ACTIONS = 'Actions';
    public const COL_NUMBER_OF_RELATED_PRODUCTS = 'numberOfRelatedProducts';

    public const URL_RELATION_DEACTIVATE = '/product-relation-gui/edit/deactivate';
    public const URL_RELATION_DELETE = '/product-relation-gui/delete/index';
    public const URL_RELATION_ACTIVATE = '/product-relation-gui/edit/activate';
    public const URL_PRODUCT_RELATION_LIST = '/product-relation-gui/list/index';
    public const URL_PRODUCT_RELATION_EDIT = '/product-relation-gui/edit/index';
    public const URL_PRODUCT_RELATION_VIEW = '/product-relation-gui/view/index';

    /**
     * @var \Spryker\Zed\ProductRelationGui\Dependency\Facade\ProductRelationGuiToProductFacadeInterface
     */
    protected $productFacade;

    /**
     * @var \Spryker\Zed\ProductRelationGui\ProductRelationGuiConfig
     */
    protected $productRelationGuiConfig;

    /**
     * @var \Spryker\Zed\ProductRelationGui\Dependency\Facade\ProductRelationGuiToLocaleFacadeInterface
     */
    protected $localeFacade;

    /**
     * @var \Orm\Zed\ProductRelation\Persistence\SpyProductRelationQuery
     */
    protected $productRelationQuery;

    /**
     * @param \Orm\Zed\ProductRelation\Persistence\SpyProductRelationQuery $productRelationQuery
     * @param \Spryker\Zed\ProductRelationGui\Dependency\Facade\ProductRelationGuiToProductFacadeInterface $productFacade
     * @param \Spryker\Zed\ProductRelationGui\ProductRelationGuiConfig $productRelationGuiConfig
     * @param \Spryker\Zed\ProductRelationGui\Dependency\Facade\ProductRelationGuiToLocaleFacadeInterface $localeFacade
     */
    public function __construct(
        SpyProductRelationQuery $productRelationQuery,
        ProductRelationGuiToProductFacadeInterface $productFacade,
        ProductRelationGuiConfig $productRelationGuiConfig,
        ProductRelationGuiToLocaleFacadeInterface $localeFacade
    ) {
        $this->productRelationQuery = $productRelationQuery;
        $this->productFacade = $productFacade;
        $this->productRelationGuiConfig = $productRelationGuiConfig;
        $this->localeFacade = $localeFacade;

        $this->setTableIdentifier('product-relation-table');
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config): TableConfiguration
    {
        $this->setHeaders($config);
        $this->setRawColumns($config);
        $this->setSortableFields($config);
        $this->setSearchableFields($config);
        $this->setDefaultSortField($config);

        return $config;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return void
     */
    protected function setRawColumns(TableConfiguration $config): void
    {
        $config->setRawColumns([
            static::COL_ACTIONS,
            SpyProductRelationTableMap::COL_IS_ACTIVE,
        ]);
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return void
     */
    protected function setHeaders(TableConfiguration $config): void
    {
        $config->setHeader([
            SpyProductRelationTableMap::COL_ID_PRODUCT_RELATION => '#',
            SpyProductAbstractTableMap::COL_SKU => 'Sku',
            SpyProductAbstractLocalizedAttributesTableMap::COL_NAME => 'Abstract product name',
            SpyProductRelationTypeTableMap::COL_KEY => 'Relation type',
            static::COL_NUMBER_OF_RELATED_PRODUCTS => 'Number of products',
            SpyProductRelationTableMap::COL_IS_ACTIVE => 'Status',
            static::COL_ACTIONS => static::COL_ACTIONS,
        ]);
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return void
     */
    protected function setSortableFields(TableConfiguration $config): void
    {
        $config->setSortable([
            SpyProductRelationTableMap::COL_ID_PRODUCT_RELATION,
            SpyProductAbstractTableMap::COL_SKU,
            SpyProductAbstractLocalizedAttributesTableMap::COL_NAME,
            SpyProductRelationTableMap::COL_IS_ACTIVE,
            SpyProductRelationTypeTableMap::COL_KEY,
            static::COL_NUMBER_OF_RELATED_PRODUCTS,
        ]);
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return void
     */
    protected function setSearchableFields(TableConfiguration $config): void
    {
        $config->setSearchable([
            SpyProductAbstractTableMap::COL_SKU,
            SpyProductRelationTypeTableMap::COL_KEY,
            SpyProductAbstractLocalizedAttributesTableMap::COL_NAME,
        ]);
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return void
     */
    protected function setDefaultSortField(TableConfiguration $config): void
    {
        $config->setDefaultSortField(
            SpyProductRelationTableMap::COL_ID_PRODUCT_RELATION,
            TableConfiguration::SORT_DESC
        );
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array
     */
    protected function prepareData(TableConfiguration $config): array
    {
        $localeTransfer = $this->localeFacade->getCurrentLocale();
        $query = $this->prepareQuery($localeTransfer->getIdLocale());

        $queryResults = $this->runQuery($query, $config);

        $results = [];
        foreach ($queryResults as $item) {
            $results[] = $this->mapResults($item);
        }

        return $results;
    }

    /**
     * @module Product
     *
     * @param int $idLocale
     *
     * @return \Orm\Zed\ProductRelation\Persistence\SpyProductRelationQuery
     */
    protected function prepareQuery(int $idLocale): SpyProductRelationQuery
    {
        return $this->productRelationQuery
            ->select([
                SpyProductRelationTableMap::COL_ID_PRODUCT_RELATION,
                SpyProductAbstractTableMap::COL_SKU,
                SpyProductRelationTypeTableMap::COL_KEY,
                SpyProductRelationTableMap::COL_IS_ACTIVE,
                SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT,
                SpyProductAbstractLocalizedAttributesTableMap::COL_NAME,
            ])
            ->joinSpyProductAbstract()
            ->joinSpyProductRelationProductAbstract('num_alias')
            ->useSpyProductAbstractQuery()
                ->useSpyProductAbstractLocalizedAttributesQuery()
                    ->filterByFkLocale($idLocale)
                ->endUse()
            ->endUse()
            ->withColumn("COUNT('num_alias')", static::COL_NUMBER_OF_RELATED_PRODUCTS)
            ->joinSpyProductRelationType()
            ->groupByIdProductRelation();
    }

    /**
     * @param array $item
     *
     * @return array
     */
    protected function mapResults(array $item): array
    {
        return [
            SpyProductRelationTableMap::COL_ID_PRODUCT_RELATION => $item[SpyProductRelationTableMap::COL_ID_PRODUCT_RELATION],
            SpyProductAbstractTableMap::COL_SKU => $item[SpyProductAbstractTableMap::COL_SKU],
            SpyProductAbstractLocalizedAttributesTableMap::COL_NAME => $item[SpyProductAbstractLocalizedAttributesTableMap::COL_NAME],
            SpyProductRelationTypeTableMap::COL_KEY => $item[SpyProductRelationTypeTableMap::COL_KEY],
            static::COL_NUMBER_OF_RELATED_PRODUCTS => $item[static::COL_NUMBER_OF_RELATED_PRODUCTS],
            SpyProductRelationTableMap::COL_IS_ACTIVE => $this->buildActiveLabel($item),
            static::COL_ACTIONS => implode(' ', $this->buildActions($item)),
        ];
    }

    /**
     * @param array $item
     *
     * @return array
     */
    protected function buildActions(array $item): array
    {
        $buttons = [];
        $buttons[] = $this->createViewButton($item);
        $buttons[] = $this->createViewInShopButton($item);
        $buttons[] = $this->createEditButton($item);
        $buttons[] = $this->createRelationStatusChangeButton($item);
        $buttons[] = $this->createDeleteRelationButton($item);

        return $buttons;
    }

    /**
     * @param array $item
     *
     * @return string
     */
    protected function createViewButton(array $item): string
    {
        return $this->generateViewButton(
            Url::generate(
                static::URL_PRODUCT_RELATION_VIEW,
                [
                    ViewController::URL_PARAM_ID_PRODUCT_RELATION => $item[SpyProductRelationTableMap::COL_ID_PRODUCT_RELATION],
                ]
            ),
            'View'
        );
    }

    /**
     * @param array $item
     *
     * @return string
     */
    protected function buildActiveLabel(array $item): string
    {
        if (!$item[SpyProductRelationTableMap::COL_IS_ACTIVE]) {
            return $this->generateLabel('Inactive', 'label-danger');
        }

        return $this->generateLabel('Active', 'label-info');
    }

    /**
     * @param array $item
     *
     * @return string
     */
    protected function createEditButton(array $item): string
    {
        return $this->generateEditButton(
            Url::generate(
                static::URL_PRODUCT_RELATION_EDIT,
                [
                    'id-product-relation' => $item[SpyProductRelationTableMap::COL_ID_PRODUCT_RELATION],
                ]
            ),
            'Edit'
        );
    }

    /**
     * @param array $item
     *
     * @return string
     */
    protected function createViewInShopButton(array $item): string
    {
        $url = $this->findProductUrl($item);
        if (!$url) {
            return '';
        }

        return $this->generateViewButton(
            $url,
            'View in Shop',
            ['target' => '_blank']
        );
    }

    /**
     * @param array $product
     *
     * @return string|null
     */
    protected function findProductUrl(array $product): ?string
    {
        $productAbstract = (new ProductAbstractTransfer())
            ->setSku($product[SpyProductAbstractTableMap::COL_SKU])
            ->setIdProductAbstract($product[SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT]);

        $url = null;

        $idLocale = $this->localeFacade->getCurrentLocale()->getIdLocale();
        $productUrl = $this->productFacade->getProductUrl($productAbstract);
        foreach ($productUrl->getUrls() as $localizedProductUrl) {
            if ($localizedProductUrl->getLocale()->getIdLocale() === $idLocale) {
                $url = $this->productRelationGuiConfig->getYvesHost() . $localizedProductUrl->getUrl();

                break;
            }
        }

        return $url;
    }

    /**
     * @param array $item
     *
     * @return string
     */
    protected function createRelationStatusChangeButton(array $item): string
    {
        if ($item[SpyProductRelationTableMap::COL_IS_ACTIVE]) {
            return $this->generateRemoveButton(
                Url::generate(static::URL_RELATION_DEACTIVATE, [
                    EditController::URL_PARAM_ID_PRODUCT_RELATION => $item[SpyProductRelationTableMap::COL_ID_PRODUCT_RELATION],
                    EditController::URL_PARAM_REDIRECT_URL => static::URL_PRODUCT_RELATION_LIST,
                ]),
                'Deactivate'
            );
        }

        return $this->generateViewButton(
            Url::generate(static::URL_RELATION_ACTIVATE, [
                EditController::URL_PARAM_ID_PRODUCT_RELATION => $item[SpyProductRelationTableMap::COL_ID_PRODUCT_RELATION],
                EditController::URL_PARAM_REDIRECT_URL => static::URL_PRODUCT_RELATION_LIST,
            ]),
            'Activate'
        );
    }

    /**
     * @param array $item
     *
     * @return string
     */
    protected function createDeleteRelationButton(array $item): string
    {
        return $this->generateRemoveButton(
            Url::generate(static::URL_RELATION_DELETE, [
                DeleteController::URL_PARAM_ID_PRODUCT_RELATION => $item[SpyProductRelationTableMap::COL_ID_PRODUCT_RELATION],
                DeleteController::URL_PARAM_REDIRECT_URL => static::URL_PRODUCT_RELATION_LIST,
            ]),
            'Delete'
        );
    }
}
