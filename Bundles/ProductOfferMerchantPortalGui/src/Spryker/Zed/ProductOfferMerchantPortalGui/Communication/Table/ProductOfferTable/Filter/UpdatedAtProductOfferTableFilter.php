<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductOfferTable\Filter;

use Generated\Shared\Transfer\GuiTableFilterTransfer;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\Filter\TableFilterInterface;

class UpdatedAtProductOfferTableFilter implements TableFilterInterface
{
    public const FILTER_NAME = 'updatedAt';

    protected const OPTION_PLACEHOLDER_UPDATED_FROM = 'Updated from';
    protected const OPTION_PLACEHOLDER_UPDATED_TO = 'Updated to';

    /**
     * @return \Generated\Shared\Transfer\GuiTableFilterTransfer
     */
    public function getFilter(): GuiTableFilterTransfer
    {
        return (new GuiTableFilterTransfer())
            ->setId(static::FILTER_NAME)
            ->setTitle('Updated')
            ->setType('date-range')
            ->addTypeOption(static::OPTION_NAME_PLACEHOLDER_FROM, static::OPTION_PLACEHOLDER_UPDATED_FROM)
            ->addTypeOption(static::OPTION_NAME_PLACEHOLDER_TO, static::OPTION_PLACEHOLDER_UPDATED_TO);
    }
}
