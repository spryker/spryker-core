<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Service\UtilDateTime;

use PHPUnit_Framework_TestCase;
use Spryker\Service\Kernel\Container;
use Spryker\Service\UtilDateTime\UtilDateTimeDependencyProvider;

/**
 * @group Unit
 * @group Spryker
 * @group Service
 * @group UtilDateTime
 * @group UtilDateTimeDependencyProviderTest
 */
class UtilDateTimeDependencyProviderTest extends PHPUnit_Framework_TestCase
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
