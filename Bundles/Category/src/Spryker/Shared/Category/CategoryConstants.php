<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Category;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
interface CategoryConstants
{
    /**
     * Specification:
     * - Defines the number of categories in the chunk to read.
     *
     * @api
     * @var string
     */
    public const CATEGORY_READ_CHUNK = 'CATEGORY:CATEGORY_READ_CHUNK';

    /**
     * Specification:
     * - Defines if Propel events for `spy_category_closure_table` table should be enabled.
     * - Impacts category create/update operations.
     *
     * @api
     * @var string
     */
    public const CATEGORY_IS_CLOSURE_TABLE_EVENTS_ENABLED = 'CATEGORY:CATEGORY_IS_CLOSURE_TABLE_EVENTS_ENABLED';
}
