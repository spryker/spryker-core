<?php
/**
 * Created by PhpStorm.
 * User: devromans
 * Date: 2019-02-06
 * Time: 14:32
 */

namespace SprykerTest\Zed\Translator\Business;


use Codeception\TestCase\Test;
use Spryker\Shared\Kernel\Communication\Application;
use Spryker\Shared\Kernel\Store;
use Spryker\Zed\Translator\Business\TranslatorBusinessFactory;
use Spryker\Zed\Translator\Business\TranslatorFacadeInterface;
use Spryker\Zed\Translator\TranslatorConfig;

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
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Translator\TranslatorConfig
     */
    protected function getConfigMock()
    {
        $configMock = $this->getMockBuilder(TranslatorConfig::class)
            ->setMethods(['getZedTranslationFilePathPatterns', 'getZedTranslatorCacheDirectory'])
            ->getMock();

        $configMock->method('getZedTranslationFilePathPatterns')
            ->willReturn([codecept_data_dir('[a-z][a-z]_[A-Z][A-Z].csv')]);

        $configMock->method('getZedTranslatorCacheDirectory')
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
