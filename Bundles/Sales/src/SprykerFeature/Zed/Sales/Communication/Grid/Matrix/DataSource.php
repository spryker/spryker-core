<?php

namespace SprykerFeature\Zed\Sales\Communication\Grid\Matrix;

use Propel\Runtime\Collection\Collection;

class DataSource
{

    /**
     * @var null
     */
    protected $itemsBuffer = null;

    /** @var array */
    protected $matrixBuffer = [];

    /** @var array */
    protected $processes = [];

    /** @var array */
    protected $statuses = [];

    /** @var string */
    protected $salesUrl = '/sales/order/items?status={STATUS}&process={PROCESS}&age={AGE}';

    /**
     * @return array|Collection
     */
    public function getData()
    {
        $items = $this->getAllItems();
        $matrix = $this->createMatrix($items);
        $data = $this->formatToData($matrix);

        return $data;
    }

    /**
     * @return int
     */
    public function getTotal()
    {
        $items = $this->getAllItems();

        return count($this->createMatrix($items));
    }

    /**
     * @param $field
     * @param array $values
     * @return mixed|void
     * @throws \Exception
     */
    public function getDataByField($field, array $values)
    {
        throw new \Exception('Not implemented');
    }

    /**
     * @return array
     */
    public function getAllOrderProcessNames()
    {
        return \SprykerFeature\Zed\Oms\Persistence\Propel\SpyOmsOrderProcessQuery::create()->select('name')->find()->toArray();
    }

    /**
     * @param array $matrix
     * @return array
     */
    protected function formatToData(array $matrix)
    {
        $data = [];

        foreach ($matrix as $status => $processes) {
            $item = ['status_name' => $status];

            foreach ($this->processes as $processName => $lastStatusChanges) {
                $item[$this->formatProcessName($processName)] = '';
            }

            foreach ($processes as $processName => $lastStatusChanges) {
                $lastStatusChange = [];
                foreach ($lastStatusChanges as $lastStatusChangeName => $lastStatusChangeCount) {
                    $lastStatusChange[] = '<a href="' . $this->createLink(
                            $lastStatusChangeName,
                            $this->processes[$processName],
                            $this->statuses[$status]
                        ) . '">' . $lastStatusChangeCount . '</a>';
                }
                $item[$this->formatProcessName($processName)] = implode(' | ', $lastStatusChange);
            }

            $data[] = $item;
        }

        return $data;
    }

    /**
     * @param $processName
     * @return mixed
     */
    protected function formatProcessName($processName)
    {
        $processName = preg_replace('/\s+/', '_', $processName);
        $processName = str_replace('-', '_', $processName);

        return preg_replace('/\(|\)/', '_', $processName);
    }

    /**
     * @param $age
     * @param $processId
     * @param $statusId
     * @return mixed|string
     */
    protected function createLink($age, $processId, $statusId)
    {
        $url = $this->salesUrl;
        $url = str_replace('{AGE}', $age, $url);
        $url = str_replace('{STATUS}', $statusId, $url);
        $url = str_replace('{PROCESS}', $processId, $url);

        return $url;
    }

    /**
     * @param \PropelObjectCollection $items
     * @return array
     * @throws \ErrorException
     */
    protected function createMatrix(\PropelObjectCollection $items)
    {
        if (!empty($this->matrixBuffer)) {
            return $this->matrixBuffer;
        }

        $matrix = [];
        foreach ($items as $item) {
            /* @var $item \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItem */
            $statusName = $item->getStatus()->getName();
            $this->statuses[$statusName] = $item->getStatus()->getIdOmsOrderItemStatus();

            $processName = $item->getProcess()->getName();
            $this->processes[$processName] = $item->getProcess()->getIdOmsOrderProcess();

            if (false === array_key_exists($statusName, $matrix)) {
                $matrix[$statusName] = [];
            }

            if (false === array_key_exists($processName, $matrix[$statusName])) {
                $matrix[$statusName][$processName] = [
                    'last24h' => 0,
                    'last7d' => 0,
                    'before7d' => 0,
                ];
            }

            $date = $item->getLastStatusChange();
            if (isset($date)) {
                $datetime = new \DateTime($date);
                $timestamp = $datetime->getTimestamp();
                $diff = time() - $timestamp;

                $oneDay = 24 * 60 * 60;
                $oneWeek = 7 * $oneDay;

                if ($diff < $oneDay) {
                    $matrix[$statusName][$processName]['last24h']++;
                } elseif ($diff < $oneWeek) {
                    $matrix[$statusName][$processName]['last7d']++;
                } else {
                    $matrix[$statusName][$processName]['before7d']++;
                }
            } else {
                throw new \ErrorException('Status without a lastStatusChange');
            }

        }

        $this->matrixBuffer = $matrix;

        return $matrix;
    }

    /**
     * @return array
     */
    protected function getAllItems()
    {
        if (!empty($this->itemsBuffer)) {
            return $this->itemsBuffer;
        }

        $orderQuery = \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderItemQuery::create()
            ->useOrderQuery();

        if (!empty($this->processesInMatrix)) {
            $processQuery = $orderQuery->useProcessQuery();
            foreach ($this->processesInMatrix as $i => $processName) {
                if ($i === 0) {
                    $processQuery->where('Process.Name = ?', $processName);
                } else {
                    $processQuery->_or()->where('Process.Name = ?', $processName);
                }
            }
            $orderQuery = $processQuery->endUse();
        }

        $items = $orderQuery->filterByIsTest(false)
            ->endUse()
            ->join('Order')
            ->join('Process')
            ->withColumn(\SprykerFeature\Zed\Oms\Persistence\Propel\Map\SpyOmsOrderProcessTableMap::COL_NAME, 'process')
            ->find();

        $this->itemsBuffer = $items;

        return $items;
    }

}
