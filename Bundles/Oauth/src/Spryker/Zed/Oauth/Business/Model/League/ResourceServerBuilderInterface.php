<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oauth\Business\Model\League;

use League\OAuth2\Server\ResourceServer;

interface ResourceServerBuilderInterface
{
    /**
     * @return \League\OAuth2\Server\ResourceServer
     */
    public function build(): ResourceServer;
}
