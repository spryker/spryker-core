<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\WishlistsRestApi;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\WishlistsRestApi\Dependency\Client\WishlistsRestApiToZedRequestClientInterface;
use Spryker\Client\WishlistsRestApi\Zed\WishlistsRestApiStub;
use Spryker\Client\WishlistsRestApi\Zed\WishlistsRestApiStubInterface;

class WishlistsRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\WishlistsRestApi\Zed\WishlistsRestApiStubInterface
     */
    public function createZedStub(): WishlistsRestApiStubInterface
    {
        return new WishlistsRestApiStub($this->getWishlistClient());
    }

    /**
     * @return \Spryker\Client\WishlistsRestApi\Dependency\Client\WishlistsRestApiToZedRequestClientInterface
     */
    public function getWishlistClient(): WishlistsRestApiToZedRequestClientInterface
    {
        return $this->getProvidedDependency(WishlistsRestApiDependencyProvider::CLIENT_ZED_REQUEST);
    }
}
