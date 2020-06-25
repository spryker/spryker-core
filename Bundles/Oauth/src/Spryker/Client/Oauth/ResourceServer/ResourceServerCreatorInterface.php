<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Oauth\ResourceServer;

interface ResourceServerCreatorInterface
{
    /**
     * @return \Spryker\Client\Oauth\ResourceServer\ResourceServer
     */
    public function create(): ResourceServer;
}
