<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\ProductManagement;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
interface ProductManagementConstants
{
    public const PRODUCT_MANAGEMENT_DEFAULT_LOCALE = 'default';

    /**
     * Base URL for Yves including scheme and port (e.g. http://www.de.demoshop.local:8080)
     *
     * @api
     */
    public const BASE_URL_YVES = 'PRODUCT_MANAGEMENT:BASE_URL_YVES';
}
