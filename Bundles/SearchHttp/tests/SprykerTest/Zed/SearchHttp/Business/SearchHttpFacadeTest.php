<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SearchHttp;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\SearchHttpConfigTransfer;
use Spryker\Client\Kernel\Container;
use Spryker\Client\Queue\QueueDependencyProvider;

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
    }

    /**
     * @return void
     */
    public function testSearchHttpConfigSuccessfullySaved(): void
    {
        // Arrange
        $searchHttpConfigTransfer = $this->tester->createSearchHttpConfigTransfer();
        $storeTransfer = $this->tester->getAllowedStore();

        // Act
        $this->tester->getFacade()->saveSearchHttpConfig($searchHttpConfigTransfer);

        // Assert
        $savedSearchHttpConfig = $this->tester->findSearchHttpConfig();

        $this->tester->assertSearchHttpConfigStoredProperly(
            $searchHttpConfigTransfer,
            $savedSearchHttpConfig,
        );
    }

    /**
     * @return void
     */
    public function testSearchHttpConfigSuccessfullyReSaved(): void
    {
        // Arrange
        $applicationId = 'test_application_id';
        $storeTransfer = $this->tester->getAllowedStore();
        $this->tester->haveSearchHttpConfig(
            [
                'search_ccp_configs' => [
                    SearchHttpConfigTransfer::APPLICATION_ID => $applicationId,
                    SearchHttpConfigTransfer::URL => 'test_url',
                ],
            ],
            $storeTransfer->getName(),
        );

        $newSearchHttpConfigTransfer = $this->tester->createSearchHttpConfigTransfer(
            [
                SearchHttpConfigTransfer::APPLICATION_ID => $applicationId,
                SearchHttpConfigTransfer::URL => 'new_test_url',
            ],
        );

        // Act
        $this->tester->getFacade()->saveSearchHttpConfig($newSearchHttpConfigTransfer);

        // Assert
        $savedSearchHttpConfig = $this->tester->findSearchHttpConfig();

        $this->tester->assertSearchHttpConfigStoredProperly(
            $newSearchHttpConfigTransfer,
            $savedSearchHttpConfig,
        );
    }

    /**
     * @return void
     */
    public function testSearchHttpConfigSuccessfullyDeleted(): void
    {
        // Arrange
        $searchHttpConfigTransfer = $this->tester->createSearchHttpConfigTransfer();
        $this->tester->haveSearchHttpConfig(
            [
                'search_ccp_configs' => [
                    SearchHttpConfigTransfer::APPLICATION_ID => $searchHttpConfigTransfer->getApplicationId(),
                    SearchHttpConfigTransfer::URL => 'test_url',
                ],
            ],
        );

        // Act
        $this->tester->getFacade()->deleteSearchHttpConfig($searchHttpConfigTransfer);

        // Assert
        $savedSearchHttpConfig = $this->tester->findSearchHttpConfig();

        $this->tester->assertSearchHttpConfigRemovedProperly($savedSearchHttpConfig);
    }
}
