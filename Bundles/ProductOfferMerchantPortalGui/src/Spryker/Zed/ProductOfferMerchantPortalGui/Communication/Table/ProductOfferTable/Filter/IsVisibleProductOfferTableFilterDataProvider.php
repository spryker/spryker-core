<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductOfferTable\Filter;

use Generated\Shared\Transfer\GuiTableFilterTransfer;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\Filter\TableFilterDataProviderInterface;

class IsVisibleProductOfferTableFilterDataProvider implements TableFilterDataProviderInterface
{
    protected const FILTER_NAME = 'visibility';

    protected const OPTION_NAME_ONLINE = 'Online';
    protected const OPTION_NAME_OFFLINE = 'Offline';

    /**
     * @return \Generated\Shared\Transfer\GuiTableFilterTransfer
     */
    public function getFilterData(): GuiTableFilterTransfer
    {
        return (new GuiTableFilterTransfer())
            ->setId(static::FILTER_NAME)
            ->setTitle('Visibility')
            ->setType('select')
            ->addTypeOption(static::OPTION_NAME_MULTISELECT, false)
            ->addTypeOption(static::OPTION_NAME_VALUES, $this->getIsVisibleOptions());
    }

    /**
     * @return array
     */
    protected function getIsVisibleOptions(): array
    {
        return [
            [
                static::OPTION_VALUE_KEY_TITLE => static::OPTION_NAME_ONLINE,
                static::OPTION_VALUE_KEY_VALUE => 1,
            ],
            [
                static::OPTION_VALUE_KEY_TITLE => static::OPTION_NAME_OFFLINE,
                static::OPTION_VALUE_KEY_VALUE => 0,
            ],
        ];
    }
}
