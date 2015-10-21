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

    const DATE_FORMAT = 'Y-m-d';
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
            SpyDiscountVoucherPoolTableMap::COL_NAME => 'Voucher Name',
            self::COL_CATEGORY_NAME => 'Category Name',
            self::COL_VOUCHERS_COUNT => 'Codes',
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
            ->withColumn('COUNT(' . SpyDiscountVoucherPoolTableMap::COL_ID_DISCOUNT_VOUCHER_POOL . ')', self::COL_VOUCHERS_COUNT)
            ->useDiscountVoucherQuery()
            ->endUse()
            ->useVoucherPoolCategoryQuery()
            ->endUse()
            ->groupByIdDiscountVoucherPool()
        ;


        $queryResults = $this->runQuery($query, $config, true);

        /** @var SpyDiscountVoucherPool $discountVoucherPool */
        foreach ($queryResults as $discountVoucherPool) {

            $results[] = [
                SpyDiscountVoucherPoolTableMap::COL_CREATED_AT => $discountVoucherPool->getCreatedAt(self::DATE_FORMAT),
                SpyDiscountVoucherPoolTableMap::COL_NAME => $discountVoucherPool->getName(),
                self::COL_CATEGORY_NAME => $discountVoucherPool->getVoucherPoolCategory()->getName(),
                self::COL_VOUCHERS_COUNT => $discountVoucherPool->getDiscountVouchers()->count(),
                self::COL_OPTIONS => $this->createRowOptions($discountVoucherPool),
            ];
        }

        return $results;
    }

    /**
     * @param SpyDiscountVoucherPool $discountVoucherPool
     *
     * @return string
     */
    private function getEditUrl(SpyDiscountVoucherPool $discountVoucherPool)
    {
        return sprintf(
            self::URL_DISCOUNT_POOL_EDIT,
            self::PARAM_ID_POOL,
            $discountVoucherPool->getIdDiscountVoucherPool()
        );
    }

    /**
     * @param SpyDiscountVoucherPool $discountVoucherPool
     *
     * @return string
     */
    protected function createRowOptions(SpyDiscountVoucherPool $discountVoucherPool)
    {
        $editUrl = $this->getEditUrl($discountVoucherPool);

        return $this->generateEditButton($editUrl, 'Edit Voucher')
            . self::SPACE_SEPARATOR
            . $this->generateViewButton('/discount/voucher/view/?' . self::PARAM_ID_POOL . '=' . $discountVoucherPool->getIdDiscountVoucherPool(), 'View Codes')
            . self::SPACE_SEPARATOR
            . $this->generateCreateButton('/discount/voucher/create-single', 'Add Single Code')
            . self::SPACE_SEPARATOR
            . $this->generateCreateButton('/discount/voucher/create-multiple', 'Add Multiple Codes')
        ;
    }

}
