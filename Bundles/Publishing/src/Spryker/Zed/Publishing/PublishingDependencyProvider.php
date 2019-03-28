<?php

namespace Spryker\Zed\Publishing;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Publishing\Dependency\PublishingRegistryCollection;

class PublishingDependencyProvider extends AbstractBundleDependencyProvider
{

    public function getPublishingRegistryCollection()
    {
        return new PublishingRegistryCollection();
    }

}
