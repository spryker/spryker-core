<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductTable\Filter;

use Generated\Shared\Transfer\GuiTableFilterTransfer;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\Filter\TableFilterInterface;

class IsActiveProductTableFilter implements TableFilterInterface
{
    public const FILTER_NAME = 'status';

    protected const OPTION_NAME_ACTIVE = 'Active';
    protected const OPTION_NAME_INACTIVE = 'Inactive';

    /**
     * @return \Generated\Shared\Transfer\GuiTableFilterTransfer
     */
    public function getFilter(): GuiTableFilterTransfer
    {
        return (new GuiTableFilterTransfer())
            ->setId(static::FILTER_NAME)
            ->setTitle('Status')
            ->setType('select')
            ->addTypeOption(static::OPTION_NAME_MULTISELECT, false)
            ->addTypeOption(static::OPTION_NAME_VALUES, $this->getIsActiveOptions());
    }

    /**
     * @return array
     */
    protected function getIsActiveOptions(): array
    {
        return [
            [
                static::OPTION_VALUE_KEY_TITLE => static::OPTION_NAME_ACTIVE,
                static::OPTION_VALUE_KEY_VALUE => 1,
            ],
            [
                static::OPTION_VALUE_KEY_TITLE => static::OPTION_NAME_INACTIVE,
                static::OPTION_VALUE_KEY_VALUE => 0,
            ],
        ];
    }
}
