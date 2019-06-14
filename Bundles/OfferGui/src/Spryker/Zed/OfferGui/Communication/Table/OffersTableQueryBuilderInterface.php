<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OfferGui\Communication\Table;

interface OffersTableQueryBuilderInterface
{
    public const FIELD_ITEM_STATE_NAMES_CSV = 'item_state_names_csv';
    public const FIELD_NUMBER_OF_ORDER_ITEMS = 'number_of_order_items';

    /**
     * @return \Orm\Zed\Offer\Persistence\SpyOfferQuery
     */
    public function buildQuery();
}
