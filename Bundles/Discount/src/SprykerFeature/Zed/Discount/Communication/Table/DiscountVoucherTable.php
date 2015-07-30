<?php

namespace SprykerFeature\Zed\Discount\Communication\Table;

use SprykerFeature\Zed\Discount\Persistence\Propel\Map\SpyDiscountVoucherTableMap;
use SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscountVoucherQuery;
use SprykerFeature\Zed\Gui\Communication\Table\AbstractTable;
use SprykerFeature\Zed\Gui\Communication\Table\TableConfiguration;

class DiscountVoucherTable extends AbstractTable
{
    const COL_OPTIONS = 'options';
    const PARAM_ID_POOL = 'id-pool';

    /**
     * @var SpyDiscountVoucherPoolQuery
     */
    protected $voucherQuery;

    /**
     * @param SpyDiscountVoucherQuery $discountVoucher
     */
    public function __construct(SpyDiscountVoucherQuery $discountVoucher)
    {
        $this->voucherQuery = $discountVoucher;
    }

    /**
     * @param TableConfiguration $config
     *
     * @return TableConfiguration
     */
    protected function configure(TableConfiguration $config)
    {

        $config->setHeader([
            SpyDiscountVoucherTableMap::COL_ID_DISCOUNT_VOUCHER => 'Id',
            SpyDiscountVoucherTableMap::COL_CREATED_AT => 'Created',
            SpyDiscountVoucherTableMap::COL_IS_ACTIVE => 'Is active',
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

        $queryResults = $this->runQuery($this->voucherQuery, $config);

        foreach ($queryResults as $item) {
//            $editUrl = $this->getEditUrl($item);
            $results[] = [
                SpyDiscountVoucherTableMap::COL_ID_DISCOUNT_VOUCHER => $item[SpyDiscountVoucherTableMap::COL_ID_DISCOUNT_VOUCHER],
                SpyDiscountVoucherTableMap::COL_CREATED_AT => $item[SpyDiscountVoucherTableMap::COL_CREATED_AT],
                SpyDiscountVoucherTableMap::COL_IS_ACTIVE => $item[SpyDiscountVoucherTableMap::COL_IS_ACTIVE],

//                SpyDiscountVoucherPoolTableMap::COL_ID_DISCOUNT_VOUCHER_POOL => $item[SpyDiscountVoucherPoolTableMap::COL_ID_DISCOUNT_VOUCHER_POOL],
//                SpyDiscountVoucherPoolTableMap::COL_NAME => $item[SpyDiscountVoucherPoolTableMap::COL_NAME],

//                self::COL_OPTIONS => sprintf(
//                    '<a href="%s" class="btn btn-sm btn-primary">Edit</a>',
//                    $editUrl
//                ),
            ];
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
