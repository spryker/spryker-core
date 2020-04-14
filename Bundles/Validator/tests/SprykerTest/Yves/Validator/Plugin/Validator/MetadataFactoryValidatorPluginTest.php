<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Yves\Validator\Plugin\Validator;

use Codeception\Test\Unit;
use Spryker\Service\Container\ContainerInterface;
use Spryker\Yves\Kernel\Container;
use Spryker\Yves\Validator\Plugin\Validator\MetadataFactoryValidatorPlugin;
use Symfony\Component\Validator\ValidatorBuilder;
use Symfony\Component\Validator\ValidatorBuilderInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Yves
 * @group Validator
 * @group Plugin
 * @group Validator
 * @group MetadataFactoryValidatorPluginTest
 * Add your own group annotations below this line
 */
class MetadataFactoryValidatorPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Yves\Validator\ValidatorYvesTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testMetadataFactoryPluginExtendsValidationBuilderWithMetadataFactory(): void
    {
        //Arrange
        $plugin = new MetadataFactoryValidatorPlugin();
        $validatorBuilder = $this->createValidatorBuilder();
        $container = $this->createContainer();

        //Act
        $result = $plugin->extend($validatorBuilder, $container);

        //Arrange
        $this->assertInstanceOf(ValidatorBuilderInterface::class, $result);
    }

    /**
     * @return \Symfony\Component\Validator\ValidatorBuilderInterface
     */
    protected function createValidatorBuilder(): ValidatorBuilderInterface
    {
        return new ValidatorBuilder();
    }

    /**
     * @return \Spryker\Service\Container\ContainerInterface
     */
    protected function createContainer(): ContainerInterface
    {
        return new Container();
    }
}
