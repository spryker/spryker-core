<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\OauthCryptography\ResourceServer;

use League\OAuth2\Server\CryptKey;
use Spryker\Client\Oauth\OauthConfig;
use Spryker\Client\OauthCryptographyExtension\Dependency\Plugin\KeyLoaderInterface;

class KeyLoader implements KeyLoaderInterface
{
    /**
     * @var \Spryker\Client\Oauth\OauthConfig
     */
    protected $oauthConfig;

    /**
     * @param \Spryker\Client\Oauth\OauthConfig $oauthConfig
     */
    public function __construct(OauthConfig $oauthConfig)
    {
        $this->oauthConfig = $oauthConfig;
    }

    /**
     * @return \League\OAuth2\Server\CryptKey[]
     */
    public function loadKeys(): array
    {
        $publicKeys = [$this->oauthConfig->getPublicKeyPath()];

        foreach ($publicKeys as $index => $publicKey) {
            if ($publicKey instanceof CryptKey === false) {
                $publicKeys[$index] = new CryptKey($publicKey);
            }
        }

        return $publicKeys;
    }
}
