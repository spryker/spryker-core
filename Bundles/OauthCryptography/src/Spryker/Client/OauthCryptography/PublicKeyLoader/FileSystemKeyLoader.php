<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\OauthCryptography\PublicKeyLoader;

use League\OAuth2\Server\CryptKey;
use Spryker\Client\OauthCryptography\OauthCryptographyConfig;

class FileSystemKeyLoader implements FileSystemKeyLoaderInterface
{
    /**
     * @var \Spryker\Client\OauthCryptography\OauthCryptographyConfig
     */
    protected $oauthCryptographyConfig;

    /**
     * @param \Spryker\Client\OauthCryptography\OauthCryptographyConfig $oauthCryptographyConfig
     */
    public function __construct(OauthCryptographyConfig $oauthCryptographyConfig)
    {
        $this->oauthCryptographyConfig = $oauthCryptographyConfig;
    }

    /**
     * @return \League\OAuth2\Server\CryptKey[]
     */
    public function loadPublicKeys(): array
    {
        $publicKey = $this->oauthCryptographyConfig->getPublicKeyPath();

        if (!$publicKey instanceof CryptKey) {
            $publicKey = new CryptKey($publicKey);
        }

        return [$publicKey, $publicKey];
    }
}
