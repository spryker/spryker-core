<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\NavigationsRestApi;

use Spryker\Glue\Kernel\AbstractFactory;
use Spryker\Glue\NavigationsRestApi\Dependency\Client\NavigationsRestApiToNavigationStorageClientInterface;
use Spryker\Glue\NavigationsRestApi\Processor\Mapper\NavigationMapper;
use Spryker\Glue\NavigationsRestApi\Processor\Mapper\NavigationMapperInterface;
use Spryker\Glue\NavigationsRestApi\Processor\Navigation\NavigationReader;
use Spryker\Glue\NavigationsRestApi\Processor\Navigation\NavigationReaderInterface;

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
            $this->getResourceBuilder()
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
     * @return \Spryker\Glue\NavigationsRestApi\Dependency\Client\NavigationsRestApiToNavigationStorageClientInterface
     */
    public function getNavigationStorageClient(): NavigationsRestApiToNavigationStorageClientInterface
    {
        return $this->getProvidedDependency(NavigationsRestApiDependencyProvider::CLIENT_NAVIGATION_STORAGE);
    }
}
