<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Kernel;

abstract class AbstractFactory
{

    use QueryContainerResolverAwareTrait;
    use BundleConfigResolverAwareTrait;
    use BundleDependencyProviderResolverAwareTrait;

}
