<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Kernel\Communication;

use Spryker\Zed\Kernel\BundleConfigResolverAwareTrait;
use Spryker\Zed\Kernel\QueryContainerResolverAwareTrait;
use Spryker\Zed\Kernel\RepositoryResolverAwareTrait;

abstract class AbstractPlugin
{
    use RepositoryResolverAwareTrait;
    use FactoryResolverAwareTrait;
    use FacadeResolverAwareTrait;
    use BundleConfigResolverAwareTrait;
    use QueryContainerResolverAwareTrait;
}
