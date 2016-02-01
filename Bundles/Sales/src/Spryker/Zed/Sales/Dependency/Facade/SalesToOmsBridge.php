<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Sales\Dependency\Facade;

use Spryker\Zed\Oms\Business\OmsFacade;
use Generated\Shared\Transfer\OrderTransfer;
use Orm\Zed\Oms\Persistence\SpyOmsOrderItemState;
use Orm\Zed\Oms\Persistence\SpyOmsOrderProcess;
use Orm\Zed\Sales\Persistence\SpySalesOrder;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;

class SalesToOmsBridge implements SalesToOmsInterface
{

    /**
     * @var OmsFacade
     */
    protected $omsFacade;

    /**
     * SalesToOmsBridge constructor.
     *
     * @param \Spryker\Zed\Oms\Business\OmsFacade $omsFacade
     */
    public function __construct($omsFacade)
    {
        $this->omsFacade = $omsFacade;
    }

    /**
     * @return \Orm\Zed\Oms\Persistence\SpyOmsOrderItemState
     */
    public function getInitialStateEntity()
    {
        return $this->omsFacade->getInitialStateEntity();
    }

    /**
     * @param string $processName
     *
     * @return \Orm\Zed\Oms\Persistence\SpyOmsOrderProcess
     */
    public function getProcessEntity($processName)
    {
        return $this->omsFacade->getProcessEntity($processName);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $transferOrder
     *
     * @return string
     */
    public function selectProcess(OrderTransfer $transferOrder)
    {
        return $this->omsFacade->selectProcess($transferOrder);
    }

    /**
     * @return array
     */
    public function getOrderItemMatrix()
    {
        return $this->omsFacade->getOrderItemMatrix();
    }

    /**
     * @param int $idOrderItem
     *
     * @return string[]
     */
    public function getManualEvents($idOrderItem)
    {
        return $this->omsFacade->getManualEvents($idOrderItem);
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $order
     * @param string $flag
     *
     * @return SpySalesOrderItem[]
     */
    public function getItemsWithFlag(SpySalesOrder $order, $flag)
    {
        return $this->omsFacade->getItemsWithFlag($order, $flag);
    }

}
