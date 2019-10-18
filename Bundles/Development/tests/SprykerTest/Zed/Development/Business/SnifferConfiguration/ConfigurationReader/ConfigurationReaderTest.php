<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Development\Business\SnifferConfiguration\ConfigurationReader;

use Codeception\Test\Unit;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Development
 * @group Business
 * @group SnifferConfiguration
 * @group ConfigurationReader
 * @group ConfigurationReaderTest
 * Add your own group annotations below this line
 */
class ConfigurationReaderTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\Development\DevelopmentBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testAclModuleHasToolingFileAndParseIt(): void
    {
        $data = $this->tester->createConfigurationReader()->getModuleConfigurationByAbsolutePath(
            $this->tester->getZedAclModulePath()
        );

        $this->assertNotEmpty($data);
    }

    /**
     * @return void
     */
    public function testDiscountModuleHasToolingFileAndParseIt(): void
    {
        $data = $this->tester->createConfigurationReader()->getModuleConfigurationByAbsolutePath(
            $this->tester->getZedDiscountModulePath()
        );

        $this->assertNotEmpty($data);
    }

    /**
     * @return void
     */
    public function testCustomerModuleHasToolingFileAndParseIt(): void
    {
        $data = $this->tester->createConfigurationReader()->getModuleConfigurationByAbsolutePath(
            $this->tester->getZedCustomerModulePath()
        );

        $this->assertNotEmpty($data);
    }

    /**
     * @return void
     */
    public function testCountryModuleHasToolingFileWithWrongExtension(): void
    {
        $data = $this->tester->createConfigurationReader()->getModuleConfigurationByAbsolutePath(
            $this->tester->getZedCountryPath()
        );

        $this->assertEmpty($data);
    }

    /**
     * @return void
     */
    public function testProductModuleDoesNotHaveToolingFile(): void
    {
        $data = $this->tester->createConfigurationReader()->getModuleConfigurationByAbsolutePath(
            $this->tester->getZedProductModulePath()
        );

        $this->assertEmpty($data);
    }

    /**
     * @return void
     */
    public function testCustomFolderHasToolingFileAndParseIt(): void
    {
        $data = $this->tester->createConfigurationReader()->getModuleConfigurationByAbsolutePath(
            $this->tester->getZedCustomPath()
        );

        $this->assertNotEmpty($data);
    }
}
