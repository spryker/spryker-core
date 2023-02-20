<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SearchHttp;

use Codeception\Test\Unit;
use Spryker\Client\Kernel\Container;
use Spryker\Client\Queue\QueueDependencyProvider;
use Spryker\Zed\SearchHttp\Dependency\Facade\SearchHttpToStoreFacadeInterface;
use Spryker\Zed\SearchHttp\SearchHttpDependencyProvider;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SearchHttp
 * @group Facade
 * @group SearchHttpFacadeTest
 * Add your own group annotations below this line
 */
class SearchHttpFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\SearchHttp\SearchHttpBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->tester->ensureSearchHttpConfigTableIsEmpty();

        $this->tester->setDependency(QueueDependencyProvider::QUEUE_ADAPTERS, function (Container $container) {
            return [
                $container->getLocator()->rabbitMq()->client()->createQueueAdapter(),
            ];
        });

        $this->mockStoreGetCurrentStoreMethod();
    }

    /**
     * @return void
     */
    public function testSearchHttpConfigSuccessfullyPublished(): void
    {
        // Arrange
        $searchHttpConfigTransfer = $this->tester->createSearchHttpConfigTransfer();

        // Act
        $this->tester->getFacade()->publishSearchHttpConfig($searchHttpConfigTransfer, 'test_store_reference');

        // Assert
        $savedSearchHttpConfig = $this->tester->findSearchHttpConfigByStoreName('test_store_name');

        $this->tester
            ->assertSearchHttpConfigStoredProperly(
                $searchHttpConfigTransfer,
                $savedSearchHttpConfig,
            );
    }

    /**
     * @return void
     */
    public function testSearchHttpConfigSuccessfullyRePublished(): void
    {
        // Arrange
        $this->tester->haveSearchHttpConfig(
            [
                'search_ccp_configs' => [
                    'applicationId' => 'test_application_id',
                    'url' => 'test_url',
                ],
            ],
        );

        $searchHttpConfigTransfer = $this->tester->createSearchHttpConfigTransfer(
            [
                'application_id' => 'test_application_id',
                'url' => 'new_test_url',
            ],
        );

        // Act
        $this->tester->getFacade()->publishSearchHttpConfig($searchHttpConfigTransfer, 'test_store_reference');

        // Assert
        $savedSearchHttpConfig = $this->tester->findSearchHttpConfigByStoreName('test_store_name');

        $this->tester->assertSearchHttpConfigStoredProperly(
            $searchHttpConfigTransfer,
            $savedSearchHttpConfig,
        );
    }

    /**
     * @return void
     */
    public function testSearchHttpConfigSuccessfullyUnpublished(): void
    {
        // Arrange
        $this->tester->haveSearchHttpConfig(
            [
                'search_ccp_configs' => [
                    'applicationId' => 'test_application_id',
                    'url' => 'test_url',
                ],
            ],
        );

        // Act
        $this->tester->getFacade()->unpublishSearchHttpConfig('test_store_reference', 'test_application_id');

        // Assert
        $savedSearchHttpConfig = $this->tester->findSearchHttpConfigByStoreName('test_store_name');

        $this->tester->assertSearchHttpConfigRemovedProperly('test_store_reference', $savedSearchHttpConfig);
    }

    /**
     * @return void
     */
    public function mockStoreGetCurrentStoreMethod(): void
    {
        $searchHttpToStoreFacadeBridge = $this
            ->getMockBuilder(SearchHttpToStoreFacadeInterface::class)
            ->getMock();

        $searchHttpToStoreFacadeBridge
            ->method('getStoreByStoreReference')
            ->willReturn($this->tester->createStoreWithStoreReference());

        $this->tester->setDependency(
            SearchHttpDependencyProvider::FACADE_STORE,
            $searchHttpToStoreFacadeBridge,
        );
    }
}
