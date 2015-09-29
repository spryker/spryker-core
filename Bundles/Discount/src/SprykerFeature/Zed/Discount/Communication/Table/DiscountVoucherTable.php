<?php

namespace SprykerFeature\Zed\Discount\Communication\Table;

use SprykerFeature\Zed\Discount\Persistence\Propel\Map\SpyDiscountTableMap;
use SprykerFeature\Zed\Discount\Persistence\Propel\Map\SpyDiscountVoucherPoolCategoryTableMap;
use SprykerFeature\Zed\Discount\Persistence\Propel\Map\SpyDiscountVoucherPoolTableMap;
use SprykerFeature\Zed\Discount\Persistence\Propel\Map\SpyDiscountVoucherTableMap;
use SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscountVoucherQuery;
use SprykerFeature\Zed\Gui\Communication\Table\AbstractTable;
use SprykerFeature\Zed\Gui\Communication\Table\TableConfiguration;

class DiscountVoucherTable extends AbstractTable
{
    const URL_DISCOUNT_EDIT = '/discount/voucher/edit?id-discount=%d';
    const COL_ACTIVE = 'active';
    const COL_VOUCHER_POOL = 'voucher_pool';
    const COL_VOUCHER_POOL_CATEGORY = 'voucher_pool_category';
    const COL_DISCOUNT_AMOUNT = 'amount';
    const COL_DISCOUNT_AMOUNT_TYPE = 'amount_type';
    const COL_DISCOUNT_NAME = 'display_name';
    const COL_DISCOUNT_VALID_TO = 'valid_to';

    /**
     * @var SpyDiscountQuery
     */
    protected $discountVoucherQuery;

    /**
     * @param SpyDiscountVoucherQuery $discountQuery
     */
    public function __construct(SpyDiscountVoucherQuery $discountQuery)
    {
        $this->discountVoucherQuery = $discountQuery;
    }

    /**
     * @param TableConfiguration $config
     *
     * @return TableConfiguration
     */
    protected function configure(TableConfiguration $config)
    {
        $config->setHeader([
            SpyDiscountVoucherTableMap::COL_CREATED_AT => 'Created At',
            SpyDiscountTableMap::COL_VALID_TO => 'Valid To',
            self::COL_VOUCHER_POOL => 'Voucher Pool',
            self::COL_VOUCHER_POOL_CATEGORY => 'Category',
            SpyDiscountVoucherTableMap::COL_CODE => 'Code',
            SpyDiscountTableMap::COL_AMOUNT => 'Value',
            SpyDiscountTableMap::COL_TYPE => 'Type',
            self::COL_ACTIVE => 'Active',
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
        $results = [];

        $query = $this->discountVoucherQuery
            ->withColumn(SpyDiscountVoucherPoolTableMap::COL_NAME, self::COL_VOUCHER_POOL)
            ->withColumn(SpyDiscountVoucherPoolCategoryTableMap::COL_NAME, self::COL_VOUCHER_POOL_CATEGORY)
            ->withColumn(SpyDiscountTableMap::COL_AMOUNT, self::COL_DISCOUNT_AMOUNT)
            ->withColumn(SpyDiscountTableMap::COL_TYPE, self::COL_DISCOUNT_AMOUNT_TYPE)
            ->withColumn(SpyDiscountTableMap::COL_DISPLAY_NAME, self::COL_DISCOUNT_NAME)
            ->withColumn(SpyDiscountTableMap::COL_VALID_TO, self::COL_DISCOUNT_VALID_TO)
            ->useVoucherPoolQuery()
                ->useDiscountQuery()
                ->endUse()
                ->useVoucherPoolCategoryQuery()
                ->endUse()
            ->endUse()
            ->groupByIdDiscountVoucher()
        ;

        $queryResults = $this->runQuery($query, $config);

        foreach ($queryResults as $item) {

            $results[] = [
                SpyDiscountVoucherTableMap::COL_CREATED_AT => $item[SpyDiscountVoucherTableMap::COL_CREATED_AT],
                SpyDiscountTableMap::COL_VALID_TO => $item[self::COL_DISCOUNT_VALID_TO],
                SpyDiscountTableMap::COL_DISPLAY_NAME => $item[self::COL_DISCOUNT_NAME],
                SpyDiscountTableMap::COL_AMOUNT => $item[self::COL_DISCOUNT_AMOUNT],
                SpyDiscountTableMap::COL_TYPE => SpyDiscountTableMap::getValueSet(SpyDiscountTableMap::COL_TYPE)[$item[self::COL_DISCOUNT_AMOUNT_TYPE]],
                self::COL_VOUCHER_POOL => $item[self::COL_VOUCHER_POOL],
                self::COL_VOUCHER_POOL_CATEGORY => $item[self::COL_VOUCHER_POOL_CATEGORY],
                SpyDiscountVoucherTableMap::COL_CODE => $item[SpyDiscountVoucherTableMap::COL_CODE],
                self::COL_ACTIVE => $this->writeActiveCheckbox($item),
            ];
        }

        return $results;
    }

    /**
     * @param array $item
     *
     * @return string
     */
    private function writeActiveCheckbox($item)
    {
        $input = sprintf(
            '<input type="checkbox" %s name="activate[]" value="on" class="active-checkbox" id="active-%d" />',
            ($item[SpyDiscountVoucherTableMap::COL_IS_ACTIVE]) ? 'checked="checked"' : '',
            $item[SpyDiscountVoucherTableMap::COL_ID_DISCOUNT_VOUCHER],
            $item[SpyDiscountVoucherTableMap::COL_ID_DISCOUNT_VOUCHER]
        );

        return $input;
    }

}
