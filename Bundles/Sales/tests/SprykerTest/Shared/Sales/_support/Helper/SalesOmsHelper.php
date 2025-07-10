<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace SprykerTest\Shared\Sales\Helper;

use Codeception\Module;
use Codeception\TestInterface;
use Exception;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OmsCheckConditionsQueryCriteriaTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Propel\Runtime\Collection\ObjectCollection;
use Ramsey\Uuid\Uuid;
use SprykerTest\Zed\Oms\Helper\OmsHelperTrait;
use SprykerTest\Zed\Testify\Helper\Business\BusinessHelperTrait;

class SalesOmsHelper extends Module
{
    use SalesHelperTrait;
    use OmsHelperTrait;
    use BusinessHelperTrait;

    protected ?SpySalesOrderItem $salesOrderItemEntity = null;

    protected string $stateMachineName;

    protected string $orderReference;

    /**
     * @var string
     */
    protected const STATE_MACHINE_NAME = 'stateMachineName';

    /**
     * @var array<string, mixed>
     */
    protected array $config = [
        self::STATE_MACHINE_NAME => 'ForeignPaymentStateMachine01',
    ];

    /**
     * @param \Codeception\TestInterface $test
     *
     * @return void
     */
    public function _before(TestInterface $test): void
    {
        parent::_before($test);

        $this->orderReference = Uuid::uuid4()->toString();
    }

    /**
     * @return string
     */
    public function getOrderReference(): string
    {
        return $this->orderReference;
    }

    /**
     * @throws \Exception
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItem
     */
    public function getSalesOrderItemEntity(): SpySalesOrderItem
    {
        if (!$this->salesOrderItemEntity) {
            throw new Exception('Sales order item entity is not set. Please create a sales order item entity first using SalesOmsHelper::haveOrderItemInState().');
        }

        return $this->salesOrderItemEntity;
    }

    /**
     * @return void
     */
    public function setupStateMachine(): void
    {
        $this->stateMachineName = $this->config[static::STATE_MACHINE_NAME];
        $xmlFileDirectory = APPLICATION_VENDOR_DIR . 'spryker/spryker/Bundles/SalesPayment/config/Zed/Oms/';

        $this->getOmsHelper()->configureTestStateMachine([$this->stateMachineName], $xmlFileDirectory);
    }

    /**
     * @param string $stateMachineName
     *
     * @return bool
     */
    public function isStateMachine(string $stateMachineName): bool
    {
        return $stateMachineName === $this->stateMachineName;
    }

    /**
     * @param string $stateName
     * @param array $seed
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItem
     */
    public function haveOrderItemInState(string $stateName, array $seed = []): SpySalesOrderItem
    {
        $salesHelper = $this->getSalesHelper();

        if (isset($seed[OrderTransfer::ORDER_REFERENCE])) {
            codecept_debug('Order reference is reserved in the seed data and can not be changed. It is required for the test setup.');
        }

        if (isset($seed[ItemTransfer::PROCESS])) {
            codecept_debug('The process is reserved in the seed data and can not be changed. It is required for the test setup.');
        }

        $seed = array_merge($seed, [
            OrderTransfer::ORDER_REFERENCE => $this->orderReference,
            ItemTransfer::STATE => $stateName,
            ItemTransfer::PROCESS => $this->stateMachineName,
        ]);

        $idSalesOrder = $salesHelper->createOrder($seed);

        $salesOrderItemEntity = $salesHelper->createSalesOrderItemForOrder($idSalesOrder, $seed);

        $this->getOmsHelper()->triggerEventForNewOrderItems([$salesOrderItemEntity->getIdSalesOrderItem()]);

        $this->salesOrderItemEntity = $salesOrderItemEntity;

        return $salesOrderItemEntity;
    }

    /**
     * @param string $expectedStatus
     *
     * @return void
     */
    public function assertOrderItemIsInState(string $expectedStatus): void
    {
        $this->salesOrderItemEntity->reload();
        $stateName = $this->salesOrderItemEntity->getState()->getName();

        $this->assertSame(
            $expectedStatus,
            $stateName,
            sprintf('Expected that the order item is in state "%s" but it is in state "%s"', $expectedStatus, $stateName),
        );
    }

    /**
     * @param string|null $eventName
     *
     * @return void
     */
    public function tryToTransitionOrderItems(?string $eventName = null): void
    {
        /** @var \Spryker\Zed\Oms\Business\OmsFacadeInterface $omsFacade */
        $omsFacade = $this->getBusinessHelper()->getFacade('Oms');

        $logContext = [];
        $omsCheckConditionsQueryCriteriaTransfer = new OmsCheckConditionsQueryCriteriaTransfer();
        $omsCheckConditionsQueryCriteriaTransfer
            ->setLimit(1)
            ->setSalesOrderItemIds([$this->salesOrderItemEntity->getIdSalesOrderItem()]);

        $omsFacade->checkConditions($logContext, $omsCheckConditionsQueryCriteriaTransfer);

        if (!$eventName) {
            return;
        }

        $collection = new ObjectCollection([$this->salesOrderItemEntity]);
        $omsFacade->triggerEvent($eventName, $collection, []);
    }
}
