<?php
namespace SprykerFeature\Zed\Oms\Business\Model\OrderStateMachine;

use DateInterval;
use SprykerFeature\Zed\Oms\Persistence\OmsQueryContainer;
use SprykerFeature\Zed\Oms\Business\Model\OrderStateMachineInterface;
use SprykerFeature\Zed\Oms\Business\Model\ProcessInterface;
use SprykerFeature\Zed\Oms\Business\Model\Process\StatusInterface;
use SprykerFeature\Zed\Oms\Business\Model\Process\EventInterface;

class Timeout implements TimeoutInterface
{

    /**
     * @var OmsQueryContainer
     */
    protected $queryContainer;

    /**
     * @var \DateTime[]
     */
    protected $eventToTimeoutBuffer = array();

    /**
     * @var StatusInterface[]
     */
    protected $statusIdToModelBuffer = array();

    /**
     * @param OmsQueryContainer $queryContainer
     */
    public function __construct(OmsQueryContainer $queryContainer)
    {
        $this->queryContainer = $queryContainer;
    }

    /**
     * @param OrderStateMachineInterface $orderStateMachine
     * @return int
     */
    public function checkTimeouts(OrderStateMachineInterface $orderStateMachine)
    {
        $orderItems = $this->findItemsWithExpiredTimeouts();

        $countAffectedItems = $orderItems->count();

        $groupedOrderItems = $this->groupItemsByEvent($orderItems);

        foreach ($groupedOrderItems as $event => $orderItems) {
            $orderStateMachine->triggerEvent($event, $orderItems, array());
        }

        return $countAffectedItems;
    }

    /**
     * @param ProcessInterface                                         $process
     * @param \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItem $orderItem
     * @param \DateTime                                                $currentTime
     * @throws \Exception
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function setNewTimeout(ProcessInterface $process, \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItem $orderItem, \DateTime $currentTime)
    {
        $targetStatusEntity = $orderItem->getStatus();

        $targetStatus = $this->getStatusFromProcess($targetStatusEntity->getName(), $process);

        if ($targetStatus->hasTimeoutEvent()) {
            $events = $targetStatus->getTimeoutEvents();

            foreach ($events as $event) {
                $timeoutDate = $this->calculateTimeoutDateFromEvent($currentTime, $event);

                (new \SprykerFeature\Zed\Oms\Persistence\Propel\SpyOmsEventTimeout())
                    ->setTimeout($timeoutDate)
                    ->setFkSalesOrderItem($orderItem->getPrimaryKey())
                    ->setStatus($targetStatusEntity)
                    ->setEvent($event->getName())
                    ->save();
            }
        }
    }

    /**
     * @param ProcessInterface                                         $process
     * @param string                                                   $statusId
     * @param \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItem $orderItem
     * @throws \Exception
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function dropOldTimeout(ProcessInterface $process, $statusId, \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItem $orderItem)
    {
        $sourceStatus = $this->getStatusFromProcess($statusId, $process);

        if ($sourceStatus->hasTimeoutEvent()) {
            \SprykerFeature\Zed\Oms\Persistence\Propel\SpyOmsEventTimeoutQuery::create()
                ->filterByOrderItem($orderItem)
                ->delete();
        }
    }

    /**
     * @param \DateTime       $currentTime
     * @param EventInterface $event
     * @return \DateTime
     */
    protected function calculateTimeoutDateFromEvent(\DateTime $currentTime, EventInterface $event)
    {
        $currentTime = clone $currentTime;

        if (!isset($this->eventToTimeoutBuffer[$event->getName()])) {
            $timeout = $event->getTimeout();
            $interval = DateInterval::createFromDateString($timeout);

            $this->validateTimeout($interval, $timeout);

            $this->eventToTimeoutBuffer[$event->getName()] = $currentTime->add($interval);

            \SprykerFeature_Shared_Library_Log::log($this->eventToTimeoutBuffer, 'timeout.log');
        }

        return $this->eventToTimeoutBuffer[$event->getName()];
    }

    /**
     * @param $statusId
     * @param ProcessInterface $process
     * @return StatusInterface
     */
    protected function getStatusFromProcess($statusId, ProcessInterface $process)
    {
        if (!isset($this->statusIdToModelBuffer[$statusId])) {
            $this->statusIdToModelBuffer[$statusId] = $process->getStatusFromAllProcesses($statusId);
        }

        return $this->statusIdToModelBuffer[$statusId];
    }

    /**
     * @param $orderItems
     * @return array
     */
    protected function groupItemsByEvent(\PropelObjectCollection $orderItems)
    {
        $groupedOrderItems = array();
        foreach ($orderItems as $orderItem) {
            $eventName = $orderItem->getEvent();
            if (!isset($groupedOrderItems[$eventName])) {
                $groupedOrderItems[$eventName] = array();
            }
            $groupedOrderItems[$eventName][] = $orderItem;
        }

        return $groupedOrderItems;
    }

    /**
     * @return \PropelObjectCollection
     */
    protected function findItemsWithExpiredTimeouts()
    {
        $now = new \DateTime('now');

        return $this->queryContainer->findItemsWithExpiredTimeouts($now)->find();
    }

    /**
     * @param $interval
     * @param $timeout
     * @return int
     * @throws \ErrorException
     */
    protected function validateTimeout($interval, $timeout)
    {
        $vars = get_object_vars($interval);
        $vSum = 0;
        foreach ($vars as $v) {
            $vSum += (int) $v;
        }
        if ($vSum === 0) {
            throw new \ErrorException('Invalid format for timeout "' . $timeout . '"');
        }

        return $vSum;
    }

}
