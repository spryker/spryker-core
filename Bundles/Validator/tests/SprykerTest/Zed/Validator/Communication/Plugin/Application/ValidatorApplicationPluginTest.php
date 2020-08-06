<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Validator\Communication\Plugin\Application;

use Codeception\Test\Unit;
use Spryker\Service\Container\ContainerInterface;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Validator\Communication\Plugin\Application\ValidatorApplicationPlugin;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Validator
 * @group Communication
 * @group Plugin
 * @group Application
 * @group ValidatorApplicationPluginTest
 * Add your own group annotations below this line
 */
class ValidatorApplicationPluginTest extends Unit
{
    protected const SERVICE_VALIDATOR = 'validator';

    /**
     * @var \SprykerTest\Zed\Validator\ValidatorCommunicationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function tesValidatorApplicationPluginSetsValidationService(): void
    {
        //Arrange
        $plugin = new ValidatorApplicationPlugin();
        $container = $this->createContainer();

        //Act
        $container = $plugin->provide($container);

        //Arrange
        $this->assertTrue($container->has(static::SERVICE_VALIDATOR));
        $this->assertInstanceOf(ValidatorInterface::class, $container->get(static::SERVICE_VALIDATOR));
    }

    /**
     * @return \Spryker\Service\Container\ContainerInterface
     */
    protected function createContainer(): ContainerInterface
    {
        return new Container();
    }
}
