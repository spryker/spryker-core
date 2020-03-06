<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferGuiPage\Communication\Table\ProductListTable\Filter;

use Generated\Shared\Transfer\TableFilterTransfer;

class HasOffersProductListTableFilterDataProvider implements ProductListTableFilterDataProviderInterface
{
    public const FILTER_NAME = 'hasOffers';

    protected const OPTION_NAME_YES = 'Yes';
    protected const OPTION_NAME_NO = 'No';

    /**
     * @return \Generated\Shared\Transfer\TableFilterTransfer
     */
    public function getFilterData(): TableFilterTransfer
    {
        return (new TableFilterTransfer())
            ->setKey(static::FILTER_NAME)
            ->setTitle('Has Offers')
            ->setType('select')
            ->setIsMultiselect(false)
            ->setOptions($this->getIsActiveOptions());
    }

    /**
     * @return int[]
     */
    protected function getIsActiveOptions(): array
    {
        return [
            static::OPTION_NAME_YES => 1,
            static::OPTION_NAME_NO => 0,
        ];
    }
}
