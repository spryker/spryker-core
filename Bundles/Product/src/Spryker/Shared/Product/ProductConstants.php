<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Product;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
interface ProductConstants
{
    /**
     * @var string
     */
    public const PUBLISHING_TO_MESSAGE_BROKER_ENABLED = 'PRODUCT:PUBLISHING_TO_MESSAGE_BROKER_ENABLED';

    /**
     * @var string
     */
    public const TENANT_IDENTIFIER = 'PRODUCT:TENANT_IDENTIFIER';
}
