<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Yves\Twig;

use Codeception\Test\Unit;
use Spryker\Yves\Kernel\Container;
use Spryker\Yves\Twig\TwigDependencyProvider;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Yves
 * @group Twig
 * @group TwigDependencyProviderTest
 * Add your own group annotations below this line
 */
class TwigDependencyProviderTest extends Unit
{
    /**
     * @return void
     */
    public function testUtilTextServiceIsAdded()
    {
        $container = new Container();
        $twigDependencyProvider = new TwigDependencyProvider();
        $twigDependencyProvider->provideDependencies($container);

        $this->assertArrayHasKey(TwigDependencyProvider::SERVICE_UTIL_TEXT, $container);
    }
}
