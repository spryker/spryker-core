<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductOfferTable\Filter;

use Generated\Shared\Transfer\GuiTableFilterTransfer;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\Filter\TableFilterDataProviderInterface;

class ValidityProductOfferTableFilterDataProvider implements TableFilterDataProviderInterface
{
    protected const FILTER_NAME = 'validity';

    protected const OPTION_PLACEHOLDER_VALID_FROM = 'Valid from';
    protected const OPTION_PLACEHOLDER_VALID_TO = 'Valid to';

    /**
     * @return \Generated\Shared\Transfer\GuiTableFilterTransfer
     */
    public function getFilterData(): GuiTableFilterTransfer
    {
        return (new GuiTableFilterTransfer())
            ->setId(static::FILTER_NAME)
            ->setTitle('Validity')
            ->setType('date-range')
            ->addTypeOption(static::OPTION_NAME_PLACEHOLDER_FROM, static::OPTION_PLACEHOLDER_VALID_FROM)
            ->addTypeOption(static::OPTION_NAME_PLACEHOLDER_TO, static::OPTION_PLACEHOLDER_VALID_TO);
    }
}
