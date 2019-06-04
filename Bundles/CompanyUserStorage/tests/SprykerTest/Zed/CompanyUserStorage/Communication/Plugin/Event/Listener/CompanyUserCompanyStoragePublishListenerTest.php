<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CompanyUserStorage\Communication\Plugin\Event\Listener;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\EventEntityTransfer;
use Orm\Zed\CompanyUserStorage\Persistence\SpyCompanyUserStorage;
use Spryker\Client\Kernel\Container;
use Spryker\Client\Queue\QueueDependencyProvider;
use Spryker\Zed\Company\Dependency\CompanyEvents;
use Spryker\Zed\CompanyUserStorage\Communication\Plugin\Event\Listener\CompanyUserCompanyStoragePublishListener;
use Spryker\Zed\CompanyUserStorage\Persistence\CompanyUserStorageRepository;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group CompanyUserStorage
 * @group Communication
 * @group Plugin
 * @group Event
 * @group Listener
 * @group CompanyUserCompanyStoragePublishListenerTest
 * Add your own group annotations below this line
 */
class CompanyUserCompanyStoragePublishListenerTest extends Unit
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
     * @var \Spryker\Zed\CompanyUserStorage\Communication\Plugin\Event\Listener\CompanyUserCompanyStoragePublishListener
     */
    protected $companyUserCompanyStoragePublishListener;

    /**
     * @var \Generated\Shared\Transfer\CompanyUserTransfer
     */
    protected $companyUserTransfer;

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

        $this->companyUserStorageRepository = new CompanyUserStorageRepository();
        $this->companyUserCompanyStoragePublishListener = new CompanyUserCompanyStoragePublishListener();
    }

    /**
     * @return void
     */
    public function testCompanyUserCompanyStoragePublishListenerStoreDataIfCompanyIsActivated(): void
    {
        //Arrange
        $expectedCount = 1;
        $this->companyUserTransfer = $this->tester->haveCompanyUserTransfer();
        $eventTransfers = [
            (new EventEntityTransfer())->setId($this->companyUserTransfer->getFkCompany()),
        ];

        //Act
        $this->companyUserCompanyStoragePublishListener->handleBulk(
            $eventTransfers,
            CompanyEvents::ENTITY_SPY_COMPANY_UPDATE
        );
        $companyUserStorageEntities = $this->companyUserStorageRepository->findCompanyUserStorageEntities([
            $this->companyUserTransfer->getIdCompanyUser(),
        ]);

        //Assert
        $this->assertCount($expectedCount, $companyUserStorageEntities);
    }

    /**
     * @return void
     */
    public function testCompanyUserCompanyStoragePublishListenerRemoveDataFromStorageIfCompanyIsDeactivated(): void
    {
        //Arrange
        $expectedCount = 0;
        $this->companyUserTransfer = $this->tester->haveCompanyUserTransfer(false);
        (new SpyCompanyUserStorage())
            ->setFkCompanyUser($this->companyUserTransfer->getIdCompanyUser())
            ->setData([])
            ->save();
        $eventTransfers = [
            (new EventEntityTransfer())->setId($this->companyUserTransfer->getFkCompany()),
        ];

        //Act
        $this->companyUserCompanyStoragePublishListener->handleBulk(
            $eventTransfers,
            CompanyEvents::ENTITY_SPY_COMPANY_UPDATE
        );
        $companyUserStorageEntities = $this->companyUserStorageRepository->findCompanyUserStorageEntities([
            $this->companyUserTransfer->getIdCompanyUser(),
        ]);

        //Assert
        $this->assertCount($expectedCount, $companyUserStorageEntities);
    }

    /**
     * @return void
     */
    protected function _after(): void
    {
        parent::_after();

        $this->cleanUpCompanyUserStorage();
    }

    /**
     * @return void
     */
    protected function cleanUpCompanyUserStorage(): void
    {
        $companyUserStorageEntities = $this->companyUserStorageRepository->findCompanyUserStorageEntities([
            $this->companyUserTransfer->getIdCompanyUser(),
        ]);

        foreach ($companyUserStorageEntities as $companyUserStorageEntity) {
            $companyUserStorageEntity->delete();
        }
    }
}
