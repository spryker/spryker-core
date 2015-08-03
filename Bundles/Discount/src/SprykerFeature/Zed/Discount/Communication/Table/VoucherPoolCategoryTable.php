<?php

namespace SprykerFeature\Zed\Discount\Communication\Table;

use SprykerFeature\Zed\Discount\Persistence\Propel\Map\SpyDiscountVoucherPoolCategoryTableMap;
use SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscountVoucherPoolCategoryQuery;
use SprykerFeature\Zed\Gui\Communication\Table\AbstractTable;
use SprykerFeature\Zed\Gui\Communication\Table\TableConfiguration;

class VoucherPoolCategoryTable extends AbstractTable
{
    protected $categoriesQuery;

    public function __construct(SpyDiscountVoucherPoolCategoryQuery $discountVoucherPoolCategory)
    {
        $this->categoriesQuery = $discountVoucherPoolCategory;
    }

    /**
     * @param TableConfiguration $config
     *
     * @return mixed
     */
    protected function configure(TableConfiguration $config)
    {
        $config->setHeader([
            SpyDiscountVoucherPoolCategoryTableMap::COL_NAME => 'Name',
            'options' => 'Options',
        ]);

        return $config;
    }

    /**
     * @param TableConfiguration $config
     *
     * @return mixed
     */
    protected function prepareData(TableConfiguration $config)
    {
        $results = [];

        $queryResults = $this->runQuery($this->categoriesQuery, $config);

        foreach ($queryResults as $item) {
            $results[] = [
                SpyDiscountVoucherPoolCategoryTableMap::COL_NAME => $item[SpyDiscountVoucherPoolCategoryTableMap::COL_NAME],
                'options' => sprintf(
                    '<a href="/discount/pool/edit-category?id=%d" class="btn btn-sm btn-primary">Edit</a>',
                    $item[SpyDiscountVoucherPoolCategoryTableMap::COL_ID_DISCOUNT_VOUCHER_POOL_CATEGORY]
                ),
            ];
        }


        return $results;
    }

}
