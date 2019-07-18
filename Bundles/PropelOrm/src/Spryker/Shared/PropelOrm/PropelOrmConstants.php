<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\PropelOrm;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
interface PropelOrmConstants
{
    /**
     * Specification:
     * - Enable this to get a better exception message when an error occurs.
     * - Should only be used on non production environments.
     *
     * @api
     */
    public const PROPEL_SHOW_EXTENDED_EXCEPTION = 'PROPEL_SHOW_EXTENDED_EXCEPTION';
}
