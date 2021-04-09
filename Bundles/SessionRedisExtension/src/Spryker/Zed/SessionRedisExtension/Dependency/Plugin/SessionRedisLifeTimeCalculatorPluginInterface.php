<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SessionRedisExtension\Dependency\Plugin;

use Spryker\Shared\SessionRedisExtension\Dependency\Plugin\SessionRedisLifeTimeCalculatorPluginInterface as NewSessionRedisLifeTimeCalculatorPluginInterface;

/**
 * @deprecated Use {@link \Spryker\Shared\SessionRedisExtension\Dependency\Plugin\SessionRedisLifeTimeCalculatorPluginInterface} instead.
 *
 * Use this plugin if life time of redis session must be changed.
 */
interface SessionRedisLifeTimeCalculatorPluginInterface extends NewSessionRedisLifeTimeCalculatorPluginInterface
{
}
