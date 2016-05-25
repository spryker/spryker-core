<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Communication\Table;

use Orm\Zed\Discount\Persistence\Map\SpyDiscountTableMap;
use Orm\Zed\Discount\Persistence\SpyDiscount;
use Orm\Zed\Discount\Persistence\SpyDiscountQuery;
use Spryker\Shared\Discount\DiscountConstants;
use Spryker\Shared\Url\Url;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

class DiscountsTable extends AbstractTable
{

    const TABLE_COL_PERIOD = self::TYPE_COL_PERIOD;
    const TABLE_COL_TYPE = 'Type';
    const TYPE_COL_PERIOD = 'Period';
    const TABLE_COL_ACTIONS = 'Actions';

    const URL_PARAM_ID_DISCOUNT = 'id-discount';
    const URL_PARAM_VISIBILITY = 'visibility';
    const URL_PARAM_REDIRECT_URL = 'redirect-url';

    const DATE_FORMAT = 'Y-m-d';

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
            self::TABLE_COL_TYPE => self::TABLE_COL_TYPE,
            self::TYPE_COL_PERIOD => self::TABLE_COL_PERIOD,
            SpyDiscountTableMap::COL_IS_ACTIVE => 'Status',
            SpyDiscountTableMap::COL_IS_EXCLUSIVE => 'Exclusive',
            self::TABLE_COL_ACTIONS => self::TABLE_COL_ACTIONS
        ]);

        $config->setSearchable([
            SpyDiscountTableMap::COL_DISPLAY_NAME
        ]);

        $config->setDefaultSortColumnIndex(0);
        $config->setDefaultSortDirection(TableConfiguration::SORT_DESC);

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

        $queryResult = $this->runQuery($this->discountQuery, $config, true);

        /** @var \Orm\Zed\Discount\Persistence\SpyDiscount $discountEntity */
        foreach ($queryResult as $discountEntity) {

            $result[] = [
                SpyDiscountTableMap::COL_ID_DISCOUNT => $discountEntity->getIdDiscount(),
                SpyDiscountTableMap::COL_DISPLAY_NAME => $discountEntity->getDisplayName(),
                SpyDiscountTableMap::COL_AMOUNT => $discountEntity->getAmount(),
                self::TABLE_COL_TYPE => $this->getDiscountType($discountEntity),
                self::TYPE_COL_PERIOD => $this->createTimePeriod($discountEntity),
                SpyDiscountTableMap::COL_IS_ACTIVE => $this->getStatus($discountEntity),
                SpyDiscountTableMap::COL_IS_EXCLUSIVE => $discountEntity->getIsExclusive(),
                self::TABLE_COL_ACTIONS => $this->getActionButtons($discountEntity),
            ];
        }

        return $result;
    }

    /**
     * @param \Orm\Zed\Discount\Persistence\SpyDiscount $discountEntity
     *
     * @return string
     */
    protected function getActionButtons(SpyDiscount $discountEntity)
    {
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
        $discountType = DiscountConstants::TYPE_CART_RULE;
        if ($discountEntity->getFkDiscountVoucherPool()) {
            $discountType = DiscountConstants::TYPE_VOUCHER;
        }

        return str_replace('_', ' ', $discountType);
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
                self::URL_PARAM_ID_DISCOUNT => $discountEntity->getIdDiscount()
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
                self::URL_PARAM_ID_DISCOUNT => $discountEntity->getIdDiscount()
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
        if ($discountEntity->getFkDiscountVoucherPool()) {
            $addVoucherCodeDiscountUrl = Url::generate(
                '/discount/index/edit',
                [
                    self::URL_PARAM_ID_DISCOUNT => $discountEntity->getIdDiscount(),
                ]
            );

            return $this->generateCreateButton($addVoucherCodeDiscountUrl, 'Add code');
        }

        return '';
    }

    /**
     * @param \Orm\Zed\Discount\Persistence\SpyDiscount $discountEntity
     *
     * @return string
     */
    protected function createToggleDiscountVisibilityButton(SpyDiscount $discountEntity)
    {
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

        return $this->generateViewButton($viewDiscountUrl, $visibility);
    }

    /**
     * @param \Orm\Zed\Discount\Persistence\SpyDiscount $discountEntity
     *
     * @return string
     */
    protected function createTimePeriod(SpyDiscount $discountEntity)
    {
        return $discountEntity->getValidFrom(self::DATE_FORMAT) . ' - ' . $discountEntity->getValidTo(self::DATE_FORMAT);
    }

}
