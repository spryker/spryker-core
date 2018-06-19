<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Availability\Zed;

use Generated\Shared\Transfer\ProductConcreteAvailabilityRequestTransfer;
use Spryker\Client\Availability\Dependency\Client\AvailabilityToZedRequestClientInterface;

class AvailabilityStub implements AvailabilityStubInterface
{
    /**
     * @var \Spryker\Client\Availability\Dependency\Client\AvailabilityToZedRequestClientInterface
     */
    protected $zedRequestClient;

    /**
     * @param \Spryker\Client\Availability\Dependency\Client\AvailabilityToZedRequestClientInterface $zedRequestClient
     */
    public function __construct(AvailabilityToZedRequestClientInterface $zedRequestClient)
    {
        $this->zedRequestClient = $zedRequestClient;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteAvailabilityRequestTransfer $productConcreteAvailabilityRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer
     */
    public function findProductConcreteAvailability(ProductConcreteAvailabilityRequestTransfer $productConcreteAvailabilityRequestTransfer)
    {
        return $this->zedRequestClient->call('/availability/gateway/find-product-concrete-availability', $productConcreteAvailabilityRequestTransfer);
    }
}
