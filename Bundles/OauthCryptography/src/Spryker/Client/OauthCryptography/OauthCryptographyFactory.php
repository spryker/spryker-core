<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\OauthCryptography;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\OauthCryptography\PublicKeyLoader\FileSystemKeyLoader;
use Spryker\Client\OauthCryptography\PublicKeyLoader\FileSystemKeyLoaderInterface;
use Spryker\Client\OauthCryptography\Validator\BearerTokenAuthorizationValidator;
use Spryker\Client\OauthCryptography\Validator\BearerTokenAuthorizationValidatorInterface;

/**
 * @method \Spryker\Client\OauthCryptography\OauthCryptographyConfig getConfig()
 */
class OauthCryptographyFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\OauthCryptography\PublicKeyLoader\FileSystemKeyLoaderInterface
     */
    public function createFileSystemKeyLoader(): FileSystemKeyLoaderInterface
    {
        return new FileSystemKeyLoader($this->getConfig());
    }

    /**
     * @return \Spryker\Client\OauthCryptography\Validator\BearerTokenAuthorizationValidatorInterface
     */
    public function createBearerTokenAuthorizationValidator(): BearerTokenAuthorizationValidatorInterface
    {
        return new BearerTokenAuthorizationValidator();
    }
}
