<?php

namespace SprykerFeature\Zed\Discount\Communication\Table;

use SprykerFeature\Zed\Discount\Persistence\Propel\Map\SpyDiscountTableMap;
use SprykerFeature\Zed\Discount\Persistence\Propel\Map\SpyDiscountVoucherPoolCategoryTableMap;
use SprykerFeature\Zed\Discount\Persistence\Propel\Map\SpyDiscountVoucherPoolTableMap;
use SprykerFeature\Zed\Discount\Persistence\Propel\Map\SpyDiscountVoucherTableMap;
use SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscountQuery;
use SprykerFeature\Zed\Gui\Communication\Table\AbstractTable;
use SprykerFeature\Zed\Gui\Communication\Table\TableConfiguration;

class DiscountTable extends AbstractTable
{
    const URL_DISCOUNT_EDIT = '/discount/voucher/edit?id-discount=%d';
    const COL_OPTIONS = 'options';
    const COL_VOUCHER_POOL = 'voucher_pool';
    const COL_VOUCHER_POOL_CATEGORY = 'voucher_pool_category';
    const COL_CODE = 'code';

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
            SpyDiscountTableMap::COL_CREATED_AT => 'Created At',
            SpyDiscountTableMap::COL_VALID_TO => 'Valid To',
            SpyDiscountTableMap::COL_DISPLAY_NAME => 'Discount Name',
            self::COL_VOUCHER_POOL => 'Pool Name',
            self::COL_VOUCHER_POOL_CATEGORY => 'Pool Category',
            self::COL_CODE => 'Code',
            SpyDiscountTableMap::COL_AMOUNT => 'Amount',
            self::COL_OPTIONS => 'Options',
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

        $query = $this->discountQuery
            ->withColumn(SpyDiscountVoucherPoolTableMap::COL_NAME, 'voucher_pool')
            ->withColumn(SpyDiscountVoucherPoolCategoryTableMap::COL_NAME, 'voucher_pool_category')
            ->withColumn(SpyDiscountVoucherTableMap::COL_CODE, 'code')
            ->useVoucherPoolQuery()
                ->useDiscountVoucherQuery()
                ->endUse()
                ->useVoucherPoolCategoryQuery()
                ->endUse()
            ->endUse()
        ;

        $queryResults = $this->runQuery($query, $config);

        foreach ($queryResults as $item) {
            $results[] = [
                SpyDiscountTableMap::COL_CREATED_AT => $item[SpyDiscountTableMap::COL_CREATED_AT],
                SpyDiscountTableMap::COL_VALID_TO => $item[SpyDiscountTableMap::COL_VALID_TO],
                SpyDiscountTableMap::COL_DISPLAY_NAME => $item[SpyDiscountTableMap::COL_DISPLAY_NAME],
                SpyDiscountTableMap::COL_AMOUNT => $item[SpyDiscountTableMap::COL_AMOUNT],
                self::COL_VOUCHER_POOL => $item['voucher_pool'],
                self::COL_VOUCHER_POOL_CATEGORY => $item['voucher_pool_category'],
                self::COL_CODE => $item['code'],
                self::COL_OPTIONS => $this->writeActiveCheckbox($item),
            ];
        }

        return $results;
    }

    private function writeActiveCheckbox($item)
    {
        $input = sprintf(
            '<label><input type="checkbox" %s name="%d" value="on" id="active-%d" /> Active</label>',
            ($item[SpyDiscountTableMap::COL_IS_ACTIVE]) ? 'checked="checked"' : '',
            $item[SpyDiscountTableMap::COL_ID_DISCOUNT],
            $item[SpyDiscountTableMap::COL_ID_DISCOUNT]
        );

        return $input;
    }

}
