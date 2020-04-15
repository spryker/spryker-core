<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductOfferTable\Filter;

use Generated\Shared\Transfer\GuiTableFilterTransfer;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\Filter\TableFilterDataProviderInterface;

class StatusProductOfferTableFilterDataProvider implements TableFilterDataProviderInterface
{
    protected const FILTER_NAME = 'status';

    protected const OPTION_APPROVED = 'Approved';
    protected const OPTION_DENIED = 'Denied';
    protected const OPTION_PENDING = 'Pending';

    /**
     * @return \Generated\Shared\Transfer\GuiTableFilterTransfer
     */
    public function getFilterData(): GuiTableFilterTransfer
    {
        return (new GuiTableFilterTransfer())
            ->setId(static::FILTER_NAME)
            ->setTitle('Status')
            ->setType('select')
            ->addTypeOption(static::OPTION_NAME_MULTISELECT, false)
            ->addTypeOption(static::OPTION_NAME_VALUES, $this->getStatusOptions());
    }

    /**
     * @return array
     */
    protected function getStatusOptions(): array
    {
        return [
            [
                static::OPTION_VALUE_KEY_TITLE => static::OPTION_APPROVED,
                static::OPTION_VALUE_KEY_VALUE => static::OPTION_APPROVED,
            ],
            [
                static::OPTION_VALUE_KEY_TITLE => static::OPTION_DENIED,
                static::OPTION_VALUE_KEY_VALUE => static::OPTION_DENIED,
            ],
            [
                static::OPTION_VALUE_KEY_TITLE => static::OPTION_PENDING,
                static::OPTION_VALUE_KEY_VALUE => static::OPTION_PENDING,
            ],
        ];
    }
}
