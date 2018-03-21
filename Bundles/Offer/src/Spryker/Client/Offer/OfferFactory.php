<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Offer;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\Offer\Zed\OfferStub;

class OfferFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\Offer\Zed\OfferStubInterface
     */
    public function createZedStub()
    {
        return new OfferStub(
            $this->getZedRequestClient()
        );
    }

    /**
     * @return \Spryker\Client\ZedRequest\ZedRequestClient
     */
    protected function getZedRequestClient()
    {
        return $this->getProvidedDependency(OfferDependencyProvider::CLIENT_ZED_REQUEST);
    }
}
