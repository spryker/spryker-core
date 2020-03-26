<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Yves\Security;

use Codeception\Test\Unit;
use Spryker\Yves\Kernel\Container;
use Spryker\Yves\Security\SecurityDependencyProvider;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Yves
 * @group Security
 * @group SecurityDependencyProviderTest
 * Add your own group annotations below this line
 */
class SecurityDependencyProviderTest extends Unit
{
    /**
     * @return void
     */
    public function testProvideAddsSecurityPlugins(): void
    {
        $container = new Container();
        $securityDependencyProvider = new SecurityDependencyProvider();
        $securityDependencyProvider->provideDependencies($container);

        $this->assertIsArray($container->get(SecurityDependencyProvider::PLUGINS_SECURITY));
    }
}
