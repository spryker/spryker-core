<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\RestRequestValidator\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\RestRequestValidator\Business\RestRequestValidatorBusinessFactory;
use Spryker\Zed\RestRequestValidator\Dependency\External\RestRequestValidatorToFilesystemAdapter;
use Spryker\Zed\RestRequestValidator\Dependency\External\RestRequestValidatorToFinderAdapter;
use Spryker\Zed\RestRequestValidator\Dependency\External\RestRequestValidatorToYamlAdapter;
use Spryker\Zed\RestRequestValidator\Dependency\Facade\RestRequestValidatorToStoreFacadeBridge;
use Spryker\Zed\RestRequestValidator\RestRequestValidatorConfig;

/**
 * Auto-generated group annotations
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
    /**
     * @var \SprykerTest\Zed\RestRequestValidator\RestRequestValidatorBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testBuildCacheWillCollectConfigsCorrectly(): void
    {
        // prepare
        $restRequestValidatorFacade = $this->tester->getLocator()->restRequestValidator()->facade();
        $mockFactory = $this->createMockFactory();
        $restRequestValidatorFacade->setFactory($mockFactory);

        // act
        $restRequestValidatorFacade->buildCache();

        // assert
        foreach ($mockFactory->getStoreFacade()->getAllStores() as $store) {
            $expectedYaml = $this->getExpectedResult($mockFactory, $store);
            $actualYaml = $this->getActualResult($mockFactory, $store);

            $this->assertEquals($expectedYaml, $actualYaml);
        }
    }

    /**
     * @return \Spryker\Zed\RestRequestValidator\Business\RestRequestValidatorBusinessFactory
     */
    protected function createMockFactory(): RestRequestValidatorBusinessFactory
    {
        $mockFactory = $this->createPartialMock(
            RestRequestValidatorBusinessFactory::class,
            [
                'getConfig',
                'getStoreFacade',
                'getFinder',
                'getFilesystem',
                'getYaml',
            ]
        );

        $mockFactory = $this->addMockConfig($mockFactory);
        $mockFactory = $this->addMockStoreFacade($mockFactory);
        $mockFactory = $this->addMockFinder($mockFactory);
        $mockFactory = $this->addMockFilesystem($mockFactory);
        $mockFactory = $this->addMockYaml($mockFactory);

        return $mockFactory;
    }

    /**
     * @param \Spryker\Zed\RestRequestValidator\Business\RestRequestValidatorBusinessFactory $mockFactory
     *
     * @return \Spryker\Zed\RestRequestValidator\Business\RestRequestValidatorBusinessFactory
     */
    protected function addMockConfig(RestRequestValidatorBusinessFactory $mockFactory): RestRequestValidatorBusinessFactory
    {
        $mockConfig = $this->createPartialMock(
            RestRequestValidatorConfig::class,
            [
                'getStorePathMask',
                'getProjectPathMask',
                'getCorePathMask',
                'getCacheFilePathPattern',
            ]
        );
        $mockConfig
            ->method('getStorePathMask')
            ->willReturn(
                $this->getFixtureDirectory('Project%s')
            );
        $mockConfig
            ->method('getProjectPathMask')
            ->willReturn(
                $this->getFixtureDirectory('Project')
            );
        $mockConfig
            ->method('getCorePathMask')
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
     * @param \Spryker\Zed\RestRequestValidator\Business\RestRequestValidatorBusinessFactory $mockFactory
     *
     * @return \Spryker\Zed\RestRequestValidator\Business\RestRequestValidatorBusinessFactory
     */
    protected function addMockStoreFacade(RestRequestValidatorBusinessFactory $mockFactory): RestRequestValidatorBusinessFactory
    {
        $mockStoreFacade = $this->createPartialMock(
            RestRequestValidatorToStoreFacadeBridge::class,
            [
                'getAllStores',
            ]
        );

        $mockStoreFacade
            ->method('getAllStores')
            ->willReturn(
                [
                    (new StoreTransfer())->fromArray(['name' => 'DE']),
                    (new StoreTransfer())->fromArray(['name' => 'AT']),
                ]
            );

        $mockFactory
            ->method('getStoreFacade')
            ->willReturn($mockStoreFacade);

        return $mockFactory;
    }

    /**
     * @param string|null $level
     *
     * @return string
     */
    private function getFixtureDirectory($level = null)
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
     * @param \Spryker\Zed\RestRequestValidator\Business\RestRequestValidatorBusinessFactory $mockFactory
     *
     * @return \Spryker\Zed\RestRequestValidator\Business\RestRequestValidatorBusinessFactory
     */
    protected function addMockFinder(RestRequestValidatorBusinessFactory $mockFactory): RestRequestValidatorBusinessFactory
    {
        $mockFactory
            ->method('getFinder')
            ->willReturn(
                new RestRequestValidatorToFinderAdapter()
            );

        return $mockFactory;
    }

    /**
     * @param \Spryker\Zed\RestRequestValidator\Business\RestRequestValidatorBusinessFactory $mockFactory
     *
     * @return \Spryker\Zed\RestRequestValidator\Business\RestRequestValidatorBusinessFactory
     */
    protected function addMockFilesystem(RestRequestValidatorBusinessFactory $mockFactory): RestRequestValidatorBusinessFactory
    {
        $mockFactory
            ->method('getFilesystem')
            ->willReturn(
                new RestRequestValidatorToFilesystemAdapter()
            );

        return $mockFactory;
    }

    /**
     * @param \Spryker\Zed\RestRequestValidator\Business\RestRequestValidatorBusinessFactory $mockFactory
     *
     * @return \Spryker\Zed\RestRequestValidator\Business\RestRequestValidatorBusinessFactory
     */
    protected function addMockYaml(RestRequestValidatorBusinessFactory $mockFactory): RestRequestValidatorBusinessFactory
    {
        $mockFactory
            ->method('getYaml')
            ->willReturn(
                new RestRequestValidatorToYamlAdapter()
            );

        return $mockFactory;
    }

    /**
     * @param \Spryker\Zed\RestRequestValidator\Business\RestRequestValidatorBusinessFactory $mockFactory
     * @param \Generated\Shared\Transfer\StoreTransfer $store
     *
     * @return array
     */
    protected function getExpectedResult(RestRequestValidatorBusinessFactory $mockFactory, StoreTransfer $store): array
    {
        return $expected = $mockFactory->getYaml()->parseFile(
            $this->getFixtureDirectory('Merged') . DIRECTORY_SEPARATOR . $store->getName() . DIRECTORY_SEPARATOR . 'result.validation.yaml'
        );
    }

    /**
     * @param \Spryker\Zed\RestRequestValidator\Business\RestRequestValidatorBusinessFactory $mockFactory
     * @param \Generated\Shared\Transfer\StoreTransfer $store
     *
     * @return array
     */
    protected function getActualResult(RestRequestValidatorBusinessFactory $mockFactory, StoreTransfer $store): array
    {
        return $expected = $mockFactory->getYaml()->parseFile(
            $this->getFixtureDirectory() . DIRECTORY_SEPARATOR . $store->getName() . DIRECTORY_SEPARATOR . 'validation.cache'
        );
    }
}
