<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Yves\Twig\Plugin\ServiceProvider;

use Codeception\Test\Unit;
use Silex\Application;
use Silex\Provider\TwigServiceProvider as SilexTwigServiceProvider;
use Spryker\Yves\Twig\Plugin\ServiceProvider\TwigServiceProvider;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Yves
 * @group Twig
 * @group Plugin
 * @group ServiceProvider
 * @group TwigServiceProviderTest
 * Add your own group annotations below this line
 */
class TwigServiceProviderTest extends Unit
{
    /**
     * @return void
     */
    public function testRegisterAddsFilesystemLoaderToApplication()
    {
        $application = new Application();

        $twigServiceProvider = new SilexTwigServiceProvider();
        $twigServiceProvider->register($application);

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

        $twigServiceProvider = new SilexTwigServiceProvider();
        $twigServiceProvider->register($application);

        $twigServiceProvider = new TwigServiceProvider();
        $twigServiceProvider->boot($application);

        $this->assertArrayNotHasKey('twig.loader.yves', $application);
    }
}
