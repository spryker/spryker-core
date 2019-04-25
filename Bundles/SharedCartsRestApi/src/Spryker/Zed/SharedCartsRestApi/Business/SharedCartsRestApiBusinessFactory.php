<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SharedCartsRestApi\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Quote\Business\QuoteFacadeInterface;
use Spryker\Zed\SharedCart\Business\SharedCartFacadeInterface;
use Spryker\Zed\SharedCartsRestApi\Business\SharedCart\SharedCartReader;
use Spryker\Zed\SharedCartsRestApi\Business\SharedCart\SharedCartReaderInterface;
use Spryker\Zed\SharedCartsRestApi\SharedCartsRestApiDependencyProvider;

class SharedCartsRestApiBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\SharedCartsRestApi\Business\SharedCart\SharedCartReaderInterface
     */
    public function createSharedCartReader(): SharedCartReaderInterface
    {
        return new SharedCartReader(
            $this->getQuoteFacade(),
            $this->getSharedCartFacade()
        );
    }

    /**
     * @return \Spryker\Zed\Quote\Business\QuoteFacadeInterface
     */
    public function getQuoteFacade(): QuoteFacadeInterface
    {
        return $this->getProvidedDependency(SharedCartsRestApiDependencyProvider::FACADE_QUOTE);
    }

    /**
     * @return \Spryker\Zed\SharedCart\Business\SharedCartFacadeInterface
     */
    public function getSharedCartFacade(): SharedCartFacadeInterface
    {
        return $this->getProvidedDependency(SharedCartsRestApiDependencyProvider::FACADE_SHARED_CART);
    }
}
