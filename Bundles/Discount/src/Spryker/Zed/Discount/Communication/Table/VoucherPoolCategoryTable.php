<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Communication\Table;

use Orm\Zed\Discount\Persistence\Map\SpyDiscountVoucherPoolCategoryTableMap;
use Orm\Zed\Discount\Persistence\SpyDiscountVoucherPoolCategoryQuery;
use Spryker\Shared\Url\Url;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

class VoucherPoolCategoryTable extends AbstractTable
{

    const URL_DISCOUNT_POOL_EDIT_CATEGORY = '/discount/pool/edit-category';

    /**
     * @var \Orm\Zed\Discount\Persistence\SpyDiscountVoucherPoolCategoryQuery
     */
    protected $categoriesQuery;

    /**
     * @param \Orm\Zed\Discount\Persistence\SpyDiscountVoucherPoolCategoryQuery $discountVoucherPoolCategory
     */
    public function __construct(SpyDiscountVoucherPoolCategoryQuery $discountVoucherPoolCategory)
    {
        $this->categoriesQuery = $discountVoucherPoolCategory;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return mixed
     */
    protected function configure(TableConfiguration $config)
    {
        $config->setHeader([
            SpyDiscountVoucherPoolCategoryTableMap::COL_NAME => 'Name',
            'options' => 'Options',
        ]);

        $config->setSearchable([
            SpyDiscountVoucherPoolCategoryTableMap::COL_NAME,
        ]);

        $config->setUrl('categories-table');

        return $config;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array
     */
    protected function prepareData(TableConfiguration $config)
    {
        $results = [];

        $queryResults = $this->runQuery($this->categoriesQuery, $config);

        foreach ($queryResults as $item) {
            $results[] = [
                SpyDiscountVoucherPoolCategoryTableMap::COL_NAME => $item[SpyDiscountVoucherPoolCategoryTableMap::COL_NAME],
                'options' => implode(' ', $this->getOptionsUrls($item)),
            ];
        }

        return $results;
    }

    /**
     * @param array $item
     *
     * @return array
     */
    protected function getOptionsUrls(array $item)
    {
        $options = [];

        $options[] = $this->generateEditButton(
            Url::generate(self::URL_DISCOUNT_POOL_EDIT_CATEGORY, [
                'id' => $item[SpyDiscountVoucherPoolCategoryTableMap::COL_ID_DISCOUNT_VOUCHER_POOL_CATEGORY],
            ]),
            'Edit'
        );

        return $options;
    }

}
