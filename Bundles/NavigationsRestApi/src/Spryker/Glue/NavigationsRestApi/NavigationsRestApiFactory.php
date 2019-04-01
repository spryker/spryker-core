<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\NavigationsRestApi;

use Spryker\Glue\Kernel\AbstractFactory;
use Spryker\Glue\NavigationsRestApi\Dependency\Client\NavigationsRestApiToNavigationStorageClientInterface;
use Spryker\Glue\NavigationsRestApi\Dependency\Client\NavigationsRestApiToUrlStorageClientInterface;
use Spryker\Glue\NavigationsRestApi\Processor\Expander\NavigationNodeExpander;
use Spryker\Glue\NavigationsRestApi\Processor\Expander\NavigationNodeExpanderInterface;
use Spryker\Glue\NavigationsRestApi\Processor\Mapper\NavigationMapper;
use Spryker\Glue\NavigationsRestApi\Processor\Mapper\NavigationMapperInterface;
use Spryker\Glue\NavigationsRestApi\Processor\Navigation\NavigationReader;
use Spryker\Glue\NavigationsRestApi\Processor\Navigation\NavigationReaderInterface;

/**
 * @method \Spryker\Glue\NavigationsRestApi\NavigationsRestApiConfig getConfig()
 */
class NavigationsRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\NavigationsRestApi\Processor\Navigation\NavigationReaderInterface
     */
    public function createNavigationReader(): NavigationReaderInterface
    {
        return new NavigationReader(
            $this->getNavigationStorageClient(),
            $this->createNavigationMapper(),
            $this->getResourceBuilder(),
            $this->createNavigationNodeExpander()
        );
    }

    /**
     * @return \Spryker\Glue\NavigationsRestApi\Processor\Mapper\NavigationMapperInterface
     */
    public function createNavigationMapper(): NavigationMapperInterface
    {
        return new NavigationMapper();
    }

    /**
     * @return \Spryker\Glue\NavigationsRestApi\Processor\Expander\NavigationNodeExpanderInterface
     */
    public function createNavigationNodeExpander(): NavigationNodeExpanderInterface
    {
        return new NavigationNodeExpander(
            $this->getUrlStorageClient(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Glue\NavigationsRestApi\Dependency\Client\NavigationsRestApiToNavigationStorageClientInterface
     */
    public function getNavigationStorageClient(): NavigationsRestApiToNavigationStorageClientInterface
    {
        return $this->getProvidedDependency(NavigationsRestApiDependencyProvider::CLIENT_NAVIGATION_STORAGE);
    }

    /**
     * @return \Spryker\Glue\NavigationsRestApi\Dependency\Client\NavigationsRestApiToUrlStorageClientInterface
     */
    public function getUrlStorageClient(): NavigationsRestApiToUrlStorageClientInterface
    {
        return $this->getProvidedDependency(NavigationsRestApiDependencyProvider::CLIENT_URL_STORAGE);
    }
}
