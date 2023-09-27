<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ServicePointsRestApi\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ServicePointsRestApi\Business\Expander\CheckoutDataExpander;
use Spryker\Zed\ServicePointsRestApi\Business\Expander\CheckoutDataExpanderInterface;
use Spryker\Zed\ServicePointsRestApi\Business\Mapper\QuoteItemMapper;
use Spryker\Zed\ServicePointsRestApi\Business\Mapper\QuoteItemMapperInterface;
use Spryker\Zed\ServicePointsRestApi\Dependency\Facade\ServicePointsRestApiToServicePointFacadeInterface;
use Spryker\Zed\ServicePointsRestApi\ServicePointsRestApiDependencyProvider;

/**
 * @method \Spryker\Zed\ServicePointsRestApi\ServicePointsRestApiConfig getConfig()
 */
class ServicePointsRestApiBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ServicePointsRestApi\Business\Expander\CheckoutDataExpanderInterface
     */
    public function createCheckoutDataExpander(): CheckoutDataExpanderInterface
    {
        return new CheckoutDataExpander();
    }

    /**
     * @return \Spryker\Zed\ServicePointsRestApi\Business\Mapper\QuoteItemMapperInterface
     */
    public function createQuoteItemMapper(): QuoteItemMapperInterface
    {
        return new QuoteItemMapper(
            $this->getServicePointFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\ServicePointsRestApi\Dependency\Facade\ServicePointsRestApiToServicePointFacadeInterface
     */
    public function getServicePointFacade(): ServicePointsRestApiToServicePointFacadeInterface
    {
        return $this->getProvidedDependency(ServicePointsRestApiDependencyProvider::FACADE_SERVICE_POINT);
    }
}
