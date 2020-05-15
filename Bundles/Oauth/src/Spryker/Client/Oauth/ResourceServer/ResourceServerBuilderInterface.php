<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Oauth\ResourceServer;

use Spryker\Client\OauthCryptography\ResourceServer\ResourceServer;

interface ResourceServerBuilderInterface
{
    /**
     * @return \Spryker\Client\OauthCryptography\ResourceServer\ResourceServer
     */
    public function create(): ResourceServer;
}
