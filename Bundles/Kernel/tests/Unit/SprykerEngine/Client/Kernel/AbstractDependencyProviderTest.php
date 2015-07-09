<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerEngine\Client\Kernel;

use SprykerEngine\Client\Kernel\AbstractDependencyProvider;
use SprykerEngine\Client\Kernel\Container;

/**
 * @group SprykerEngine
 * @group Client
 * @group Kernel
 * @group AbstractDependencyProvider
 */
class AbstractDependencyProviderTest extends \PHPUnit_Framework_TestCase
{

    public function testCallProvideServiceLayerDependenciesMustReturnContainer()
    {
        $container = new Container();

        $abstractDependencyProviderMock = $this->getAbstractDependencyProviderMock();
        $expected = $abstractDependencyProviderMock->provideServiceLayerDependencies($container);

        $this->assertSame($expected, $container);
    }

    /**
     * @return AbstractDependencyProvider
     */
    private function getAbstractDependencyProviderMock()
    {
        return $this->getMockForAbstractClass('SprykerEngine\Client\Kernel\AbstractDependencyProvider');
    }

}
