<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\RestRequestValidator\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\StoreTransfer;
use PHPUnit\Framework\MockObject\MockObject;
use Spryker\Zed\RestRequestValidator\Business\RestRequestValidatorBusinessFactory;
use Spryker\Zed\RestRequestValidator\Dependency\External\RestRequestValidatorToFilesystemAdapter;
use Spryker\Zed\RestRequestValidator\Dependency\External\RestRequestValidatorToFinderAdapter;
use Spryker\Zed\RestRequestValidator\Dependency\External\RestRequestValidatorToYamlAdapter;
use Spryker\Zed\RestRequestValidator\Dependency\Store\RestRequestValidatorToStoreBridge;
use Spryker\Zed\RestRequestValidator\RestRequestValidatorConfig;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group RestRequestValidator
 * @group Business
 * @group Facade
 * @group RestRequestValidatorFacadeTest
 * Add your own group annotations below this line
 */
class RestRequestValidatorFacadeTest extends Unit
{
    protected const STORE_DE = 'DE';
    protected const STORE_AT = 'AT';

    /**
     * @var \SprykerTest\Zed\RestRequestValidator\RestRequestValidatorBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function tearDown(): void
    {
        parent::tearDown();

        $this->deleteDirectory($this->getFixtureDirectory('Result'));
    }

    /**
     * @return void
     */
    public function testBuildCacheWillCollectConfigsCorrectly(): void
    {
        $restRequestValidatorFacade = $this->tester->getLocator()->restRequestValidator()->facade();
        $mockFactory = $this->createMockFactory();
        $restRequestValidatorFacade->setFactory($mockFactory);

        $restRequestValidatorFacade->buildValidationCache();

        $storeTransferDe = (new StoreTransfer())->setName(static::STORE_DE);
        $expectedYamlDe = $this->getExpectedResult($storeTransferDe);
        $actualYamlDe = $this->getActualResult($storeTransferDe);

        $this->assertEquals($expectedYamlDe, $actualYamlDe);

        $storeTransferAt = (new StoreTransfer())->setName(static::STORE_AT);
        $expectedYamlAt = $this->getExpectedResult($storeTransferAt);
        $actualYamlAt = $this->getActualResult($storeTransferAt);

        $this->assertEquals($expectedYamlAt, $actualYamlAt);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    protected function createMockFactory(): MockObject
    {
        $mockFactory = $this->createPartialMock(
            RestRequestValidatorBusinessFactory::class,
            [
                'getConfig',
                'getFinderAdapter',
                'getFilesystemAdapter',
                'getYamlAdapter',
                'getStore',
            ]
        );

        $mockFactory = $this->addMockConfig($mockFactory);
        $mockFactory = $this->addMockFinderAdapter($mockFactory);
        $mockFactory = $this->addMockFilesystemAdapter($mockFactory);
        $mockFactory = $this->addMockYamlAdapter($mockFactory);
        $mockFactory = $this->addStore($mockFactory);

        return $mockFactory;
    }

    /**
     * @param \PHPUnit\Framework\MockObject\MockObject $mockFactory
     *
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    protected function addMockConfig(MockObject $mockFactory): MockObject
    {
        $mockConfig = $this->createPartialMock(
            RestRequestValidatorConfig::class,
            [
                'getStorePathPattern',
                'getProjectPathPattern',
                'getCorePathPattern',
                'getCacheFilePathPattern',
            ]
        );
        $mockConfig
            ->method('getStorePathPattern')
            ->willReturn(
                $this->getFixtureDirectory('Project%s')
            );
        $mockConfig
            ->method('getProjectPathPattern')
            ->willReturn(
                $this->getFixtureDirectory('Project')
            );
        $mockConfig
            ->method('getCorePathPattern')
            ->willReturn(
                $this->getFixtureDirectory('Vendor')
            );
        $mockConfig
            ->method('getCacheFilePathPattern')
            ->willReturn(
                $this->getFixtureDirectory('Result') . '%s' . DIRECTORY_SEPARATOR . 'validation.cache'
            );

        $mockFactory
            ->method('getConfig')
            ->willReturn($mockConfig);

        return $mockFactory;
    }

    /**
     * @param \PHPUnit\Framework\MockObject\MockObject $mockFactory
     *
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    protected function addStore(MockObject $mockFactory): MockObject
    {
        $mockStore = $this->createPartialMock(
            RestRequestValidatorToStoreBridge::class,
            [
                'getAllowedStores',
            ]
        );

        $mockStore
            ->method('getAllowedStores')
            ->willReturn(
                [
                    static::STORE_DE,
                    static::STORE_AT,
                ]
            );

        $mockFactory
            ->method('getStore')
            ->willReturn($mockStore);

        return $mockFactory;
    }

    /**
     * @param string|null $level
     *
     * @return string
     */
    protected function getFixtureDirectory(?string $level = null): string
    {
        $pathParts = [
            __DIR__,
            'Fixtures',
            'Validation',
        ];

        if ($level) {
            $pathParts[] = $level;
        }

        return implode(DIRECTORY_SEPARATOR, $pathParts) . DIRECTORY_SEPARATOR;
    }

    /**
     * @param \PHPUnit\Framework\MockObject\MockObject $mockFactory
     *
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    protected function addMockFinderAdapter(MockObject $mockFactory): MockObject
    {
        $mockFactory
            ->method('getFinderAdapter')
            ->willReturn(
                new RestRequestValidatorToFinderAdapter()
            );

        return $mockFactory;
    }

    /**
     * @param \PHPUnit\Framework\MockObject\MockObject $mockFactory
     *
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    protected function addMockFilesystemAdapter(MockObject $mockFactory): MockObject
    {
        $mockFactory
            ->method('getFilesystemAdapter')
            ->willReturn(
                new RestRequestValidatorToFilesystemAdapter()
            );

        return $mockFactory;
    }

    /**
     * @param \PHPUnit\Framework\MockObject\MockObject $mockFactory
     *
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    protected function addMockYamlAdapter(MockObject $mockFactory): MockObject
    {
        $mockFactory
            ->method('getYamlAdapter')
            ->willReturn(
                new RestRequestValidatorToYamlAdapter()
            );

        return $mockFactory;
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $store
     *
     * @return array
     */
    protected function getExpectedResult(StoreTransfer $store): array
    {
        return $expected = (new RestRequestValidatorToYamlAdapter())->parseFile(
            $this->getFixtureDirectory('Merged') . $store->getName() . DIRECTORY_SEPARATOR . 'result.validation.yaml'
        );
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $store
     *
     * @return array
     */
    protected function getActualResult(StoreTransfer $store): array
    {
        return $expected = (new RestRequestValidatorToYamlAdapter())->parseFile(
            $this->getFixtureDirectory('Result') . $store->getName() . DIRECTORY_SEPARATOR . 'validation.cache'
        );
    }

    /**
     * @param string $dir
     *
     * @return bool
     */
    protected function deleteDirectory(string $dir): bool
    {
        $files = array_diff(scandir($dir), ['.', '..']);
        foreach ($files as $file) {
            if (is_dir($dir . DIRECTORY_SEPARATOR . $file)) {
                $this->deleteDirectory($dir . DIRECTORY_SEPARATOR . $file);
            } else {
                unlink($dir . DIRECTORY_SEPARATOR . $file);
            }
        }

        return rmdir($dir);
    }
}
