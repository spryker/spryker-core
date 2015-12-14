<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Sales\Dependency\Facade;

use Generated\Shared\Transfer\OrderTransfer;
use Orm\Zed\Oms\Persistence\SpyOmsOrderItemState;
use Orm\Zed\Oms\Persistence\SpyOmsOrderProcess;


class SalesToOmsBridge implements SalesToOmsInterface
{

    /**
     * @var \Spryker\Zed\Oms\Business\OmsFacade
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
     * @return SpyOmsOrderItemState
     */
    public function getInitialStateEntity()
    {
        return $this->omsFacade->getInitialStateEntity();
    }

    /**
     * @param string $processName
     *
     * @return SpyOmsOrderProcess
     */
    public function getProcessEntity($processName)
    {
        return $this->omsFacade->getProcessEntity($processName);
    }

    /**
     * @param OrderTransfer $transferOrder
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
}
