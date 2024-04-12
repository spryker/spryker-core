<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Transfer;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
interface TransferConstants
{
    /**
     * Specification:
     * - If true, transfer generation is in debug mode.
     * - If false, transfer generation will run in normal mode.
     *
     * @api
     *
     * @var string
     */
    public const IS_DEBUG_ENABLED = 'TRANSFER:IS_DEBUG_ENABLED';

    /**
     * Specification:
     * - Use this strategy for configuration merge in case you want to throw an exception if there is a conflict.
     *
     * @api
     *
     * @var string
     */
    public const PROPERTY_DESCRIPTION_MERGE_STRATEGY_DEFAULT = 'PROPERTY_DESCRIPTION_MERGE_STRATEGY_DEFAULT';

    /**
     * Specification:
     * - Use this strategy for configuration merge. Get the first value if there is a conflict.
     *
     * @api
     *
     * @var string
     */
    public const PROPERTY_DESCRIPTION_MERGE_STRATEGY_GET_FIRST = 'PROPERTY_DESCRIPTION_MERGE_STRATEGY_GET_FIRST';

    /**
     * Specification:
     * - Use this strategy for configuration merge. Concat values if there is a conflict.
     *
     * @api
     *
     * @var string
     */
    public const PROPERTY_DESCRIPTION_MERGE_STRATEGY_CONCAT = 'PROPERTY_DESCRIPTION_MERGE_STRATEGY_CONCAT';
}
