<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\SearchElasticsearchGui;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
interface SearchElasticsearchGuiConstants
{
    /**
     * Specification:
     * - When executing boosted full text search queries the value of this config setting will be used as the boost factor.
     * - I.e. to set the boost factor to 3 add this to your config: `$config[SearchElasticsearchConstants::FULL_TEXT_BOOSTED_BOOSTING_VALUE] = 3;`.
     *
     * @uses \Spryker\Shared\SearchElasticsearch\SearchElasticsearchConstants::FULL_TEXT_BOOSTED_BOOSTING_VALUE
     *
     * @api
     */
    public const FULL_TEXT_BOOSTED_BOOSTING_VALUE = 'SEARCH_ELASTICSEARCH:FULL_TEXT_BOOSTED_BOOSTING_VALUE';
}
