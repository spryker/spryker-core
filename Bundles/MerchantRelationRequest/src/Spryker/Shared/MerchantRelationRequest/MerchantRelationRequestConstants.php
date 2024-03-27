<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\MerchantRelationRequest;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
interface MerchantRelationRequestConstants
{
    /**
     * Specification:
     * - Base URL for Yves including scheme and port (e.g. http://www.de.demoshop.local:8080)
     *
     * @api
     *
     * @var string
     */
    public const BASE_URL_YVES = 'MERCHANT_RELATION_REQUEST:BASE_URL_YVES';
}
