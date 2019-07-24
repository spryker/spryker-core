<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfigurableBundle\Zed;

use Spryker\Client\ProductConfigurableBundle\Dependency\Client\ProductConfigurableBundleToZedRequestClientInterface;

class ProductConfigurableBundleStub implements ProductConfigurableBundleStubInterface
{
    /**
     * @var \Spryker\Client\ProductConfigurableBundle\Dependency\Client\ProductConfigurableBundleToZedRequestClientInterface
     */
    protected $zedRequestClient;

    /**
     * @param \Spryker\Client\ProductConfigurableBundle\Dependency\Client\ProductConfigurableBundleToZedRequestClientInterface $zedRequestClient
     */
    public function __construct(ProductConfigurableBundleToZedRequestClientInterface $zedRequestClient)
    {
        $this->zedRequestClient = $zedRequestClient;
    }
}
