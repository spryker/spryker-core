<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\OauthCryptography\Communication\Plugin\Oauth;

use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\OauthExtension\Dependency\Plugin\KeyLoaderPluginInterface;

/**
 * @method \Spryker\Client\OauthCryptography\OauthCryptographyClientInterface getClient()
 * @method \Spryker\Client\OauthCryptography\OauthCryptographyConfig getConfig()
 */
class FileSystemKeyLoaderPlugin extends AbstractPlugin implements KeyLoaderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Loads the default configured public ssh key.
     * - Creates `CryptKey` instance in case the configured key is not one.
     *
     * @api
     *
     * @return \League\OAuth2\Server\CryptKey[]
     */
    public function loadKeys(): array
    {
        return $this->getClient()->loadPublicKeys();
    }
}
