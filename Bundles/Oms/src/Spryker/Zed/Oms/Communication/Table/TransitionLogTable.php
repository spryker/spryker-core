<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Communication\Table;

use BadMethodCallException;
use Orm\Zed\Oms\Persistence\Map\SpyOmsTransitionLogTableMap;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\Oms\Business\Exception\TransitionLogException;
use Spryker\Zed\Oms\Persistence\OmsQueryContainerInterface;
use UnexpectedValueException;

class TransitionLogTable extends AbstractTable
{
    /**
     * @var \Spryker\Zed\Oms\Persistence\OmsQueryContainerInterface
     */
    protected $omsQueryContainer;

    /**
     * @param \Spryker\Zed\Oms\Persistence\OmsQueryContainerInterface $omsQueryContainer
     */
    public function __construct(OmsQueryContainerInterface $omsQueryContainer)
    {
        $this->omsQueryContainer = $omsQueryContainer;
    }

    /**
     * @inheritDoc
     * @throws \UnexpectedValueException
     * @throws \Spryker\Zed\Oms\Business\Exception\TransitionLogException
     */
    protected function configure(TableConfiguration $config)
    {
        $headers = [
            SpyOmsTransitionLogTableMap::COL_FK_SALES_ORDER_ITEM => 'Item',
            SpyOmsTransitionLogTableMap::COL_EVENT => 'Event',
            SpyOmsTransitionLogTableMap::COL_CONDITION => 'Condition',
            SpyOmsTransitionLogTableMap::COL_SOURCE_STATE => 'Source state',
            SpyOmsTransitionLogTableMap::COL_TARGET_STATE => 'Target state',
            SpyOmsTransitionLogTableMap::COL_COMMAND => 'Command',
            SpyOmsTransitionLogTableMap::COL_IS_ERROR => 'Is error',
            SpyOmsTransitionLogTableMap::COL_ERROR_MESSAGE => 'Error message',
            SpyOmsTransitionLogTableMap::COL_PATH => 'Path',
            SpyOmsTransitionLogTableMap::COL_HOSTNAME => 'Hostname',
            SpyOmsTransitionLogTableMap::COL_CREATED_AT => 'Date',
        ];

        $config->setHeader($headers);

        $config->setUrl('table-ajax?id-order=' . $this->getIdOrder());

        $createdAtColumnIndex = array_search(SpyOmsTransitionLogTableMap::COL_CREATED_AT, array_keys($config->getHeader()));
        if ($createdAtColumnIndex === false) {
            throw new UnexpectedValueException('Not a valid column index');
        }

        if (!$createdAtColumnIndex) {
            throw new TransitionLogException('Could not find "createdAd" column index');
        }

        $config->setDefaultSortColumnIndex($createdAtColumnIndex);
        $config->setDefaultSortDirection(TableConfiguration::SORT_DESC);

        $config->setSearchable([SpyOmsTransitionLogTableMap::COL_SOURCE_STATE]);
        $config->setSortable([SpyOmsTransitionLogTableMap::COL_CREATED_AT]);

        return $config;
    }

    /**
     * @inheritDoc
     */
    protected function prepareData(TableConfiguration $config)
    {
        $idOrder = $this->getIdOrder();

        $query = $this->omsQueryContainer->queryLogByIdOrder($idOrder, false);

        $result = $this->runQuery($query, $config);

        foreach ($result as $i => $row) {
            $row = $this->formatEmptyValues($row, SpyOmsTransitionLogTableMap::COL_EVENT);
            $row = $this->formatEmptyValues($row, SpyOmsTransitionLogTableMap::COL_CONDITION);
            $row = $this->formatEmptyValues($row, SpyOmsTransitionLogTableMap::COL_COMMAND);

            $result[$i] = $row;
        }

        return $result;
    }

    /**
     * @param array $row
     * @param string $column
     *
     * @return array
     */
    protected function formatArray(array $row, $column)
    {
        $array = $row[$column];

        if (!empty($array)) {
            foreach ($array as $i => $value) {
                $pieces = explode('\\', $value);
                $conditionShort = end($pieces);
                $array[$i] = '<alt title="' . $value . '">' . $conditionShort . '</alt>';
            }
        }
        $row[$column] = implode(',', $array);

        return $row;
    }

    /**
     * @throws \BadMethodCallException
     *
     * @return int
     */
    protected function getIdOrder()
    {
        $idOrder = $this->request->get('id-order');
        if (!$idOrder) {
            throw new BadMethodCallException('Please pass a sales order id');
        }

        return $idOrder;
    }

    /**
     * @param array $row
     * @param string $column
     *
     * @return array
     */
    protected function formatEmptyValues($row, $column)
    {
        $row[$column] = empty($row[$column]) ? '---' : $row[$column];

        return $row;
    }
}
