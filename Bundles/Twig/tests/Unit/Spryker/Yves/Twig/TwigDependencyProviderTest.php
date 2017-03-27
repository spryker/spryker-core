<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Yves\Twig;

use PHPUnit_Framework_TestCase;
use Spryker\Yves\Kernel\Container;
use Spryker\Yves\Twig\TwigDependencyProvider;

/**
 * @group Unit
 * @group Spryker
 * @group Yves
 * @group Twig
 * @group TwigDependencyProviderTest
 */
class TwigDependencyProviderTest extends PHPUnit_Framework_TestCase
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
