<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ContentStorage\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ContentTransfer;
use Generated\Shared\Transfer\LocalizedContentTransfer;
use Orm\Zed\ContentStorage\Persistence\SpyContentStorage;
use Spryker\Client\Kernel\Container;
use Spryker\Client\Queue\QueueDependencyProvider;
use Spryker\Shared\ContentStorage\ContentStorageConfig;
use Spryker\Zed\ContentStorage\Persistence\ContentStoragePersistenceFactory;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group ContentStorage
 * @group Business
 * @group Facade
 * @group ContentStorageFacadeTest
 * Add your own group annotations below this line
 */
class ContentStorageFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ContentStorage\ContentStorageBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->tester->setDependency(QueueDependencyProvider::QUEUE_ADAPTERS, function (Container $container) {
            return [
                $container->getLocator()->rabbitMq()->client()->createQueueAdapter(),
            ];
        });
    }

    /**
     * @return void
     */
    public function testPublishStoresEntityData(): void
    {
        $data = [
            ContentTransfer::CONTENT_TERM_KEY => 'test-term',
            ContentTransfer::LOCALIZED_CONTENTS => [
                [
                    LocalizedContentTransfer::PARAMETERS => '["M23222", "M23232"]',
                ],
            ],
        ];
        $contentTransfer = $this->tester->haveContent($data);

        $this->tester->getFacade()->publish([$contentTransfer->getIdContent()]);
        $contentStorageEntity = $this->getContentStorageEntity();
        $storageData = [
            ContentTransfer::ID_CONTENT => $contentTransfer->getIdContent(),
            ContentStorageConfig::TERM_KEY => $data[ContentTransfer::CONTENT_TERM_KEY],
            ContentStorageConfig::CONTENT_KEY => json_decode(
                $data[ContentTransfer::LOCALIZED_CONTENTS][0][LocalizedContentTransfer::PARAMETERS]
            ),
        ];

        $this->assertEquals($storageData, $contentStorageEntity->getData());
    }

    /**
     * @return \Orm\Zed\ContentStorage\Persistence\SpyContentStorage
     */
    protected function getContentStorageEntity(): SpyContentStorage
    {
        $persistenceFactory = new ContentStoragePersistenceFactory();
        $contentStorageEntity = $persistenceFactory->createContentStorageQuery()
            ->lastCreatedFirst()
            ->findOne();

        return $contentStorageEntity;
    }
}
