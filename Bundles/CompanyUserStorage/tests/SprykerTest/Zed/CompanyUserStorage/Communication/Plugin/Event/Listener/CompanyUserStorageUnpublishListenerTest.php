<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CompanyUserStorage\Communication\Plugin\Event\Listener;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\EventEntityTransfer;
use Spryker\Client\Kernel\Container;
use Spryker\Client\Queue\QueueDependencyProvider;
use Spryker\Zed\CompanyUser\Dependency\CompanyUserEvents;
use Spryker\Zed\CompanyUserStorage\Communication\Plugin\Event\Listener\CompanyUserStoragePublishListener;
use Spryker\Zed\CompanyUserStorage\Communication\Plugin\Event\Listener\CompanyUserStorageUnpublishListener;
use Spryker\Zed\CompanyUserStorage\Persistence\CompanyUserStorageRepository;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group CompanyUserStorage
 * @group Communication
 * @group Plugin
 * @group Event
 * @group Listener
 * @group CompanyUserStorageUnpublishListenerTest
 * Add your own group annotations below this line
 */
class CompanyUserStorageUnpublishListenerTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\CompanyUserStorage\CompanyUserStorageCommunicationTester
     */
    protected $tester;

    /**
     * @var \Spryker\Zed\CompanyUserStorage\Persistence\CompanyUserStorageRepositoryInterface
     */
    protected $companyUserStorageRepository;

    /**
     * @var \Generated\Shared\Transfer\CompanyUserTransfer
     */
    protected $companyUserTransfer;

    /**
     * @var \Spryker\Zed\CompanyUserStorage\Communication\Plugin\Event\Listener\CompanyUserStoragePublishListener
     */
    protected $companyUserStoragePublishListener;

    /**
     * @var \Spryker\Zed\CompanyUserStorage\Communication\Plugin\Event\Listener\CompanyUserStorageUnpublishListener
     */
    protected $companyUserStorageUnpublishListener;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->setDependency(QueueDependencyProvider::QUEUE_ADAPTERS, function (Container $container) {
            return [
                $container->getLocator()->rabbitMq()->client()->createQueueAdapter(),
            ];
        });

        $this->companyUserTransfer = $this->tester->haveCompanyUserTransfer();
        $this->companyUserStorageRepository = new CompanyUserStorageRepository();
        $this->companyUserStoragePublishListener = new CompanyUserStoragePublishListener();
        $this->companyUserStorageUnpublishListener = new CompanyUserStorageUnpublishListener();
    }

    /**
     * @return void
     */
    public function testCompanyUserStorageUnpublishListenerRemoveDataFromStore(): void
    {
        //Arrange
        $expectedCount = 0;
        $eventTransfers = [
            (new EventEntityTransfer())->setId($this->companyUserTransfer->getIdCompanyUser()),
        ];

        //Act
        $this->companyUserStoragePublishListener->handleBulk(
            $eventTransfers,
            CompanyUserEvents::COMPANY_USER_PUBLISH
        );
        $this->companyUserStorageUnpublishListener->handleBulk(
            $eventTransfers,
            CompanyUserEvents::COMPANY_USER_UNPUBLISH
        );
        $companyUserStorageEntities = $this->companyUserStorageRepository->findCompanyUserStorageEntities([
            $this->companyUserTransfer->getIdCompanyUser(),
        ]);

        //Assert
        $this->assertCount($expectedCount, $companyUserStorageEntities);
    }
}
