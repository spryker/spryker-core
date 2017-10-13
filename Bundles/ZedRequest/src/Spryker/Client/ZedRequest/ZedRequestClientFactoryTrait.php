<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ZedRequest;

use Spryker\Client\ZedRequest\ServiceProvider\ZedRequestClientServiceProvider;

trait ZedRequestClientFactoryTrait
{
    /**
     * @return \Spryker\Client\ZedRequest\ZedRequestClientInterface
     */
    public function getZedRequestClient()
    {
        return $this->getProvidedDependency(ZedRequestClientServiceProvider::CLIENT_ZED_REQUEST);
    }

    /**
     * @param string $key
     *
     * @throws \Spryker\Client\Kernel\Exception\Container\ContainerKeyNotFoundException
     *
     * @return mixed
     */
    abstract public function getProvidedDependency($key);
}
