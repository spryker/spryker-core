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
    const COL_CATEGORY_NAME = 'category_name';
    const COL_VOUCHERS_COUNT = 'Vouchers';

    const URL_DISCOUNT_POOL_EDIT = '/discount/pool/edit?%s=%d';
    const PARAM_ID_POOL = 'id-pool';
    const CONTROLLER_TABLE_ACTION = 'poolTable';

    const SPACE_SEPARATOR = ' ';


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
            SpyDiscountVoucherPoolTableMap::COL_CREATED_AT => 'Date Created',
            SpyDiscountVoucherPoolTableMap::COL_NAME => 'Pool Name',
            self::COL_CATEGORY_NAME => 'Category Name',
            self::COL_VOUCHERS_COUNT => 'Vouchers',
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

        $query = $this->poolQuery
            ->withColumn(SpyDiscountVoucherPoolCategoryTableMap::COL_NAME, 'category_name')
            ->useVoucherPoolCategoryQuery()
            ->endUse()
            ->useDiscountVoucherQuery()
                ->withColumn('COUNT(' . SpyDiscountVoucherPoolTableMap::COL_ID_DISCOUNT_VOUCHER_POOL . ')', self::COL_VOUCHERS_COUNT)
            ->endUse()
            ->groupByIdDiscountVoucherPool()
        ;


        $queryResults = $this->runQuery($query, $config);

        foreach ($queryResults as $item) {

            $results[] = [
                SpyDiscountVoucherPoolTableMap::COL_CREATED_AT => $item[SpyDiscountVoucherPoolTableMap::COL_CREATED_AT],
                SpyDiscountVoucherPoolTableMap::COL_NAME => $item[SpyDiscountVoucherPoolTableMap::COL_NAME],
                self::COL_CATEGORY_NAME => $item[self::COL_CATEGORY_NAME],
                self::COL_VOUCHERS_COUNT => $item[self::COL_VOUCHERS_COUNT],
                self::COL_OPTIONS => $this->createRowOptions($item),
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
        return sprintf(
            self::URL_DISCOUNT_POOL_EDIT,
            self::PARAM_ID_POOL,
            $item[SpyDiscountVoucherPoolTableMap::COL_ID_DISCOUNT_VOUCHER_POOL]
        );
    }

    /**
     * @param array $item
     *
     * @return string
     */
    protected function createRowOptions(array $item)
    {
        $editUrl = $this->getEditUrl($item);

        return $this->generateEditButton($editUrl, 'Edit Voucher')
            . self::SPACE_SEPARATOR
            . $this->generateViewButton('/discount/voucher/view/?' . self::PARAM_ID_POOL . '=' . $item[SpyDiscountVoucherPoolTableMap::COL_ID_DISCOUNT_VOUCHER_POOL], 'View Codes')
            . self::SPACE_SEPARATOR
            . $this->generateCreateButton('/discount/voucher/create-single', 'Add Single Voucher')
            . self::SPACE_SEPARATOR
            . $this->generateCreateButton('/discount/voucher/create-multiple', 'Add Multiple Vouchers')
        ;
    }

}
