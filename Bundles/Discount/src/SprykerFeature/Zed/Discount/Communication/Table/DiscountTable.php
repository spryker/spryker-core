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
    const COL_OPTIONS = 'options';
    const PARAM_ID_POOL = 'id-pool';

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
//            SpyDiscountVoucherTableMap::COL_ID_DISCOUNT_VOUCHER => 'Id',
//            SpyDiscountVoucherTableMap::COL_CREATED_AT => 'Created',
//            SpyDiscountVoucherTableMap::COL_IS_ACTIVE => 'Is active',

            SpyDiscountTableMap::COL_VALID_TO => 'valid until',

//            'voucher_name' => 'voucher name',
//            'voucher_pool' => 'voucher pool',
//            'category' => 'category',
//            'code' => 'code',
//            'value' => 'value',
//            'status' => 'status',
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
//            $editUrl = $this->getEditUrl($item);
            $results[] = [
                SpyDiscountTableMap::COL_CREATED_AT => $item[SpyDiscountTableMap::COL_CREATED_AT],
                SpyDiscountTableMap::COL_VALID_TO => $item[SpyDiscountTableMap::COL_VALID_TO],
                SpyDiscountTableMap::COL_DISPLAY_NAME => $item[SpyDiscountTableMap::COL_DISPLAY_NAME],
                SpyDiscountTableMap::COL_IS_ACTIVE => $item[SpyDiscountTableMap::COL_IS_ACTIVE],

                'voucher_pool' => $item['voucher_pool'],
                'voucher_pool_category' => $item['voucher_pool_category'],
                'code' => $item['code'],

//                self::COL_OPTIONS => sprintf(
//                    '<a href="%s" class="btn btn-sm btn-primary">Edit</a>',
//                    $editUrl
//                ),
            ];
//            echo '<pre>';
//            print_r($item);
//            print_r($results);
//            die;
        }

        return $results;
    }

    /**
     * @param array $item
     *
     * @return string
     */
    private function getEditUrl(array $item)
    {
        $editUrl = sprintf(
            self::URL_DISCOUNT_POOL_EDIT,
            self::PARAM_ID_POOL,
            $item[SpyDiscountVoucherPoolTableMap::COL_ID_DISCOUNT_VOUCHER_POOL]
        );

        return $editUrl;
    }

}
