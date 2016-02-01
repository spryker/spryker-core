<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\Spryker\Zed\Kernel;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

/**
 * @group Spryker
 * @group Zed
 * @group Kernel
 * @group AbstractBundleDependencyProvider
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
