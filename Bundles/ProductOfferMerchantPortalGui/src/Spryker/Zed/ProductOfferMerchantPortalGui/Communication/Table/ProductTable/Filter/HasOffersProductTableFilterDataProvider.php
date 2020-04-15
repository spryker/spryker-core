<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\ProductTable\Filter;

use Generated\Shared\Transfer\GuiTableFilterTransfer;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Table\Filter\TableFilterDataProviderInterface;

class HasOffersProductTableFilterDataProvider implements TableFilterDataProviderInterface
{
    public const FILTER_NAME = 'offers';

    protected const OPTION_NAME_WITH_OFFERS = 'With Offers';
    protected const OPTION_NAME_WITHOUT_OFFERS = 'Without Offers';

    /**
     * @return \Generated\Shared\Transfer\GuiTableFilterTransfer
     */
    public function getFilterData(): GuiTableFilterTransfer
    {
        return (new GuiTableFilterTransfer())
            ->setId(static::FILTER_NAME)
            ->setTitle('Offers')
            ->setType('select')
            ->addTypeOption(static::OPTION_NAME_MULTISELECT, false)
            ->addTypeOption(static::OPTION_NAME_VALUES, $this->getIsActiveValues());
    }

    /**
     * @return array
     */
    protected function getIsActiveValues(): array
    {
        return [
            [
                static::OPTION_VALUE_KEY_TITLE => static::OPTION_NAME_WITH_OFFERS,
                static::OPTION_VALUE_KEY_VALUE => 1,
                ],
            [
                static::OPTION_VALUE_KEY_TITLE => static::OPTION_NAME_WITHOUT_OFFERS,
                static::OPTION_VALUE_KEY_VALUE => 0,
                ],
        ];
    }
}
