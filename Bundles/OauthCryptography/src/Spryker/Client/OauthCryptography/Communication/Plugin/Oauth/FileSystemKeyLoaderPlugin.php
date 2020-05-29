<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\OauthCryptography\Communication\Plugin\Oauth;

use League\OAuth2\Server\CryptKey;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\OauthExtention\Dependency\Plugin\KeyLoaderPluginInterface;

/**
 * @method \Spryker\Client\OauthCryptography\OauthCryptographyFactory getFactory()
 */
class FileSystemKeyLoaderPlugin extends AbstractPlugin implements KeyLoaderPluginInterface
{
    /**
     * @return \League\OAuth2\Server\CryptKey[]
     */
    public function loadKeys(): array
    {
        // Todo: move behind the public API.
        $publicKey = $this->getFactory()->getConfig()->getPublicKeyPath();

        if (!$publicKey instanceof CryptKey) {
            $publicKey = new CryptKey($publicKey);
        }

        return [$publicKey];
    }
}
