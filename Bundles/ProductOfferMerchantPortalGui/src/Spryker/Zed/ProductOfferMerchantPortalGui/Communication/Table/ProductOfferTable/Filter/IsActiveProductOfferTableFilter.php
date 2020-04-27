<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductOfferTable\Filter;

use Generated\Shared\Transfer\GuiTableFilterTransfer;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\Filter\SelectTableFilterInterface;

class IsActiveProductOfferTableFilter implements SelectTableFilterInterface
{
    public const FILTER_NAME = 'isActive';

    /**
     * @return \Generated\Shared\Transfer\GuiTableFilterTransfer
     */
    public function getFilter(): GuiTableFilterTransfer
    {
        return (new GuiTableFilterTransfer())
            ->setId(static::FILTER_NAME)
            ->setTitle('Visibility')
            ->setType(static::FILTER_TYPE)
            ->addTypeOption(static::OPTION_VALUES, $this->getIsVisibleOptions());
    }

    /**
     * @return array
     */
    protected function getIsVisibleOptions(): array
    {
        return [
            [
                static::OPTION_VALUE_KEY_TITLE => 'Online',
                static::OPTION_VALUE_KEY_VALUE => 1,
            ],
            [
                static::OPTION_VALUE_KEY_TITLE => 'Offline',
                static::OPTION_VALUE_KEY_VALUE => 0,
            ],
        ];
    }
}
