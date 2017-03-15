<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\FactFinder;

use Pyz\Yves\Product\Builder\FrontendProductBuilder;
use Pyz\Yves\Product\Model\ProductAbstract;
use Spryker\Client\Collector\KeyBuilder\UrlKeyBuilder;
use Spryker\Client\Collector\Matcher\UrlMatcher;
use Spryker\Client\FactFinder\Business\Api\Converter\ConverterFactory;
use Spryker\Client\FactFinder\Business\Api\FactFinderConnector;
use Spryker\Client\FactFinder\Business\Api\Handler\Request\RecommendationRequest;
use Spryker\Client\FactFinder\Business\Api\Handler\Request\SearchRequest;
use Spryker\Client\FactFinder\Business\Api\Handler\Request\SuggestRequest;
use Spryker\Client\FactFinder\Business\Service\ProductByUrlResolver;
use Spryker\Client\FactFinder\Zed\FactFinderStub;
use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\Quote\Session\QuoteSession;

/**
 * @method \Spryker\Zed\FactFinder\FactFinderConfig getConfig()
 */
class FactFinderFactory extends AbstractFactory
{

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
     * @return \Spryker\Client\FactFinder\Business\Api\Handler\Request\SuggestRequest
     */
    public function createSuggestRequest()
    {
        return new SuggestRequest(
            $this->createFactFinderConnector(),
            $this->createConverterFactory()
        );
    }

    /**
     * @return \Spryker\Client\FactFinder\Business\Api\Handler\Request\RecommendationRequest
     */
    public function createRecommendationsRequest()
    {
        return new RecommendationRequest(
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
     * @return QuoteSession
     */
    public function createSession()
    {
        return new QuoteSession($this->getProvidedDependency(FactFinderDependencyProvider::CLIENT_SESSION));
    }

    /**
     * @return \Spryker\Client\FactFinder\Business\Service\ProductByUrlResolver
     */
    public function createProductByUrlResolver()
    {
        return new ProductByUrlResolver(
            $this->createUrlMatcher(),
            $this->getStorageClient()
        );
    }

    /**
     * @return \Spryker\Client\Collector\Matcher\UrlMatcher
     */
    public function createUrlMatcher()
    {
        return new UrlMatcher(
            $this->createUrlKeyBuilder(),
            $this->getStorageClient()
        );
    }

    /**
     * @return \Spryker\Client\Storage\StorageClientInterface
     */
    public function getStorageClient()
    {
        return $this->getProvidedDependency(FactFinderDependencyProvider::CLIENT_KV_STORAGE);
    }

    /**
     * @return \Spryker\Client\Collector\KeyBuilder\UrlKeyBuilder
     */
    public function createUrlKeyBuilder()
    {
        return new UrlKeyBuilder();
    }

    /**
     * @return \Pyz\Yves\Product\Builder\FrontendProductBuilder
     */
    public function createFrontendProductBuilder()
    {
        return new FrontendProductBuilder(
            $this->createProductAbstract()
        );
    }

    /**
     * @return \Pyz\Yves\Product\Model\ProductAbstract
     */
    protected function createProductAbstract()
    {
        return new ProductAbstract();
    }

}
