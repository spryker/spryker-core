<?php

namespace SprykerFeature\Zed\Discount\Communication\Table;

use SprykerFeature\Zed\Discount\Persistence\Propel\Map\SpyDiscountTableMap;
use SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscountQuery;
use SprykerFeature\Zed\Gui\Communication\Table\AbstractTable;
use SprykerFeature\Zed\Gui\Communication\Table\TableConfiguration;

class DiscountsTable extends AbstractTable
{

    const COL_VALUE = 'Value';
    const COL_PERIOD = 'Period';
    const COL_OPTIONS = 'Options';

    const PARAM_ID_DISCOUNT = 'id-discount';

    /**
     * @var SpyDiscountQuery
     */
    protected $discountQuery;

    /**
     * @param SpyDiscountQuery $discountQuery
     */
    public function __construct(SpyDiscountQuery $discountQuery)
    {
        $this->discountQuery = $discountQuery;
    }

    /**
     * @param TableConfiguration $config
     *
     * @return TableConfiguration
     */
    protected function configure(TableConfiguration $config)
    {
        $config->setHeader([
            SpyDiscountTableMap::COL_ID_DISCOUNT => 'ID',
            SpyDiscountTableMap::COL_DISPLAY_NAME => 'Display Name',
            SpyDiscountTableMap::COL_DESCRIPTION => 'Description',
            self::COL_VALUE => self::COL_VALUE,
            SpyDiscountTableMap::COL_IS_PRIVILEGED => 'Is Privileged',
            SpyDiscountTableMap::COL_IS_ACTIVE => 'Is Active',
            self::COL_PERIOD => self::COL_PERIOD,
            self::COL_OPTIONS => self::COL_OPTIONS,
        ]);

        return $config;
    }

    /**
     * @param TableConfiguration $config
     *
     * @return array
     */
    protected function prepareData(TableConfiguration $config)
    {
        $result = [];

        $query = $this->discountQuery
            ->where('fk_discount_voucher_pool IS NULL')
        ;

        $queryResult = $this->runQuery($query, $config);
        foreach ($queryResult as $item) {
            $result[] = [
                SpyDiscountTableMap::COL_ID_DISCOUNT => $item[SpyDiscountTableMap::COL_ID_DISCOUNT],
                SpyDiscountTableMap::COL_DISPLAY_NAME => $item[SpyDiscountTableMap::COL_DISPLAY_NAME],
                SpyDiscountTableMap::COL_DESCRIPTION => $item[SpyDiscountTableMap::COL_DESCRIPTION],
                self::COL_VALUE => $item[SpyDiscountTableMap::COL_AMOUNT] . ' ' . $item[SpyDiscountTableMap::COL_TYPE],
                SpyDiscountTableMap::COL_IS_PRIVILEGED => $item[SpyDiscountTableMap::COL_IS_PRIVILEGED],
                SpyDiscountTableMap::COL_IS_ACTIVE => $item[SpyDiscountTableMap::COL_IS_ACTIVE],
                self::COL_PERIOD => $item[SpyDiscountTableMap::COL_VALID_FROM] . ' - ' . $item[SpyDiscountTableMap::COL_VALID_TO],
                self::COL_OPTIONS => $this->getRowOptions($item),
            ];
        }

        return $result;
    }

    /**
     * @param array $item
     *
     * @return string
     */
    protected function getRowOptions(array $item)
    {
        return '<a class="btn btn-xs btn-info" href="/discount/cart-rule/edit?' . self::PARAM_ID_DISCOUNT . '=' . $item[SpyDiscountTableMap::COL_ID_DISCOUNT] . '">Edit</a>';
    }

}
