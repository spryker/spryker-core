<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Service\Translator;

use Codeception\TestCase\Test;
use Spryker\Service\Translator\TranslatorConfig;
use Spryker\Service\Translator\TranslatorServiceFactory;
use Spryker\Service\Translator\TranslatorServiceInterface;
use Spryker\Shared\Kernel\Communication\Application;
use Spryker\Shared\Kernel\Store;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Service
 * @group Translator
 * @group TranslatorServiceTest
 * Add your own group annotations below this line
 */
class TranslatorServiceTest extends Test
{
    /**
     * @var \SprykerTest\Service\Translator\TranslatorServiceTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testTranslatorTranslatesString(): void
    {
        // Act
        $translatedString = $this->getServiceMock()->translate('Customers');

        // Assert
        $this->tester->assertSame('Kunden', $translatedString);
    }

    /**
     * @return void
     */
    public function testCacheGeneratorGeneratesCache(): void
    {
        // Assign
        $this->tester->clearOutputDirectory();

        // Act
        $this->getServiceMock()->generateTranslationCache();

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
        $this->getServiceMock()->cleanTranslationCache();

        // Assert
        $this->tester->assertEquals(0, $this->tester->findFiles(codecept_output_dir())->count());
    }

    /**
     * @return void
     */
    public function testTranslationChecksTranslations(): void
    {
        // Assign
        $locale = $this->getFactoryMock()->getApplication()['locale'];

        // Assert
        $this->tester->assertTrue($this->getServiceMock()->hasTranslation('Customers', $locale));
        $this->tester->assertFalse($this->getServiceMock()->hasTranslation(time(), $locale));
    }

    /**
     * @return \Spryker\Service\Translator\TranslatorServiceInterface
     */
    protected function getServiceMock(): TranslatorServiceInterface
    {
        /** @var \Spryker\Service\Translator\TranslatorServiceInterface|\Spryker\Service\Kernel\AbstractService $service */
        $service = $this->tester->getService();
        $service->setFactory($this->getFactoryMock());

        return $service;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Service\Translator\TranslatorServiceFactory
     */
    protected function getFactoryMock()
    {
        $factoryMock = $this->getMockBuilder(TranslatorServiceFactory::class)
            ->setMethods(['getConfig', 'getApplication', 'getStore'])
            ->getMock();

        $factoryMock->method('getConfig')
            ->willReturn($this->getConfigMock());

        $factoryMock->method('getApplication')
            ->willReturn($this->getApplicationMock());

        $factoryMock->method('getStore')
            ->willReturn($this->getStoreMock());

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
     * @return \Spryker\Shared\Kernel\Communication\Application
     */
    protected function getApplicationMock(): Application
    {
        $application = new Application();
        $application['locale'] = 'de_DE';

        return $application;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Service\Translator\TranslatorConfig
     */
    protected function getConfigMock()
    {
        $configMock = $this->getMockBuilder(TranslatorConfig::class)
            ->setMethods(['getTranslationFilePathPatterns', 'getCacheDirectory'])
            ->getMock();

        $configMock->method('getTranslationFilePathPatterns')
            ->willReturn([codecept_data_dir('[a-z][a-z]_[A-Z][A-Z].csv')]);

        $configMock->method('getCacheDirectory')
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
