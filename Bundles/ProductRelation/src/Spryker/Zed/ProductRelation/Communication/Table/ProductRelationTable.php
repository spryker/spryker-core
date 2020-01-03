<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelation\Communication\Table;

use Generated\Shared\Transfer\ProductAbstractTransfer;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractLocalizedAttributesTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Orm\Zed\ProductRelation\Persistence\Map\SpyProductRelationTableMap;
use Orm\Zed\ProductRelation\Persistence\Map\SpyProductRelationTypeTableMap;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\ProductRelation\Communication\Controller\DeleteController;
use Spryker\Zed\ProductRelation\Communication\Controller\EditController;
use Spryker\Zed\ProductRelation\Communication\Controller\ViewController;
use Spryker\Zed\ProductRelation\Dependency\Facade\ProductRelationToLocaleInterface;
use Spryker\Zed\ProductRelation\Dependency\Facade\ProductRelationToProductInterface;
use Spryker\Zed\ProductRelation\Persistence\ProductRelationQueryContainer;
use Spryker\Zed\ProductRelation\Persistence\ProductRelationQueryContainerInterface;
use Spryker\Zed\ProductRelation\ProductRelationConfig;

class ProductRelationTable extends AbstractTable
{
    public const COL_ACTIONS = 'Actions';

    public const URL_RELATION_DEACTIVATE = '/product-relation/edit/deactivate';
    public const URL_RELATION_DELETE = '/product-relation/delete/index';
    public const URL_RELATION_ACTIVATE = '/product-relation/edit/activate';
    public const URL_PRODUCT_RELATION_LIST = '/product-relation/list';

    /**
     * @var \Spryker\Zed\ProductRelation\Persistence\ProductRelationQueryContainerInterface
     */
    protected $productRelationQueryContainer;

    /**
     * @var \Spryker\Zed\ProductRelation\Dependency\Facade\ProductRelationToProductInterface
     */
    protected $productFacade;

    /**
     * @var \Spryker\Zed\ProductRelation\ProductRelationConfig
     */
    protected $productRelationConfig;

    /**
     * @var \Spryker\Zed\ProductRelation\Dependency\Facade\ProductRelationToLocaleInterface
     */
    protected $localeFacade;

    /**
     * @param \Spryker\Zed\ProductRelation\Persistence\ProductRelationQueryContainerInterface $productRelationQueryContainer
     * @param \Spryker\Zed\ProductRelation\Dependency\Facade\ProductRelationToProductInterface $productFacade
     * @param \Spryker\Zed\ProductRelation\ProductRelationConfig $productRelationConfig
     * @param \Spryker\Zed\ProductRelation\Dependency\Facade\ProductRelationToLocaleInterface $localeFacade
     */
    public function __construct(
        ProductRelationQueryContainerInterface $productRelationQueryContainer,
        ProductRelationToProductInterface $productFacade,
        ProductRelationConfig $productRelationConfig,
        ProductRelationToLocaleInterface $localeFacade
    ) {

        $this->productRelationQueryContainer = $productRelationQueryContainer;
        $this->productFacade = $productFacade;
        $this->productRelationConfig = $productRelationConfig;
        $this->localeFacade = $localeFacade;

        $this->setTableIdentifier('product-relation-table');
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config)
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
    protected function setRawColumns(TableConfiguration $config)
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
    protected function setHeaders(TableConfiguration $config)
    {
        $config->setHeader([
            SpyProductRelationTableMap::COL_ID_PRODUCT_RELATION => '#',
            SpyProductAbstractTableMap::COL_SKU => 'Sku',
            SpyProductAbstractLocalizedAttributesTableMap::COL_NAME => 'Abstract product name',
            SpyProductRelationTypeTableMap::COL_KEY => 'Relation type',
            ProductRelationQueryContainer::COL_NUMBER_OF_RELATED_PRODUCTS => 'Number of products',
            SpyProductRelationTableMap::COL_IS_ACTIVE => 'Status',
            static::COL_ACTIONS => static::COL_ACTIONS,
        ]);
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return void
     */
    protected function setSortableFields(TableConfiguration $config)
    {
        $config->setSortable([
            SpyProductRelationTableMap::COL_ID_PRODUCT_RELATION,
            SpyProductAbstractTableMap::COL_SKU,
            SpyProductAbstractLocalizedAttributesTableMap::COL_NAME,
            SpyProductRelationTableMap::COL_IS_ACTIVE,
            SpyProductRelationTypeTableMap::COL_KEY,
            ProductRelationQueryContainer::COL_NUMBER_OF_RELATED_PRODUCTS,
        ]);
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return void
     */
    protected function setSearchableFields(TableConfiguration $config)
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
    protected function setDefaultSortField(TableConfiguration $config)
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
    protected function prepareData(TableConfiguration $config)
    {
        $localeTransfer = $this->localeFacade->getCurrentLocale();
        $query = $this->productRelationQueryContainer
            ->queryProductRelationsWithProductCount($localeTransfer->getIdLocale());

        $queryResults = $this->runQuery($query, $config);

        $results = [];
        foreach ($queryResults as $item) {
            $results[] = $this->mapResults($item);
        }

        return $results;
    }

    /**
     * @param array $item
     *
     * @return array
     */
    protected function mapResults(array $item)
    {
        return [
            SpyProductRelationTableMap::COL_ID_PRODUCT_RELATION => $item[SpyProductRelationTableMap::COL_ID_PRODUCT_RELATION],
            SpyProductAbstractTableMap::COL_SKU => $item[SpyProductAbstractTableMap::COL_SKU],
            SpyProductAbstractLocalizedAttributesTableMap::COL_NAME => $item[SpyProductAbstractLocalizedAttributesTableMap::COL_NAME],
            SpyProductRelationTypeTableMap::COL_KEY => $item[SpyProductRelationTypeTableMap::COL_KEY],
            ProductRelationQueryContainer::COL_NUMBER_OF_RELATED_PRODUCTS => $item[ProductRelationQueryContainer::COL_NUMBER_OF_RELATED_PRODUCTS],
            SpyProductRelationTableMap::COL_IS_ACTIVE => $this->buildActiveLabel($item),
            static::COL_ACTIONS => implode(' ', $this->buildActions($item)),
        ];
    }

    /**
     * @param array $item
     *
     * @return array
     */
    protected function buildActions(array $item)
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
    protected function createViewButton(array $item)
    {
        return $this->generateViewButton(
            Url::generate(
                '/product-relation/view/index',
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
    protected function buildActiveLabel(array $item)
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
    protected function createEditButton(array $item)
    {
        return $this->generateEditButton(
            Url::generate(
                '/product-relation/edit/index',
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
    protected function createViewInShopButton(array $item)
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
    protected function findProductUrl(array $product)
    {
        $productAbstract = (new ProductAbstractTransfer())
            ->setSku($product[SpyProductAbstractTableMap::COL_SKU])
            ->setIdProductAbstract($product[SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT]);

        $url = null;

        $idLocale = $this->localeFacade->getCurrentLocale()->getIdLocale();
        $productUrl = $this->productFacade->getProductUrl($productAbstract);
        foreach ($productUrl->getUrls() as $localizedProductUrl) {
            if ($localizedProductUrl->getLocale()->getIdLocale() === $idLocale) {
                $url = $this->productRelationConfig->findYvesHost() . $localizedProductUrl->getUrl();
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
    protected function createRelationStatusChangeButton(array $item)
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
    protected function createDeleteRelationButton(array $item)
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
