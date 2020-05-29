<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\OauthExtention\Dependency\Plugin;

interface KeyLoaderPluginInterface
{
    /**
     * @return \League\OAuth2\Server\CryptKey[]
     */
    public function loadKeys(): array;
}
