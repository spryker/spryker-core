<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Discount\Communication\Grid;

use SprykerFeature\Zed\Ui\Dependency\Grid\AbstractGrid;

class VoucherGrid extends AbstractGrid
{

    const VOUCHER_POOL = 'voucher_pool';
    const IS_ACTIVE = 'is_active';
    const CODE = 'code';

    /**
     * @return array
     */
    public function definePlugins()
    {
        $plugins = [
            $this->createDefaultRowRenderer(),
            $this->createPagination(),

            $this->createDefaultColumn()
                ->setName(self::VOUCHER_POOL)
                ->filterable()
                ->sortable(),
            $this->createDefaultColumn()
                ->setName(self::CODE)
                ->filterable()
                ->sortable(),
            $this->createDefaultColumn()
                ->setName(self::VOUCHER_POOL)
                ->filterable()
                ->sortable(),
            $this->createBooleanColumn()
                ->setName(self::IS_ACTIVE)
                ->filterable()
                ->sortable(),
        ];

        return $plugins;
    }

}
