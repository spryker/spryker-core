<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Service\UtilDateTime\ServiceProvider;

use Codeception\Test\Unit;
use Silex\Application;
use Spryker\Service\UtilDateTime\Model\DateTimeFormatterTwigExtension;
use Spryker\Service\UtilDateTime\ServiceProvider\DateTimeFormatterServiceProvider;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Service
 * @group UtilDateTime
 * @group ServiceProvider
 * @group DateTimeFormatterServiceProviderTest
 * Add your own group annotations below this line
 */
class DateTimeFormatterServiceProviderTest extends Unit
{
    /**
     * @return void
     */
    public function testRegisterAddsExtensionToTwig()
    {
        $applicationMock = $this->getApplicationMock();
        $dateTimeFormatterServiceProvider = new DateTimeFormatterServiceProvider();
        $dateTimeFormatterServiceProvider->register($applicationMock);

        $twig = $applicationMock['twig'];

        $this->assertTrue($twig->hasExtension(DateTimeFormatterTwigExtension::EXTENSION_NAME));
    }

    /**
     * @return \Silex\Application
     */
    protected function getApplicationMock()
    {
        $application = new Application();
        $application['twig'] = function () {
            return new Environment(new FilesystemLoader());
        };

        return $application;
    }
}
