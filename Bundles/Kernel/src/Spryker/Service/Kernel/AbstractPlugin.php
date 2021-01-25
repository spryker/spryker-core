<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Kernel;

abstract class AbstractPlugin
{
    use BundleConfigResolverAwareTrait;
    use FactoryResolverAwareTrait;
    use ServiceResolverAwareTrait;
}
