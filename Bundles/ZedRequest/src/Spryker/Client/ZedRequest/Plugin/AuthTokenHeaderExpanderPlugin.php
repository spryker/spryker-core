<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ZedRequest\Plugin;

use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\ZedRequestExtension\Dependency\Plugin\HeaderExpanderPluginInterface;

/**
 * @method \Spryker\Client\ZedRequest\ZedRequestClient getClient()
 */
class AuthTokenHeaderExpanderPlugin extends AbstractPlugin implements HeaderExpanderPluginInterface
{
    /**
     * @param array $headers
     *
     * @return array
     */
    public function expandHeader(array $headers): array
    {
        $headers['Auth-Token'] = $this->getClient()->getAuthToken();

        return $headers;
    }
}
