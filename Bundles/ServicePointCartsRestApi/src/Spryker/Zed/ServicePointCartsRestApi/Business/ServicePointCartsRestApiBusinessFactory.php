<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ServicePointCartsRestApi\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ServicePointCartsRestApi\Business\Replacer\ServicePointQuoteItemReplacer;
use Spryker\Zed\ServicePointCartsRestApi\Business\Replacer\ServicePointQuoteItemReplacerInterface;
use Spryker\Zed\ServicePointCartsRestApi\Dependency\Facade\ServicePointCartsRestApiToServicePointCartFacadeInterface;
use Spryker\Zed\ServicePointCartsRestApi\ServicePointCartsRestApiDependencyProvider;

/**
 * @method \Spryker\Zed\ServicePointCartsRestApi\ServicePointCartsRestApiConfig getConfig()
 */
class ServicePointCartsRestApiBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ServicePointCartsRestApi\Business\Replacer\ServicePointQuoteItemReplacerInterface
     */
    public function createServicePointQuoteItemReplacer(): ServicePointQuoteItemReplacerInterface
    {
        return new ServicePointQuoteItemReplacer(
            $this->getServicePointCartFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\ServicePointCartsRestApi\Dependency\Facade\ServicePointCartsRestApiToServicePointCartFacadeInterface
     */
    public function getServicePointCartFacade(): ServicePointCartsRestApiToServicePointCartFacadeInterface
    {
        return $this->getProvidedDependency(ServicePointCartsRestApiDependencyProvider::FACADE_SERVICE_POINT_CART);
    }
}
