<?php

namespace SprykerFeature\Zed\Sales\Communication\Controller;

use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;

class OperationsController extends AbstractController
{

    public function indexAction()
    {
    }

    public function matrixAction()
    {
    }

    protected function getProcessOptions()
    {
        $processList = $this->facadeSales->getAllProcesses();
        $processOptions = ['' => '-- select process --'];
        foreach ($processList as $process) {
            /* @var $process \SprykerFeature\Zed\Oms\Persistence\Propel\SpyOmsOrderProcess */
            $processOptions[$process->getIdSalesOrderProcess()] = $process->getName();
        }

        return $processOptions;
    }

    protected function getStatusOptions($processId = null)
    {
        $statusList = $this->facadeSales->getSimpleItemStatusOverviewIncludingIds($processId);
        $statusOptions = ['' => '-- select status --'];
        foreach ($statusList as $statusData) {
            $statusOptions[$statusData['status_id']] = $statusData['status_name']. ' (' . $statusData['order_count'] . ' order(s))';
        }

        return $statusOptions;
    }


}
