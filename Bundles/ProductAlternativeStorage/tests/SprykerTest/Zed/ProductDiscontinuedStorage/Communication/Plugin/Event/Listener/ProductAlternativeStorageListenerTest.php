<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductAlternativeStorage\Communication\Plugin\Event\Listener;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\EventEntityTransfer;
use Generated\Shared\Transfer\ProductDiscontinueRequestTransfer;
use PHPUnit\Framework\SkippedTestError;
use Spryker\Shared\Config\Config;
use Spryker\Shared\PropelQueryBuilder\PropelQueryBuilderConstants;
use Spryker\Zed\ProductAlternative\Dependency\ProductAlternativeEvents;
use Spryker\Zed\ProductAlternativeStorage\Communication\Plugin\Event\Listener\ProductAlternativeStorageListener;
use Spryker\Zed\ProductAlternativeStorage\Persistence\ProductAlternativeStorageRepository;
use Spryker\Zed\ProductDiscontinued\Dependency\ProductDiscontinuedEvents;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group ProductAlternativeStorage
 * @group Communication
 * @group Plugin
 * @group Event
 * @group Listener
 * @group ProductAlternativeStorageListenerTest
 * Add your own group annotations below this line
 */
class ProductAlternativeStorageListenerTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\ProductAlternativeStorage\ProductAlternativeStorageBusinessTester
     */
    protected $tester;

    /**
     * @var \Spryker\Zed\ProductAlternativeStorage\Persistence\ProductAlternativeStorageRepository
     */
    protected $productAlternativeStorageRepository;

    /**
     * @var \Spryker\Zed\ProductAlternativeStorage\Communication\Plugin\Event\Listener\ProductAlternativeStorageListener
     */
    protected $productAlternativeStorageListener;

    /**
     * @var \Generated\Shared\Transfer\ProductDiscontinuedTransfer
     */
    protected $productDiscontinuedTransfer;

    /**
     * @throws \PHPUnit\Framework\SkippedTestError
     *
     * @return void
     */
    protected function setUp(): void
    {
        $dbEngine = Config::get(PropelQueryBuilderConstants::ZED_DB_ENGINE);
        if ($dbEngine !== 'pgsql') {
            throw new SkippedTestError('Warning: no PostgreSQL is detected');
        }
        parent::setUp();

        $this->productAlternativeStorageRepository = new ProductAlternativeStorageRepository();

        $this->productAlternativeStorageListener = new ProductAlternativeStorageListener();
        $this->productAlternativeStorageListener->setFacade($this->tester->getMockedFacade());

        $productConcrete = $this->tester->haveProduct();
        $productDiscontinuedRequestTransfer = (new ProductDiscontinueRequestTransfer())
            ->setIdProduct($productConcrete->getIdProductConcrete());
        $this->productDiscontinuedTransfer = $this->tester->getProductDiscontinuedFacade()->markProductAsDiscontinued(
            $productDiscontinuedRequestTransfer
        )
            ->getProductDiscontinued();
    }

    /**
     * @return void
     */
    public function testProductAlternativeStorageEntityCanBePublished(): void
    {
        // Arrange
        $eventTransfers = [
            (new EventEntityTransfer())->setId($this->productDiscontinuedTransfer->getIdProductDiscontinued()),
        ];

        // Act
        $this->productAlternativeStorageListener->handleBulk(
            $eventTransfers,
            ProductAlternativeEvents::PRODUCT_ALTERNATIVE_PUBLISH
        );
        $productDiscontinuedEntityTransfers = $this->productAlternativeStorageRepository
            ->findProductAlternativeStorageEntitiesByIds(
                [$this->productDiscontinuedTransfer->getIdProductDiscontinued()]
            );

        // Assert
        $this->assertCount(count($this->tester->getLocaleFacade()->getAvailableLocales()), $productDiscontinuedEntityTransfers);
    }

    /**
     * @return void
     */
    public function testProductAlternativeStorageEntityCanBeUnpublished(): void
    {
        // Arrange
        $eventTransfers = [
            (new EventEntityTransfer())->setId($this->productDiscontinuedTransfer->getIdProductDiscontinued()),
        ];

        // Act
        $this->productAlternativeStorageListener->handleBulk(
            $eventTransfers,
            ProductDiscontinuedEvents::PRODUCT_DISCONTINUED_PUBLISH
        );
        $this->productAlternativeStorageListener->handleBulk(
            $eventTransfers,
            ProductDiscontinuedEvents::PRODUCT_DISCONTINUED_PUBLISH
        );
        $productDiscontinuedEntityTransfers = $this->productAlternativeStorageRepository
            ->findProductAlternativeStorageEntitiesByIds(
                [$this->productDiscontinuedTransfer->getIdProductDiscontinued()]
            );

        // Assert
        $this->assertCount(0, $productDiscontinuedEntityTransfers);
    }
}
