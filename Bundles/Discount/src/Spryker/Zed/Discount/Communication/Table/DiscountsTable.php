<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Communication\Table;

use Orm\Zed\Discount\Persistence\Map\SpyDiscountTableMap;
use Orm\Zed\Discount\Persistence\SpyDiscount;
use Orm\Zed\Discount\Persistence\SpyDiscountQuery;
use Spryker\Shared\Url\Url;
use Spryker\Zed\Discount\DiscountDependencyProvider;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

class DiscountsTable extends AbstractTable
{

    const COL_VALUE = 'Value';
    const COL_PERIOD = 'Period';
    const DATE_FORMAT = 'Y-m-d';
    const COL_DECISION_RULES = 'Cart Rules';
    const DECISION_RULE_PLUGIN = 'DecisionRulePlugin';

    const URL_PARAM_ID_DISCOUNT = 'id-discount';
    const TABLE_COL_ACTIONS = 'Actions';
    const URL_PARAM_VISIBILITY = 'visibility';
    const URL_PARAM_REDIRECT_URL = 'redirect-url';

    /**
     * @var \Orm\Zed\Discount\Persistence\SpyDiscountQuery
     */
    protected $discountQuery;

    /**
     * @param \Orm\Zed\Discount\Persistence\SpyDiscountQuery $discountQuery
     */
    public function __construct(SpyDiscountQuery $discountQuery)
    {
        $this->discountQuery = $discountQuery;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config)
    {
        $url = Url::generate('listTable')->build();
        $config->setUrl($url);

        $config->setHeader([
            SpyDiscountTableMap::COL_ID_DISCOUNT => 'Discount ID',
            SpyDiscountTableMap::COL_DISPLAY_NAME => 'Name',
            SpyDiscountTableMap::COL_AMOUNT => 'Amount',
            'Type' => 'Type',
            'Period' => self::COL_PERIOD,
            SpyDiscountTableMap::COL_IS_ACTIVE => 'Status',
            SpyDiscountTableMap::COL_IS_EXCLUSIVE => 'Exclusive',
            self::TABLE_COL_ACTIONS => self::TABLE_COL_ACTIONS
        ]);

        $config->setSearchable([
            SpyDiscountTableMap::COL_DISPLAY_NAME
        ]);

        $config->addRawColumn(self::TABLE_COL_ACTIONS);

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

        $query = $this->discountQuery
            ->where('fk_discount_voucher_pool IS NULL');

        $queryResult = $this->runQuery($query, $config, true);

        /** @var \Orm\Zed\Discount\Persistence\SpyDiscount $discountEntity */
        foreach ($queryResult as $discountEntity) {

            $result[] = [
                SpyDiscountTableMap::COL_ID_DISCOUNT => $discountEntity->getIdDiscount(),
                SpyDiscountTableMap::COL_DISPLAY_NAME => $discountEntity->getDisplayName(),
                SpyDiscountTableMap::COL_AMOUNT => $discountEntity->getAmount(),
                'Type' => 'Type',
                'Period' => $discountEntity->getValidFrom(self::DATE_FORMAT) . ' - ' . $discountEntity->getValidTo(self::DATE_FORMAT),
                SpyDiscountTableMap::COL_IS_ACTIVE => $this->getStatus($discountEntity),
                SpyDiscountTableMap::COL_IS_EXCLUSIVE => $discountEntity->getIsExclusive(),
                self::TABLE_COL_ACTIONS => $this->getActionButtons($discountEntity),
            ];
        }

        return $result;
    }

    /**
     * @param \Orm\Zed\Discount\Persistence\SpyDiscount $discount
     *
     * @return string
     */
    protected function getDiscountPrice(SpyDiscount $discount)
    {
        $amount = $discount->getAmount();
        $amountType = $this->getDiscountAmountType($discount);

        return $amount . ' ' . $amountType;
    }

    /**
     * @param \Orm\Zed\Discount\Persistence\SpyDiscount $discount
     *
     * @return string
     */
    protected function getDiscountAmountType(SpyDiscount $discount)
    {
        if ($discount->getCalculatorPlugin() === DiscountDependencyProvider::PLUGIN_CALCULATOR_PERCENTAGE) {
            return 'percentage';
        }

        return 'fixed';
    }

    /**
     * @param \Orm\Zed\Discount\Persistence\SpyDiscount $discountEntity
     *
     * @return string
     */
    protected function getActionButtons(SpyDiscount $discountEntity)
    {
        $editDiscountUrl = Url::generate(
            '/discount/index/edit',
            [
                self::URL_PARAM_ID_DISCOUNT => $discountEntity->getIdDiscount()
            ]
        );

        $buttons[] = $this->generateEditButton($editDiscountUrl, 'Edit');

        $viewDiscountUrl = Url::generate(
            '/discount/index/view',
            [
                self::URL_PARAM_ID_DISCOUNT => $discountEntity->getIdDiscount()
            ]
        );

        $buttons[] = $this->generateViewButton($viewDiscountUrl, 'View');

        $visibility = 'activate';
        if ($discountEntity->getIsActive()) {
            $visibility = 'deactivate';
        }

        $viewDiscountUrl = Url::generate(
            '/discount/index/toggle-discount-visibility',
            [
                self::URL_PARAM_ID_DISCOUNT => $discountEntity->getIdDiscount(),
                self::URL_PARAM_VISIBILITY => $visibility,
                self::URL_PARAM_REDIRECT_URL => '/discount/index/list'
            ]
        );

        $buttons[] = $this->generateViewButton($viewDiscountUrl, $visibility);


        return implode(' ', $buttons);
    }

    /**
     * @param SpyDiscount $discountEntity
     *
     * @return string
     */
    protected function getStatus(SpyDiscount $discountEntity)
    {
         return $discountEntity->getIsActive() ? 'Active' : 'Inactive';
    }

}
