<?php

namespace Spryker\Zed\Publishing;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Publisher\Dependency\PublisherRegistryCollection;

class PublishingDependencyProvider extends AbstractBundleDependencyProvider
{

    public function getPublisherRegistryCollection()
    {
        return new PublisherRegistryCollection();
    }

}
