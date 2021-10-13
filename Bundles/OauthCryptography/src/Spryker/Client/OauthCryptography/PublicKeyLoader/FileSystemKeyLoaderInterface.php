<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\OauthCryptography\PublicKeyLoader;

interface FileSystemKeyLoaderInterface
{
    /**
     * @return array<\League\OAuth2\Server\CryptKey>
     */
    public function loadPublicKeys(): array;
}
