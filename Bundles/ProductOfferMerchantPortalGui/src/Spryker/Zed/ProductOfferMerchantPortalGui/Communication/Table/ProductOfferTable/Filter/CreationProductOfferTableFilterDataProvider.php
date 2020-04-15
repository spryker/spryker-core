<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductOfferTable\Filter;

use Generated\Shared\Transfer\GuiTableFilterTransfer;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\Filter\TableFilterDataProviderInterface;

class CreationProductOfferTableFilterDataProvider implements TableFilterDataProviderInterface
{
    protected const FILTER_NAME = 'creation';

    protected const OPTION_PLACEHOLDER_CREATED_FROM = 'Created from';
    protected const OPTION_PLACEHOLDER_CREATED_TO = 'Created to';

    /**
     * @return \Generated\Shared\Transfer\GuiTableFilterTransfer
     */
    public function getFilterData(): GuiTableFilterTransfer
    {
        return (new GuiTableFilterTransfer())
            ->setId(static::FILTER_NAME)
            ->setTitle('Created')
            ->setType('date-range')
            ->addTypeOption(static::OPTION_NAME_PLACEHOLDER_FROM, static::OPTION_PLACEHOLDER_CREATED_FROM)
            ->addTypeOption(static::OPTION_NAME_PLACEHOLDER_TO, static::OPTION_PLACEHOLDER_CREATED_TO);
    }
}
