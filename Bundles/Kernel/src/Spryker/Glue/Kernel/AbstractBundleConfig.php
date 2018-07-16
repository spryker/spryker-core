<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\Kernel;

use Spryker\Shared\Kernel\AbstractBundleConfig as SharedAbstractBundleConfig;
use Spryker\Shared\Kernel\SharedConfigResolverAwareTrait;

abstract class AbstractBundleConfig extends SharedAbstractBundleConfig
{
    use SharedConfigResolverAwareTrait;
}
