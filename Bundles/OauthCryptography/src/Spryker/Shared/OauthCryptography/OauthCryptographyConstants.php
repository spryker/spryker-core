<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\OauthCryptography;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
interface OauthCryptographyConstants
{
    /**
     * Specification:
     *  - Path to public key location.
     */
    public const PUBLIC_KEY_PATH = 'OAUTH_CRYPTOGRAPHY:PUBLIC_KEY_PATH';
}
