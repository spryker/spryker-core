<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Kernel;

use Spryker\Zed\Kernel\Container;

/**
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group Kernel
 * @group AbstractBundleDependencyProviderTest
 */
class AbstractBundleDependencyProviderTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testCallProvidePersistenceLayerDependenciesMustReturnContainer()
    {
        $container = new Container();

        $abstractDependencyProviderMock = $this->getAbstractBundleDependencyProviderMock();
        $expected = $abstractDependencyProviderMock->providePersistenceLayerDependencies($container);

        $this->assertSame($expected, $container);
    }

    /**
     * @return void
     */
    public function testCallProvideCommunicationLayerDependenciesMustReturnContainer()
    {
        $container = new Container();

        $abstractDependencyProviderMock = $this->getAbstractBundleDependencyProviderMock();
        $expected = $abstractDependencyProviderMock->provideCommunicationLayerDependencies($container);

        $this->assertSame($expected, $container);
    }

    /**
     * @return void
     */
    public function testCallProvideBusinessLayerDependenciesMustReturnContainer()
    {
        $container = new Container();

        $abstractDependencyProviderMock = $this->getAbstractBundleDependencyProviderMock();
        $expected = $abstractDependencyProviderMock->provideBusinessLayerDependencies($container);

        $this->assertSame($expected, $container);
    }

    /**
     * @return \Spryker\Zed\Kernel\AbstractBundleDependencyProvider
     */
    private function getAbstractBundleDependencyProviderMock()
    {
        return $this->getMockForAbstractClass('Spryker\Zed\Kernel\AbstractBundleDependencyProvider');
    }

}
