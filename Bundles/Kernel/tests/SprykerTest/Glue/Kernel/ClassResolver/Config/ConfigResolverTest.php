<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\Kernel\ClassResolver\Config;

use Spryker\Glue\Kernel\ClassResolver\Config\BundleConfigNotFoundException;
use Spryker\Glue\Kernel\ClassResolver\Config\BundleConfigResolver;
use SprykerTest\Glue\Kernel\ClassResolver\AbstractResolverTest;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Glue
 * @group Kernel
 * @group ClassResolver
 * @group Config
 * @group ConfigResolverTest
 * Add your own group annotations below this line
 */
class ConfigResolverTest extends AbstractResolverTest
{
    /**
     * @var string
     */
    protected $coreClass = 'Spryker\\Glue\\Kernel\\ClassResolver\\KernelConfig';

    /**
     * @var string
     */
    protected $projectClass = 'ProjectNamespace\\Glue\\Kernel\\ClassResolver\\KernelConfig';

    /**
     * @var string
     */
    protected $storeClass = 'ProjectNamespace\\Glue\\KernelDE\\ClassResolver\\KernelConfig';

    /**
     * @var string
     */
    protected $classPattern = '%namespace%\\Glue\\%bundle%%store%\\ClassResolver\\%bundle%Config';

    /**
     * @var string
     */
    protected $expectedExceptionClass = BundleConfigNotFoundException::class;

    /**
     * @param array $methods
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Glue\Kernel\ClassResolver\Config\BundleConfigResolver
     */
    protected function getResolverMock(array $methods)
    {
        $resolverMock = $this->getMockBuilder(BundleConfigResolver::class)->setMethods($methods)->getMock();

        return $resolverMock;
    }
}
