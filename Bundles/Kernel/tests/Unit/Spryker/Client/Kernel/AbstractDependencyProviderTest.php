<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\Spryker\Client\Kernel;

use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;

/**
 * @group Spryker
 * @group Client
 * @group Kernel
 * @group AbstractDependencyProvider
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
        $expected = $abstractDependencyProviderMock->provideServiceLayerDependencies($container);

        $this->assertSame($expected, $container);
    }

    /**
     * @return \Spryker\Client\Kernel\AbstractDependencyProvider
     */
    private function getAbstractDependencyProviderMock()
    {
        return $this->getMockForAbstractClass('Spryker\Client\Kernel\AbstractDependencyProvider');
    }

}
