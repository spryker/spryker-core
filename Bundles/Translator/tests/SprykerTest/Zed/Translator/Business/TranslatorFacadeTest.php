<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Translator\Business;

use Codeception\TestCase\Test;
use Spryker\Shared\Kernel\Store;
use Spryker\Zed\Translator\Business\TranslatorBusinessFactory;
use Spryker\Zed\Translator\Business\TranslatorFacadeInterface;
use Spryker\Zed\Translator\Dependency\Facade\TranslatorToLocaleFacadeBridge;
use Spryker\Zed\Translator\TranslatorConfig;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Translator
 * @group Business
 * @group Facade
 * @group TranslatorFacadeTest
 * Add your own group annotations below this line
 */
class TranslatorFacadeTest extends Test
{
    /**
     * @var \SprykerTest\Zed\Translator\TranslatorBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testCacheGeneratorGeneratesCache(): void
    {
        // Assign
        $this->tester->clearOutputDirectory();

        // Act
        $this->getFacadeMock()->generateTranslationCache();

        // Assert
        $this->tester->assertEquals(2, $this->tester->findFiles(codecept_output_dir())->count());
    }

    /**
     * @return void
     */
    public function testCacheCleanerCleansCache(): void
    {
        // Assign
        $this->tester->clearOutputDirectory();

        file_put_contents(codecept_output_dir(time() . '.csv'), time());

        // Act
        $this->getFacadeMock()->cleanTranslationCache();

        // Assert
        $this->tester->assertEquals(0, $this->tester->findFiles(codecept_output_dir())->count());
    }

    /**
     * @return \Spryker\Zed\Translator\Business\TranslatorFacadeInterface
     */
    protected function getFacadeMock(): TranslatorFacadeInterface
    {
        /** @var \Spryker\Zed\Translator\Business\TranslatorFacadeInterface|\Spryker\Zed\Kernel\Business\AbstractFacade $facade */
        $facade = $this->tester->getFacade();
        $facade->setFactory($this->getFactoryMock());

        return $facade;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Translator\Business\TranslatorBusinessFactory
     */
    protected function getFactoryMock()
    {
        $factoryMock = $this->getMockBuilder(TranslatorBusinessFactory::class)
            ->setMethods(['getConfig', 'getStore', 'getLocaleFacade'])
            ->getMock();

        $factoryMock->method('getConfig')
            ->willReturn($this->getConfigMock());

        $factoryMock->method('getStore')
            ->willReturn($this->getStoreMock());

        $factoryMock->method('getLocaleFacade')
            ->willReturn(new TranslatorToLocaleFacadeBridge($this->tester->getLocator()->locale()->facade()));

        return $factoryMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Shared\Kernel\Store
     */
    protected function getStoreMock()
    {
        $storeMock = $this->getMockBuilder(Store::class)
            ->disableOriginalConstructor()
            ->setMethods(['getLocales'])
            ->getMock();

        $storeMock->method('getLocales')
            ->willReturn(['de' => 'de_DE']);

        return $storeMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Translator\TranslatorConfig
     */
    protected function getConfigMock()
    {
        $configMock = $this->getMockBuilder(TranslatorConfig::class)
            ->setMethods(['getTranslationFilePathPatterns', 'getTranslatorCacheDirectory'])
            ->getMock();

        $configMock->method('getTranslationFilePathPatterns')
            ->willReturn([codecept_data_dir('[a-z][a-z]_[A-Z][A-Z].csv')]);

        $configMock->method('getTranslatorCacheDirectory')
            ->willReturn(codecept_output_dir());

        return $configMock;
    }

    /**
     * @return void
     */
    protected function tearDown(): void
    {
        $this->tester->clearOutputDirectory();

        parent::tearDown();
    }
}
