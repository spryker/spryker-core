<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\Kernel\Backend;

use Codeception\Test\Unit;
use Spryker\Glue\Kernel\Backend\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Backend\Container;
use Spryker\Glue\Kernel\Backend\Exception\InvalidContainerException;
use Spryker\Glue\Kernel\Container as GlueContainer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group Kernel
 * @group Backend
 * @group AbstractBundleDependencyProviderTest
 * Add your own group annotations below this line
 */
class AbstractBundleDependencyProviderTest extends Unit
{
    /**
     * @return void
     */
    public function testCallProvideGlueLayerDependenciesMustReturnBackendContainer(): void
    {
        $container = new Container();

        $abstractDependencyProviderMock = $this->getMockForAbstractClass(AbstractBundleDependencyProvider::class);
        $expected = $abstractDependencyProviderMock->provideDependencies($container);

        $this->assertSame($expected, $container);
    }

    /**
     * @return void
     */
    public function testCallProvideGlueLayerDependenciesMustThrowExceptionIfNotReturnBackendContainer(): void
    {
        $container = new GlueContainer();
        $abstractDependencyProviderMock = $this->getMockForAbstractClass(AbstractBundleDependencyProvider::class);

        $this->expectException(InvalidContainerException::class);

        $abstractDependencyProviderMock->provideDependencies($container);
    }
}
