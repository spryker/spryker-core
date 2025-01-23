<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Twig\Extension;

use Codeception\Test\Unit;
use Spryker\Shared\Twig\Extension\EnvironmentCoreExtension;
use SprykerTest\Shared\Twig\TwigSharedTester;
use Twig\Loader\ArrayLoader;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Shared
 * @group Twig
 * @group Extension
 * @group EnvironmentCoreExtensionTest
 * Add your own group annotations below this line
 */
class EnvironmentCoreExtensionTest extends Unit
{
    /**
     * @var \SprykerTest\Shared\Twig\TwigSharedTester
     */
    protected TwigSharedTester $tester;

    /**
     * @return void
     */
    public function testFilterShouldExcludeSystemPhpFunctionFromExecutions(): void
    {
        // Arrange
        $environmentCoreExtension = new EnvironmentCoreExtension();
        $twig = $this->tester->createTwigEnvironment(new ArrayLoader([
            'test' => "{{ ['id'] | map('system') | join }} {{ ['php -v'] | reduce('exec') | join }} {{ ['  php  '] | map(value => value | trim) | join }}",
        ]));
        $environmentCoreExtension->extend($twig);

        // Act
        $output = $twig->render('test');

        // Assert
        $this->assertSame('id php -v php', $output);
    }
}
