<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Kernel;

abstract class AbstractFactory
{
    use BundleConfigResolverAwareTrait;
    use BundleDependencyProviderResolverAwareTrait;
    use QueryContainerResolverAwareTrait;
    use RepositoryResolverAwareTrait;
    use EntityManagerResolverAwareTrait;
}
