<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Search;

interface SearchConstants
{

    /**
     * When executing boosted full text search queries the value of this config setting will be used as the boost factor.
     * E.g. to set the boost factor to 3 add this to your config: `$config[SearchConstants::FULL_TEXT_BOOSTED_BOOSTING_VALUE] = 3;`.
     *
     * @api
     */
    const FULL_TEXT_BOOSTED_BOOSTING_VALUE = 'FULL_TEXT_BOOSTED_BOOSTING_VALUE';

    /**
     * Available facet types
     */
    const FACET_TYPE_ENUMERATION = 'enumeration';
    const FACET_TYPE_RANGE = 'range';
    const FACET_TYPE_PRICE_RANGE = 'price-range';
    const FACET_TYPE_CATEGORY = 'category';

}
