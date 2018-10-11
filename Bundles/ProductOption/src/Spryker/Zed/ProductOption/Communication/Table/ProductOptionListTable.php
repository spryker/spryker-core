<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOption\Communication\Table;

use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\MoneyTransfer;
use Orm\Zed\ProductOption\Persistence\Map\SpyProductOptionGroupTableMap;
use Orm\Zed\ProductOption\Persistence\Map\SpyProductOptionValueTableMap;
use Orm\Zed\ProductOption\Persistence\SpyProductOptionGroup;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToCurrencyFacadeInterface;
use Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToMoneyFacadeInterface;
use Spryker\Zed\ProductOption\Persistence\ProductOptionQueryContainerInterface;

class ProductOptionListTable extends AbstractTable
{
    public const TABLE_COL_PRICE = 'price';
    public const TABLE_COL_GROSS_PRICE = 'gross_price';
    public const TABLE_COL_NET_PRICE = 'net_price';
    public const TABLE_COL_SKU = 'sku';
    public const TABLE_COL_NAME = 'name';
    public const TABLE_COL_ACTIONS = 'Actions';

    public const URL_PARAM_ID_PRODUCT_OPTION_GROUP = 'id-product-option-group';
    public const URL_PARAM_ACTIVE = 'active';
    public const URL_PARAM_REDIRECT_URL = 'redirect-url';

    public const PRICE_NET = 'PRICE_NET';
    public const PRICE_GROSS = 'PRICE_GROSS';

    public const PRICE_LABEL = '<span class="label label-info">%s</span>';

    /**
     * @var \Spryker\Zed\ProductOption\Persistence\ProductOptionQueryContainerInterface
     */
    protected $productOptionQueryContainer;

    /**
     * @var \Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToCurrencyFacadeInterface
     */
    protected $currencyFacade;

    /**
     * @var \Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToMoneyFacadeInterface
     */
    protected $moneyFacade;

    /**
     * @var array Keys are currency ids, values are currency transfer objects in array format.
     */
    protected static $currencyBuffer = [];

    /**
     * @param \Spryker\Zed\ProductOption\Persistence\ProductOptionQueryContainerInterface $productOptionQueryContainer
     * @param \Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToCurrencyFacadeInterface $currencyFacade
     * @param \Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToMoneyFacadeInterface $moneyFacade
     */
    public function __construct(
        ProductOptionQueryContainerInterface $productOptionQueryContainer,
        ProductOptionToCurrencyFacadeInterface $currencyFacade,
        ProductOptionToMoneyFacadeInterface $moneyFacade
    ) {
        $this->productOptionQueryContainer = $productOptionQueryContainer;
        $this->currencyFacade = $currencyFacade;
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
            static::TABLE_COL_SKU => 'SKU',
            static::TABLE_COL_NAME => 'Name',
            static::TABLE_COL_GROSS_PRICE => 'Gross Price',
            static::TABLE_COL_NET_PRICE => 'Net Price',
            SpyProductOptionGroupTableMap::COL_ACTIVE => 'Status',
            static::TABLE_COL_ACTIONS => 'Actions',
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

        $config->addRawColumn(static::TABLE_COL_ACTIONS);
        $config->addRawColumn(static::TABLE_COL_SKU);
        $config->addRawColumn(static::TABLE_COL_GROSS_PRICE);
        $config->addRawColumn(static::TABLE_COL_NET_PRICE);
        $config->addRawColumn(static::TABLE_COL_NAME);
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

        $productQuery = $this->productOptionQueryContainer->queryAllProductOptionGroups();

        /** @var \Orm\Zed\ProductOption\Persistence\SpyProductOptionGroup[] $queryResult */
        $queryResult = $this->runQuery($productQuery, $config, true);

        foreach ($queryResult as $productOptionGroupEntity) {
            $formattedPrices = $this->getFormattedPrices($productOptionGroupEntity);

            $result[] = [
                SpyProductOptionGroupTableMap::COL_ID_PRODUCT_OPTION_GROUP => $productOptionGroupEntity->getIdProductOptionGroup(),
                SpyProductOptionGroupTableMap::COL_NAME => $productOptionGroupEntity->getName(),
                static::TABLE_COL_SKU => $this->formatSkus($productOptionGroupEntity),
                static::TABLE_COL_NAME => $this->formatNames($productOptionGroupEntity),
                static::TABLE_COL_GROSS_PRICE => $formattedPrices[static::PRICE_GROSS],
                static::TABLE_COL_NET_PRICE => $formattedPrices[static::PRICE_NET],
                SpyProductOptionGroupTableMap::COL_ACTIVE => $this->getStatus($productOptionGroupEntity),
                static::TABLE_COL_ACTIONS => $this->getActionButtons($productOptionGroupEntity),
            ];
        }

        return $result;
    }

    /**
     * Example return
     *  [
     *      idProductOptionValue1 =>
     *          [
     *              PRICE_NET => ['<span>CHF 15.00</span>', '<span>EUR 10.00</span>', ...],
     *              PRICE_GROSS => ['<span>CHF 17.00</span>', '<span>EUR 15.00</span>', ...],
     *          ]
     *  ]
     *
     * First level keys are product option value ids,
     *   second level keys are price mode identifiers,
     *   third level keys are simple numerical indexes for each price,
     *   values are formatted prices with symbol.
     *
     * @param \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\ProductOption\Persistence\SpyProductOptionValue[] $productOptionValueCollection
     *
     * @return array
     */
    protected function getPriceCollection(ObjectCollection $productOptionValueCollection)
    {
        $priceCollection = [];
        foreach ($productOptionValueCollection as $productOptionValueEntity) {
            foreach ($productOptionValueEntity->getProductOptionValuePrices() as $productOptionPriceEntity) {
                $idProductOptionValue = $productOptionValueEntity->getIdProductOptionValue();
                $idCurrency = $productOptionPriceEntity->getFkCurrency();
                $netPrice = $productOptionPriceEntity->getNetPrice();
                $grossPrice = $productOptionPriceEntity->getGrossPrice();

                $priceCollection[$idProductOptionValue][static::PRICE_NET][] = $this->formatPrice($netPrice, $idCurrency);
                $priceCollection[$idProductOptionValue][static::PRICE_GROSS][] = $this->formatPrice($grossPrice, $idCurrency);
            }
        }

        return $priceCollection;
    }

    /**
     * @param int|null $price
     * @param int $idCurrency
     *
     * @return string
     */
    protected function formatPrice($price, $idCurrency)
    {
        if ($price === null) {
            return sprintf(static::PRICE_LABEL, '-');
        }

        $moneyTransfer = (new MoneyTransfer())
            ->setAmount((string)$price)
            ->setCurrency($this->getCurrencyTransfer($idCurrency));

        return sprintf(static::PRICE_LABEL, $this->moneyFacade->formatWithSymbol($moneyTransfer));
    }

    /**
     * @param int $idCurrency
     *
     * @return \Generated\Shared\Transfer\CurrencyTransfer
     */
    protected function getCurrencyTransfer($idCurrency)
    {
        if (!isset(static::$currencyBuffer[$idCurrency])) {
            static::$currencyBuffer[$idCurrency] = $this->currencyFacade
                ->getByIdCurrency($idCurrency)
                ->toArray();
        }

        return (new CurrencyTransfer())->fromArray(static::$currencyBuffer[$idCurrency]);
    }

    /**
     * @param \Orm\Zed\ProductOption\Persistence\SpyProductOptionGroup $entity
     *
     * @return string[]
     */
    protected function getFormattedPrices(SpyProductOptionGroup $entity)
    {
        $priceCollection = $this->getPriceCollection($entity->getSpyProductOptionValues());

        $formattedPrices = [
            static::PRICE_NET => '',
            static::PRICE_GROSS => '',
        ];
        foreach ($priceCollection as $productOptionValuePrices) {
            $formattedPrices[static::PRICE_NET] .= $this->wrapInlineCellItem(
                implode(' ', $productOptionValuePrices[static::PRICE_NET])
            );
            $formattedPrices[static::PRICE_GROSS] .= $this->wrapInlineCellItem(
                implode(' ', $productOptionValuePrices[static::PRICE_GROSS])
            );
        }

        return $formattedPrices;
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
                static::URL_PARAM_ID_PRODUCT_OPTION_GROUP => $productOptionGroupEntity->getIdProductOptionGroup(),
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
                static::URL_PARAM_ID_PRODUCT_OPTION_GROUP => $productOptionGroupEntity->getIdProductOptionGroup(),
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
                static::URL_PARAM_ID_PRODUCT_OPTION_GROUP => $productOptionGroupEntity->getIdProductOptionGroup(),
                static::URL_PARAM_ACTIVE => $productOptionGroupEntity->getActive() ? 0 : 1,
                static::URL_PARAM_REDIRECT_URL => $redirectUrl,
            ]
        );

        return $this->generateStatusButton($editProductOptionUrl, $productOptionGroupEntity->getActive());
    }

    /**
     * @param \Spryker\Service\UtilText\Model\Url\Url $viewDiscountUrl
     * @param bool $isActive
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
