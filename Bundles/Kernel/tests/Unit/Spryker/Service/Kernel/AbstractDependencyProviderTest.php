<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Service\Kernel;

use Spryker\Service\Kernel\AbstractBundleDependencyProvider;
use Spryker\Service\Kernel\Container;

/**
 * @group Unit
 * @group Spryker
 * @group Service
 * @group Kernel
 * @group AbstractDependencyProviderTest
 */
class AbstractDependencyProviderTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testCallProvideServiceLayerDependenciesMustReturnContainer()
    {
        $container = new Container();

        $abstractDependencyProviderMock = $this->getAbstractDependencyProviderMock();
        $expected = $abstractDependencyProviderMock->provideServiceDependencies($container);

        $this->assertSame($expected, $container);
    }

    /**
     * @return \Spryker\Service\Kernel\AbstractBundleDependencyProvider
     */
    private function getAbstractDependencyProviderMock()
    {
        return $this->getMockForAbstractClass(AbstractBundleDependencyProvider::class);
    }

}
