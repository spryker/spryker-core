<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductTable\Filter;

use Generated\Shared\Transfer\GuiTableFilterTransfer;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\Filter\SelectTableFilterInterface;

class HasOffersProductTableFilter implements SelectTableFilterInterface
{
    public const FILTER_NAME = 'offers';

    /**
     * @return \Generated\Shared\Transfer\GuiTableFilterTransfer
     */
    public function getFilter(): GuiTableFilterTransfer
    {
        return (new GuiTableFilterTransfer())
            ->setId(static::FILTER_NAME)
            ->setTitle('Offers')
            ->setType(static::FILTER_TYPE)
            ->addTypeOption(static::OPTION_VALUES, $this->getIsActiveValues());
    }

    /**
     * @return array
     */
    protected function getIsActiveValues(): array
    {
        return [
            [
                static::OPTION_VALUE_KEY_TITLE => 'With Offers',
                static::OPTION_VALUE_KEY_VALUE => 1,
            ],
            [
                static::OPTION_VALUE_KEY_TITLE => 'Without Offers',
                static::OPTION_VALUE_KEY_VALUE => 0,
            ],
        ];
    }
}
