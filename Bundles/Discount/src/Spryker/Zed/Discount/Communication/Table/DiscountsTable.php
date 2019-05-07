<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Communication\Table;

use Orm\Zed\Discount\Persistence\Map\SpyDiscountTableMap;
use Orm\Zed\Discount\Persistence\SpyDiscount;
use Orm\Zed\Discount\Persistence\SpyDiscountQuery;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Discount\Persistence\DiscountQueryContainerInterface;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Traversable;

class DiscountsTable extends AbstractTable
{
    public const TABLE_COL_PERIOD = self::TYPE_COL_PERIOD;
    public const TABLE_COL_TYPE = 'Type';
    public const TYPE_COL_PERIOD = 'Period';
    public const TABLE_COL_ACTIONS = 'Actions';
    public const TABLE_COL_STORE = 'Store';

    public const URL_PARAM_ID_DISCOUNT = 'id-discount';
    public const URL_PARAM_VISIBILITY = 'visibility';
    public const URL_PARAM_REDIRECT_URL = 'redirect-url';

    public const URL_FRAGMENT_TAB_CONTENT_VOUCHER = 'tab-content-voucher';

    public const DATE_FORMAT = 'Y-m-d';
    public const BUTTON_ACTIVATE = 'Activate';
    public const BUTTON_DEACTIVATE = 'Deactivate';

    /**
     * @var \Orm\Zed\Discount\Persistence\SpyDiscountQuery
     */
    protected $discountQuery;

    /**
     * @var \Spryker\Zed\Discount\Persistence\DiscountQueryContainerInterface
     */
    protected $discountQueryContainer;

    /**
     * @var array|\Spryker\Zed\Discount\Dependency\Plugin\DiscountCalculatorPluginInterface[]
     */
    protected $calculatorPlugins = [];

    /**
     * @param \Orm\Zed\Discount\Persistence\SpyDiscountQuery $discountQuery
     * @param \Spryker\Zed\Discount\Persistence\DiscountQueryContainerInterface $discountQueryContainer
     * @param \Spryker\Zed\Discount\Dependency\Plugin\DiscountCalculatorPluginInterface[] $calculatorPlugins
     */
    public function __construct(SpyDiscountQuery $discountQuery, DiscountQueryContainerInterface $discountQueryContainer, array $calculatorPlugins)
    {
        $this->discountQuery = $discountQuery;
        $this->discountQueryContainer = $discountQueryContainer;
        $this->calculatorPlugins = $calculatorPlugins;
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
            SpyDiscountTableMap::COL_ID_DISCOUNT => 'Discount ID',
            SpyDiscountTableMap::COL_DISPLAY_NAME => 'Name',
            SpyDiscountTableMap::COL_AMOUNT => 'Amount',
            static::TABLE_COL_TYPE => static::TABLE_COL_TYPE,
            static::TYPE_COL_PERIOD => static::TABLE_COL_PERIOD,
            SpyDiscountTableMap::COL_IS_ACTIVE => 'Status',
            SpyDiscountTableMap::COL_IS_EXCLUSIVE => 'Exclusive',
            static::TABLE_COL_STORE => static::TABLE_COL_STORE,
            static::TABLE_COL_ACTIONS => static::TABLE_COL_ACTIONS,
        ]);

        $config->setSearchable([
            SpyDiscountTableMap::COL_DISPLAY_NAME,
            SpyDiscountTableMap::COL_ID_DISCOUNT,
        ]);

        $config->setSortable([
            SpyDiscountTableMap::COL_ID_DISCOUNT,
            SpyDiscountTableMap::COL_DISPLAY_NAME,
            SpyDiscountTableMap::COL_AMOUNT,
            SpyDiscountTableMap::COL_IS_ACTIVE,
            SpyDiscountTableMap::COL_IS_EXCLUSIVE,
        ]);

        $config->setDefaultSortField(
            SpyDiscountTableMap::COL_ID_DISCOUNT,
            TableConfiguration::SORT_DESC
        );

        $config->addRawColumn(static::TABLE_COL_ACTIONS);
        $config->addRawColumn(SpyDiscountTableMap::COL_AMOUNT);
        $config->addRawColumn(static::TABLE_COL_STORE);

        return $config;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array
     */
    protected function prepareData(TableConfiguration $config)
    {
        $result = [];

        /** @var \Orm\Zed\Discount\Persistence\SpyDiscount[] $discountEntities */
        $discountEntities = $this->runQuery($this->discountQuery, $config, true);

        foreach ($discountEntities as $discountEntity) {
            $result[] = [
                SpyDiscountTableMap::COL_ID_DISCOUNT => $discountEntity->getIdDiscount(),
                SpyDiscountTableMap::COL_DISPLAY_NAME => $discountEntity->getDisplayName(),
                SpyDiscountTableMap::COL_AMOUNT => $this->getFormattedAmount($discountEntity),
                static::TABLE_COL_TYPE => $this->getDiscountType($discountEntity),
                static::TYPE_COL_PERIOD => $this->createTimePeriod($discountEntity),
                SpyDiscountTableMap::COL_IS_ACTIVE => $this->getStatus($discountEntity),
                SpyDiscountTableMap::COL_IS_EXCLUSIVE => $discountEntity->getIsExclusive(),
                static::TABLE_COL_ACTIONS => $this->getActionButtons($discountEntity),
                static::TABLE_COL_STORE => $this->getStoreNames($discountEntity->getIdDiscount()),
            ];
        }

        return $result;
    }

    /**
     * @param int $idDiscount
     *
     * @return string
     */
    protected function getStoreNames($idDiscount)
    {
        $discountStoreCollection = $this
            ->discountQueryContainer
            ->queryDiscountStoreWithStoresByFkDiscount($idDiscount)
            ->find();

        return $this->extractStoreNames($discountStoreCollection);
    }

    /**
     * @param \Traversable|\Orm\Zed\Discount\Persistence\SpyDiscountStore[] $discountStoreEntityCollection
     *
     * @return string
     */
    protected function extractStoreNames(Traversable $discountStoreEntityCollection)
    {
        $storeNames = [];
        foreach ($discountStoreEntityCollection as $discountStoreEntity) {
            $storeNames[] = sprintf(
                '<span class="label label-info">%s</span>',
                $discountStoreEntity->getSpyStore()->getName()
            );
        }

        return implode(" ", $storeNames);
    }

    /**
     * @param \Orm\Zed\Discount\Persistence\SpyDiscount $discountEntity
     *
     * @return string
     */
    protected function getActionButtons(SpyDiscount $discountEntity)
    {
        $buttons = [];
        $buttons[] = $this->createEditButton($discountEntity);
        $buttons[] = $this->createViewButton($discountEntity);
        $buttons[] = $this->createAddVoucherCodeButton($discountEntity);
        $buttons[] = $this->createToggleDiscountVisibilityButton($discountEntity);

        return implode(' ', $buttons);
    }

    /**
     * @param \Orm\Zed\Discount\Persistence\SpyDiscount $discountEntity
     *
     * @return string
     */
    protected function getStatus(SpyDiscount $discountEntity)
    {
         return $discountEntity->getIsActive() ? 'Active' : 'Inactive';
    }

    /**
     * @param \Orm\Zed\Discount\Persistence\SpyDiscount $discountEntity
     *
     * @return string
     */
    protected function getDiscountType(SpyDiscount $discountEntity)
    {
        return str_replace('_', ' ', $discountEntity->getDiscountType());
    }

    /**
     * @param \Orm\Zed\Discount\Persistence\SpyDiscount $discountEntity
     *
     * @return string
     */
    protected function createEditButton(SpyDiscount $discountEntity)
    {
        $editDiscountUrl = Url::generate(
            '/discount/index/edit',
            [
                static::URL_PARAM_ID_DISCOUNT => $discountEntity->getIdDiscount(),
            ]
        );

        return $this->generateEditButton($editDiscountUrl, 'Edit');
    }

    /**
     * @param \Orm\Zed\Discount\Persistence\SpyDiscount $discountEntity
     *
     * @return string
     */
    protected function createViewButton(SpyDiscount $discountEntity)
    {
        $viewDiscountUrl = Url::generate(
            '/discount/index/view',
            [
                static::URL_PARAM_ID_DISCOUNT => $discountEntity->getIdDiscount(),
            ]
        );

        return $this->generateViewButton($viewDiscountUrl, 'View');
    }

    /**
     * @param \Orm\Zed\Discount\Persistence\SpyDiscount $discountEntity
     *
     * @return string
     */
    protected function createAddVoucherCodeButton(SpyDiscount $discountEntity)
    {
        if (!$discountEntity->getFkDiscountVoucherPool()) {
            return '';
        }

        $addVoucherCodeDiscountUrl = Url::generate(
            '/discount/index/edit',
            [
                static::URL_PARAM_ID_DISCOUNT => $discountEntity->getIdDiscount(),
            ],
            [
                Url::FRAGMENT => static::URL_FRAGMENT_TAB_CONTENT_VOUCHER,
            ]
        );

        return $this->generateCreateButton($addVoucherCodeDiscountUrl, 'Add code');
    }

    /**
     * @param \Orm\Zed\Discount\Persistence\SpyDiscount $discountEntity
     *
     * @return string
     */
    protected function createToggleDiscountVisibilityButton(SpyDiscount $discountEntity)
    {
        $visibility = static::BUTTON_ACTIVATE;
        if ($discountEntity->getIsActive()) {
            $visibility = static::BUTTON_DEACTIVATE;
        }

        $viewDiscountUrl = Url::generate(
            '/discount/index/toggle-discount-visibility',
            [
                static::URL_PARAM_ID_DISCOUNT => $discountEntity->getIdDiscount(),
                static::URL_PARAM_VISIBILITY => $visibility,
                static::URL_PARAM_REDIRECT_URL => '/discount/index/list',
            ]
        );

        return $this->generateStatusButton($viewDiscountUrl, $visibility);
    }

    /**
     * @param \Spryker\Service\UtilText\Model\Url\Url $viewDiscountUrl
     * @param string $visibility
     *
     * @return string
     */
    protected function generateStatusButton(Url $viewDiscountUrl, $visibility)
    {
        if ($visibility === static::BUTTON_ACTIVATE) {
            return $this->generateViewButton($viewDiscountUrl, $visibility);
        }

        return $this->generateRemoveButton($viewDiscountUrl, $visibility);
    }

    /**
     * @param \Orm\Zed\Discount\Persistence\SpyDiscount $discountEntity
     *
     * @return string
     */
    protected function createTimePeriod(SpyDiscount $discountEntity)
    {
        return $discountEntity->getValidFrom(static::DATE_FORMAT) . ' - ' . $discountEntity->getValidTo(self::DATE_FORMAT);
    }

    /**
     * @param \Orm\Zed\Discount\Persistence\SpyDiscount $discountEntity
     *
     * @return string
     */
    protected function getFormattedAmount(SpyDiscount $discountEntity)
    {
        $calculatorPlugin = $this->calculatorPlugins[$discountEntity->getCalculatorPlugin()];

        if (count($discountEntity->getDiscountAmounts()) === 0) {
            return $calculatorPlugin->getFormattedAmount($discountEntity->getAmount());
        }

        $rowTemplate = '<tr><td>GROSS</td><td>NET</td></tr>';
        $row = '';
        foreach ($discountEntity->getDiscountAmounts() as $discountAmountEntity) {
            $netAmount = '-';
            $grossAmount = '-';
            $currencyCode = $discountAmountEntity->getCurrency()->getCode();
            if ($discountAmountEntity->getNetAmount()) {
                $netAmount = $calculatorPlugin->getFormattedAmount(
                    $discountAmountEntity->getNetAmount(),
                    $currencyCode
                );
            }

            if ($discountAmountEntity->getGrossAmount()) {
                $grossAmount = $calculatorPlugin->getFormattedAmount(
                    $discountAmountEntity->getGrossAmount(),
                    $currencyCode
                );
            }

            $template = str_replace('GROSS', $grossAmount, $rowTemplate);
            $row .= str_replace('NET', $netAmount, $template);
        }

        $table = '
           <table width="80%" cellspacing="2">
           <tr>
                <td>Gross</td>
                <td>Net</td>
           </tr>
           ' . $row . '
           </table>
        ';

        return $table;
    }
}
