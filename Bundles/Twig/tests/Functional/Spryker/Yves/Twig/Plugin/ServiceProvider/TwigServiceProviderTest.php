<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\Spryker\Yves\Twig\Plugin\ServiceProvider;

use PHPUnit_Framework_TestCase;
use Silex\Application;
use Spryker\Yves\Twig\Plugin\ServiceProvider\TwigServiceProvider;

/**
 * @group Functional
 * @group Spryker
 * @group Yves
 * @group Twig
 * @group Plugin
 * @group ServiceProvider
 * @group TwigServiceProviderTest
 */
class TwigServiceProviderTest extends PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testRegisterAddsFilesystemLoaderToApplication()
    {
        $application = new Application();
        $twigServiceProvider = new TwigServiceProvider();
        $twigServiceProvider->register($application);

        $this->assertArrayHasKey('twig.loader.yves', $application);
    }

    /**
     * @return void
     */
    public function testBootDoesNothing()
    {
        $application = new Application();
        $twigServiceProvider = new TwigServiceProvider();
        $twigServiceProvider->boot($application);

        $this->assertArrayNotHasKey('twig.loader.yves', $application);
    }

}
