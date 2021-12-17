<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\ApiApplication\Type;

use Spryker\Glue\Kernel\FactoryResolverAwareTrait;
use Spryker\Shared\Application\Application;

/**
 * Executes an ApiApplication bootstrap without administrating the request flow. Only should be used in special cases where API
 * applications have special execution flows
 */
abstract class RequestFlowAgnosticApiApplication extends Application
{
    use FactoryResolverAwareTrait;
}
