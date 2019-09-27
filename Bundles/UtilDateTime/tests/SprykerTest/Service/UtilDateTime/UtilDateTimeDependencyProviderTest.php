<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Service\UtilDateTime;

use Codeception\Test\Unit;
use Spryker\Service\Kernel\Container;
use Spryker\Service\UtilDateTime\UtilDateTimeDependencyProvider;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Service
 * @group UtilDateTime
 * @group UtilDateTimeDependencyProviderTest
 * Add your own group annotations below this line
 */
class UtilDateTimeDependencyProviderTest extends Unit
{
    /**
     * @return void
     */
    public function testProvideServiceDependenciesShouldAddConfigToContainer()
    {
        $container = new Container();
        $utilDateTimeDependencyProvider = new UtilDateTimeDependencyProvider();
        $utilDateTimeDependencyProvider->provideServiceDependencies($container);

        $this->assertArrayHasKey(UtilDateTimeDependencyProvider::CONFIG, $container);
    }
}
