<?php

namespace SprykerFeature\Zed\Discount\Communication\Table;

use SprykerFeature\Zed\Discount\Communication\Controller\PoolController;
use SprykerFeature\Zed\Discount\Persistence\Propel\Map\SpyDiscountVoucherPoolCategoryTableMap;
use SprykerFeature\Zed\Discount\Persistence\Propel\Map\SpyDiscountVoucherPoolTableMap;
use SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscountVoucherPool;
use SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscountVoucherPoolCategoryQuery;
use SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscountVoucherPoolQuery;
use SprykerFeature\Zed\Gui\Communication\Table\AbstractTable;
use SprykerFeature\Zed\Gui\Communication\Table\TableConfiguration;

class VoucherPoolTable extends AbstractTable
{
    const COL_OPTIONS = 'options';
    const URL_DISCOUNT_POOL_EDIT = '/discount/pool/edit?%s=%d';
    const PARAM_ID_POOL = 'id-pool';
    const CONTROLLER_TABLE_ACTION = 'poolTable';

    /**
     * @var SpyDiscountVoucherPoolQuery
     */
    protected $poolQuery;

    /**
     * @param SpyDiscountVoucherPoolQuery $discountVoucherPool
     */
    public function __construct(SpyDiscountVoucherPoolQuery $discountVoucherPool)
    {
        $this->poolQuery = $discountVoucherPool;
    }

    /**
     * @param TableConfiguration $config
     *
     * @return TableConfiguration
     */
    protected function configure(TableConfiguration $config)
    {
        $config->setUrl(self::CONTROLLER_TABLE_ACTION);

        $config->setHeader([
            'date_created' => 'date created',
            'valid_until' => 'valid until',
            'voucher_name' => 'voucher name',
            'voucher_pool' => 'voucher pool',
            'category' => 'category',
            'code' => 'code',
            'value' => 'value',
            'status' => 'status',
//            SpyDiscountVoucherPoolTableMap::COL_ID_DISCOUNT_VOUCHER_POOL => 'Id',
//            SpyDiscountVoucherPoolTableMap::COL_NAME => 'Name',
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

        $queryResults = $this->runQuery($this->poolQuery, $config);

        foreach ($queryResults as $item) {
            $editUrl = $this->getEditUrl($item);
            $results[] = [
//                SpyDiscountVoucherPoolTableMap::COL_ID_DISCOUNT_VOUCHER_POOL => $item[SpyDiscountVoucherPoolTableMap::COL_ID_DISCOUNT_VOUCHER_POOL],
//                SpyDiscountVoucherPoolTableMap::COL_NAME => $item[SpyDiscountVoucherPoolTableMap::COL_NAME],
                'date_created' => 'date created',
                'valid_until' => 'valid until',
                'voucher_name' => 'voucher name',
                'voucher_pool' => 'voucher pool',
                'category' => 'category',
                'code' => 'code',
                'value' => 'value',
                'status' => 'status',
                self::COL_OPTIONS => sprintf(
                    '<a href="%s" class="btn btn-sm btn-primary">Edit</a>',
                    $editUrl
                ),
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
