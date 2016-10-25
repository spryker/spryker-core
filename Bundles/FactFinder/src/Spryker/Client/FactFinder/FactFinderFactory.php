<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\FactFinder;

use Spryker\Client\Cart\Session\QuoteSession;
use Spryker\Client\FactFinder\Business\Api\Converter\ConverterFactory;
use Spryker\Client\FactFinder\Business\Api\FactFinderConnector;
use Spryker\Client\FactFinder\Business\Api\Handler\Request\SearchRequest;
use Spryker\Client\FactFinder\Zed\FactFinderStub;
use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Zed\Kernel\BundleConfigResolverAwareTrait;

/**
 * @method \Spryker\Zed\FactFinder\FactFinderConfig getConfig()
 */
class FactFinderFactory extends AbstractFactory
{

    use BundleConfigResolverAwareTrait;

    /**
     * @return \Spryker\Client\FactFinder\Zed\FactFinderStubInterface
     */
    public function createZedFactFinderStub()
    {
        return new FactFinderStub(
            $this->getProvidedDependency(FactFinderDependencyProvider::SERVICE_ZED)
        );
    }

    /**
     * @return \Spryker\Client\FactFinder\Business\Api\Handler\Request\SearchRequest
     */
    public function createSearchRequest()
    {
        return new SearchRequest(
            $this->createFactFinderConnector(),
            $this->createConverterFactory()
        );
    }

    /**
     * @return \Spryker\Client\FactFinder\Business\Api\FactFinderConnector
     */
    public function createFactFinderConnector()
    {
        return new FactFinderConnector($this->getConfig());
    }

    /**
     * @return \Spryker\Client\FactFinder\Business\Api\Converter\ConverterFactory
     */
    protected function createConverterFactory()
    {
        return new ConverterFactory();
    }

    /**
     * @return \Spryker\Client\Cart\Session\QuoteSessionInterface
     */
    public function createSession()
    {
        return new QuoteSession($this->getSessionClient());
    }

}
