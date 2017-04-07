<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Twig;

use PHPUnit_Framework_TestCase;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Twig\TwigDependencyProvider;

/**
 * @group Unit
 * @group Spryker
 * @group Zed
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
        $twigDependencyProvider->provideCommunicationLayerDependencies($container);

        $this->assertArrayHasKey(TwigDependencyProvider::SERVICE_UTIL_TEXT, $container);
    }

}
