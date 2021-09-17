<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\OauthExtension\Dependency\Plugin;

/**
 * Plugin implementations will be used to load cryptographic keys.
 */
interface KeyLoaderPluginInterface
{
    /**
     * Specification:
     * - Loads public keys.
     *
     * @api
     *
     * @return array<\League\OAuth2\Server\CryptKey>
     */
    public function loadKeys(): array;
}
