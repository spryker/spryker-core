<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerEngine\Zed\Kernel;

use SprykerEngine\Zed\Kernel\AbstractBundleDependencyProvider;
use SprykerEngine\Zed\Kernel\Container;

/**
 * @group SprykerEngine
 * @group Zed
 * @group Kernel
 * @group AbstractBundleDependencyProvider
 */
class AbstractBundleDependencyProviderTest extends \PHPUnit_Framework_TestCase
{

    public function testCallProvidePersistenceLayerDependenciesMustReturnContainer()
    {
        $container = new Container();

        $abstractDependencyProviderMock = $this->getAbstractBundleDependencyProviderMock();
        $expected = $abstractDependencyProviderMock->providePersistenceLayerDependencies($container);

        $this->assertSame($expected, $container);
    }

    public function testCallProvideCommunicationLayerDependenciesMustReturnContainer()
    {
        $container = new Container();

        $abstractDependencyProviderMock = $this->getAbstractBundleDependencyProviderMock();
        $expected = $abstractDependencyProviderMock->provideCommunicationLayerDependencies($container);

        $this->assertSame($expected, $container);
    }

    public function testCallProvideBusinessLayerDependenciesMustReturnContainer()
    {
        $container = new Container();

        $abstractDependencyProviderMock = $this->getAbstractBundleDependencyProviderMock();
        $expected = $abstractDependencyProviderMock->provideBusinessLayerDependencies($container);

        $this->assertSame($expected, $container);
    }

    /**
     * @return AbstractBundleDependencyProvider
     */
    private function getAbstractBundleDependencyProviderMock()
    {
        return $this->getMockForAbstractClass('SprykerEngine\Zed\Kernel\AbstractBundleDependencyProvider');
    }

}
