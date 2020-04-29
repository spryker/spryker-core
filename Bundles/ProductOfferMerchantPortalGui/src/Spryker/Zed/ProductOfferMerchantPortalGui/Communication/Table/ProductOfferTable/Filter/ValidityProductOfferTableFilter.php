<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductOfferTable\Filter;

use Generated\Shared\Transfer\GuiTableFilterTransfer;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\Filter\DateRangeTableFilterInterface;

class ValidityProductOfferTableFilter implements DateRangeTableFilterInterface
{
    public const FILTER_NAME = 'validity';

    /**
     * @return \Generated\Shared\Transfer\GuiTableFilterTransfer
     */
    public function getFilter(): GuiTableFilterTransfer
    {
        return (new GuiTableFilterTransfer())
            ->setId(static::FILTER_NAME)
            ->setTitle('Validity')
            ->setType(static::FILTER_TYPE);
    }
}
