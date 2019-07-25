<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ConfigurableBundle\Zed;

use Spryker\Client\ConfigurableBundle\Dependency\Client\ConfigurableBundleToZedRequestClientInterface;

class ConfigurableBundleStub implements ConfigurableBundleStubInterface
{
    /**
     * @var \Spryker\Client\ConfigurableBundle\Dependency\Client\ConfigurableBundleToZedRequestClientInterface
     */
    protected $zedRequestClient;

    /**
     * @param \Spryker\Client\ConfigurableBundle\Dependency\Client\ConfigurableBundleToZedRequestClientInterface $zedRequestClient
     */
    public function __construct(ConfigurableBundleToZedRequestClientInterface $zedRequestClient)
    {
        $this->zedRequestClient = $zedRequestClient;
    }
}
