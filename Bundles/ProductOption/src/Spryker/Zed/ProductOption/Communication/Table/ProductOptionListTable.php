<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOption\Communication\Table;

use Orm\Zed\ProductOption\Persistence\Map\SpyProductOptionGroupTableMap;
use Orm\Zed\ProductOption\Persistence\Map\SpyProductOptionValueTableMap;
use Orm\Zed\ProductOption\Persistence\SpyProductOptionGroup;
use Spryker\Shared\Url\Url;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToMoneyInterface;
use Spryker\Zed\ProductOption\Persistence\ProductOptionQueryContainerInterface;

class ProductOptionListTable extends AbstractTable
{

    const TABLE_COL_PRICE = 'price';
    const TABLE_COL_SKU = 'sku';
    const TABLE_COL_NAME = 'name';
    const TABLE_COL_ACTIONS = 'Actions';

    const URL_PARAM_ID_PRODUCT_OPTION_GROUP = 'id-product-option-group';
    const URL_PARAM_ACTIVE = 'active';
    const URL_PARAM_REDIRECT_URL = 'redirect-url';

    /**
     * @var \Spryker\Zed\ProductOption\Persistence\ProductOptionQueryContainerInterface
     */
    protected $productOptionQueryContainer;

    /**
     * @var \Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToMoneyInterface
     */
    protected $moneyFacade;

    /**
     * @param \Spryker\Zed\ProductOption\Persistence\ProductOptionQueryContainerInterface $productOptionQueryContainer
     * @param \Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToMoneyInterface $moneyFacade
     */
    public function __construct(
        ProductOptionQueryContainerInterface $productOptionQueryContainer,
        ProductOptionToMoneyInterface $moneyFacade
    ) {
        $this->productOptionQueryContainer = $productOptionQueryContainer;
        $this->moneyFacade = $moneyFacade;
    }


    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config)
    {
        $url = Url::generate('list-table')->build();
        $config->setUrl($url);

        $config->setHeader([
            SpyProductOptionGroupTableMap::COL_ID_PRODUCT_OPTION_GROUP => 'Option group ID',
            SpyProductOptionGroupTableMap::COL_NAME => 'Group name',
            self::TABLE_COL_SKU => 'SKU',
            self::TABLE_COL_NAME => 'Name',
            self::TABLE_COL_PRICE => 'Price',
            SpyProductOptionGroupTableMap::COL_ACTIVE => 'Status',
            self::TABLE_COL_ACTIONS => 'Actions',
        ]);

        $config->setSearchable([
            SpyProductOptionValueTableMap::COL_SKU,
            SpyProductOptionValueTableMap::COL_VALUE,
            SpyProductOptionGroupTableMap::COL_ID_PRODUCT_OPTION_GROUP,
            SpyProductOptionGroupTableMap::COL_NAME,
        ]);

        $config->setSortable([
            SpyProductOptionGroupTableMap::COL_ID_PRODUCT_OPTION_GROUP,
            SpyProductOptionGroupTableMap::COL_ACTIVE,
            SpyProductOptionGroupTableMap::COL_NAME,
        ]);

        $config->setDefaultSortColumnIndex(0);
        $config->setDefaultSortDirection(TableConfiguration::SORT_DESC);

        $config->addRawColumn(self::TABLE_COL_ACTIONS);
        $config->addRawColumn(self::TABLE_COL_SKU);
        $config->addRawColumn(self::TABLE_COL_PRICE);
        $config->addRawColumn(self::TABLE_COL_NAME);
        $config->addRawColumn(SpyProductOptionGroupTableMap::COL_ACTIVE);

        return $config;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return mixed
     */
    protected function prepareData(TableConfiguration $config)
    {
        $result = [];

        $productQuery = $this->productOptionQueryContainer->queryProductOptionGroupWithValues();

        $queryResult = $this->runQuery($productQuery, $config, true);

        /** @var \Orm\Zed\ProductOption\Persistence\SpyProductOptionGroup $productOptionGroupEntity */
        foreach ($queryResult as $productOptionGroupEntity) {

            $result[] = [
                SpyProductOptionGroupTableMap::COL_ID_PRODUCT_OPTION_GROUP => $productOptionGroupEntity->getIdProductOptionGroup(),
                SpyProductOptionGroupTableMap::COL_NAME => $productOptionGroupEntity->getName(),
                self::TABLE_COL_SKU => $this->formatSkus($productOptionGroupEntity),
                self::TABLE_COL_NAME => $this->formatNames($productOptionGroupEntity),
                self::TABLE_COL_PRICE => $this->formatPrices($productOptionGroupEntity),
                SpyProductOptionGroupTableMap::COL_ACTIVE => $this->getStatus($productOptionGroupEntity),
                self::TABLE_COL_ACTIONS => $this->getActionButtons($productOptionGroupEntity)
            ];
        }

        return $result;
    }

    /**
     * @param \Orm\Zed\ProductOption\Persistence\SpyProductOptionGroup $productOptionGroupEntity
     *
     * @return string
     */
    protected function formatSkus(SpyProductOptionGroup $productOptionGroupEntity)
    {
        $skus = '';
        foreach ($productOptionGroupEntity->getSpyProductOptionValues() as $productOptionValueEntity) {
            $skus .= $this->wrapInlineCellItem($productOptionValueEntity->getSku());
        }
        return $skus;
    }

    /**
     * @param \Orm\Zed\ProductOption\Persistence\SpyProductOptionGroup $productOptionGroupEntity
     *
     * @return string
     */
    protected function formatNames(SpyProductOptionGroup $productOptionGroupEntity)
    {
        $names = '';
        foreach ($productOptionGroupEntity->getSpyProductOptionValues() as $productOptionValueEntity) {
            $names .= $this->wrapInlineCellItem($productOptionValueEntity->getValue());
        }
        return $names;
    }

    /**
     * @param \Orm\Zed\ProductOption\Persistence\SpyProductOptionGroup $productOptionGroupEntity
     *
     * @return string
     */
    protected function formatPrices(SpyProductOptionGroup $productOptionGroupEntity)
    {
        $names = '';
        foreach ($productOptionGroupEntity->getSpyProductOptionValues() as $productOptionValueEntity) {

            $moneyTransfer = $this->moneyFacade->fromInteger($productOptionValueEntity->getPrice());

            $names .= $this->wrapInlineCellItem(
                $this->moneyFacade->formatWithSymbol($moneyTransfer)
            );
        }
        return $names;
    }

    /**
     * @param string $item
     *
     * @return string
     */
    protected function wrapInlineCellItem($item)
    {
        return '<p>' . $item . '</p>';
    }

    /**
     * @param \Orm\Zed\ProductOption\Persistence\SpyProductOptionGroup $productOptionGroupEntity
     *
     * @return string
     */
    protected function getStatus(SpyProductOptionGroup $productOptionGroupEntity)
    {
        if ($productOptionGroupEntity->getActive()) {
            return '<p class="text-success">Active</p>';
        }

        return '<p class="text-danger">Inactive</p>';
    }

    /**
     * @param \Orm\Zed\ProductOption\Persistence\SpyProductOptionGroup $productOptionGroupEntity
     *
     * @return string
     */
    protected function getActionButtons(SpyProductOptionGroup $productOptionGroupEntity)
    {
        $buttons = [];
        $buttons[] = $this->createEditButton($productOptionGroupEntity);
        $buttons[] = $this->createViewButton($productOptionGroupEntity);
        $buttons[] = $this->createDeativateButton($productOptionGroupEntity);

        return implode(' ', $buttons);
    }

    /**
     * @param \Orm\Zed\ProductOption\Persistence\SpyProductOptionGroup $productOptionGroupEntity
     *
     * @return string
     */
    protected function createViewButton(SpyProductOptionGroup $productOptionGroupEntity)
    {
        $viewProductOptionUrl = Url::generate(
            '/product-option/view/index',
            [
                self::URL_PARAM_ID_PRODUCT_OPTION_GROUP => $productOptionGroupEntity->getIdProductOptionGroup()
            ]
        );

        return $this->generateViewButton($viewProductOptionUrl, 'View');
    }

    /**
     * @param \Orm\Zed\ProductOption\Persistence\SpyProductOptionGroup $productOptionGroupEntity
     *
     * @return string
     */
    protected function createEditButton(SpyProductOptionGroup $productOptionGroupEntity)
    {
        $editProductOptionUrl = Url::generate(
            '/product-option/edit/index',
            [
                self::URL_PARAM_ID_PRODUCT_OPTION_GROUP => $productOptionGroupEntity->getIdProductOptionGroup()
            ]
        );

        return $this->generateEditButton($editProductOptionUrl, 'Edit');
    }

    /**
     * @param \Orm\Zed\ProductOption\Persistence\SpyProductOptionGroup $productOptionGroupEntity
     *
     * @return string
     */
    protected function createDeativateButton(SpyProductOptionGroup $productOptionGroupEntity)
    {
        $redirectUrl = Url::generate('/product-option/list/index')->build();

        $editProductOptionUrl = Url::generate(
            '/product-option/index/toggle-active',
            [
                self::URL_PARAM_ID_PRODUCT_OPTION_GROUP => $productOptionGroupEntity->getIdProductOptionGroup(),
                self::URL_PARAM_ACTIVE => $productOptionGroupEntity->getActive() ? 0 : 1,
                self::URL_PARAM_REDIRECT_URL => $redirectUrl,
            ]
        );

        return $this->generateStatusButton($editProductOptionUrl, $productOptionGroupEntity->getActive());
    }

    /**
     * @param \Spryker\Shared\Url\Url $viewDiscountUrl
     * @param string $isActive
     *
     * @return string
     */
    protected function generateStatusButton(Url $viewDiscountUrl, $isActive)
    {
        if ($isActive) {
            return $this->generateRemoveButton($viewDiscountUrl, 'Deactivate');
        }

        return $this->generateViewButton($viewDiscountUrl, 'Activate');
    }

}
