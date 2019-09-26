<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Development\Business\SnifferConfiguration\Builder;

use Codeception\Test\Unit;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Development
 * @group Business
 * @group SnifferConfiguration
 * @group Builder
 * @group ArchitectureSnifferConfigurationBuilderTest
 * Add your own group annotations below this line
 */
class ArchitectureSnifferConfigurationBuilderTest extends Unit
{
    protected const CONFIG_PRIORITY_NAME = 'priority';
    protected const CONFIG_IGNORE_ERRORS = 'ignoreErrors';

    /**
     * @var \SprykerTest\Zed\Development\DevelopmentBusinessTester
     */
    protected $tester;

    /**
     * @var \Spryker\Zed\Development\Business\SnifferConfiguration\ConfigurationReader\ConfigurationReaderInterface
     */
    protected $configurationReader;

    /**
     * @return void
     */
    public function testAclModuleHasPriorityDifferentFromDefaultPriority(): void
    {
        $aclModuleArchitectureSnifferConfig = $this->tester->createArchitectureSnifferConfigurationBuilder()->getConfiguration(
            $this->tester->getZedAclModulePath()
        );

        $aclPriority = $aclModuleArchitectureSnifferConfig[static::CONFIG_PRIORITY_NAME];

        $this->assertNotEquals($aclPriority, $this->tester->getDefaultPriority());
    }

    /**
     * @return void
     */
    public function testCustomerModuleWillBeSkipped(): void
    {
        $customerModuleArchitectureSnifferConfig = $this->tester->createArchitectureSnifferConfigurationBuilder()->getConfiguration(
            $this->tester->getZedCustomerModulePath()
        );

        $this->assertEmpty($customerModuleArchitectureSnifferConfig);
    }

    /**
     * @return void
     */
    public function testDiscountModuleHasDefaultPriorityBecauseDoesNotHavePriorityParamInConfigFile(): void
    {
        $discountModuleArchitectureSnifferConfig = $this->tester->createArchitectureSnifferConfigurationBuilder()->getConfiguration(
            $this->tester->getZedDiscountModulePath()
        );

        $discountPriority = $discountModuleArchitectureSnifferConfig[static::CONFIG_PRIORITY_NAME];

        $this->assertEquals($discountPriority, $this->tester->getDefaultPriority());
    }

    /**
     * @return void
     */
    public function testCountryModuleHasDefaultPriorityBecauseConfigFileHasWrongExtension(): void
    {
        $countryModuleArchitectureSnifferConfig = $this->tester->createArchitectureSnifferConfigurationBuilder()->getConfiguration(
            $this->tester->getZedCountryPath()
        );

        $countryPriority = $countryModuleArchitectureSnifferConfig[static::CONFIG_PRIORITY_NAME];

        $this->assertEquals($countryPriority, $this->tester->getDefaultPriority());
    }

    /**
     * @return void
     */
    public function testProductModuleHasDefaultPriorityBecauseConfigFileDoesNotExist(): void
    {
        $productModuleArchitectureSnifferConfig = $this->tester->createArchitectureSnifferConfigurationBuilder()->getConfiguration(
            $this->tester->getZedProductModulePath()
        );

        $productPriority = $productModuleArchitectureSnifferConfig[static::CONFIG_PRIORITY_NAME];

        $this->assertEquals($productPriority, $this->tester->getDefaultPriority());
    }

    /**
     * @return void
     */
    public function testCustomDirectoryHasDefaultPriorityBecauseConfigFileDoesNotHaveArchitectureSnifferBlock(): void
    {
        $customDirectoryArchitectureSnifferConfig = $this->tester->createArchitectureSnifferConfigurationBuilder()->getConfiguration(
            $this->tester->getZedCustomPath()
        );

        $customDirectoryPriority = $customDirectoryArchitectureSnifferConfig[static::CONFIG_PRIORITY_NAME];

        $this->assertEquals($customDirectoryPriority, $this->tester->getDefaultPriority());
    }

    /**
     * @return void
     */
    public function testDiscountModuleHasIgnoreErrorPatternsInConfigFile(): void
    {
        $discountModuleArchitectureSnifferConfig = $this->tester->createArchitectureSnifferConfigurationBuilder()->getConfiguration(
            $this->tester->getZedDiscountModulePath()
        );

        $discountModuleIgnoreErrorPatterns = $discountModuleArchitectureSnifferConfig[static::CONFIG_IGNORE_ERRORS];

        $this->assertNotEmpty($discountModuleIgnoreErrorPatterns);
    }
}
