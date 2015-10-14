<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Oms\Communication\Table;

use Propel\Runtime\ActiveQuery\Criteria;
use SprykerFeature\Zed\Gui\Communication\Table\AbstractTable;
use SprykerFeature\Zed\Gui\Communication\Table\TableConfiguration;
use SprykerFeature\Zed\Oms\Persistence\OmsQueryContainerInterface;
use SprykerFeature\Zed\Oms\Persistence\Propel\Map\SpyOmsTransitionLogTableMap;

class TransitionLogTable extends AbstractTable
{

    const ACTIONS = 'Actions';

    /**
     * @var OmsQueryContainerInterface
     */
    protected $omsQueryContainer;

    /**
     * TransitionLogTable constructor.
     * @param OmsQueryContainerInterface $omsQueryContainer
     */
    public function __construct(OmsQueryContainerInterface $omsQueryContainer)
    {
        $this->omsQueryContainer = $omsQueryContainer;
    }

    /**
     * @inheritDoc
     */
    protected function configure(TableConfiguration $config)
    {
        $headers = [
            SpyOmsTransitionLogTableMap::COL_FK_SALES_ORDER_ITEM => 'Item',
            SpyOmsTransitionLogTableMap::COL_EVENT => 'Event',
            SpyOmsTransitionLogTableMap::COL_CONDITIONS => 'Condition',
            SpyOmsTransitionLogTableMap::COL_SOURCE_STATE => 'Source state',
            SpyOmsTransitionLogTableMap::COL_TARGET_STATE => 'Target state',
            SpyOmsTransitionLogTableMap::COL_COMMANDS => 'Command',
            SpyOmsTransitionLogTableMap::COL_ERROR => 'Error',
            SpyOmsTransitionLogTableMap::COL_ERROR_MESSAGE => 'Error message',
            SpyOmsTransitionLogTableMap::COL_PATH => 'Path',
            SpyOmsTransitionLogTableMap::COL_HOSTNAME => 'Hostname',
            SpyOmsTransitionLogTableMap::COL_CREATED_AT => 'Date',
        ];

        $config->setHeader($headers);

        $config->setUrl('table-ajax?id-order='.$this->getIdOrder());

        $createdAtColumnIndex = array_search(SpyOmsTransitionLogTableMap::COL_CREATED_AT, array_keys($config->getHeader()));

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

            $row = $this->formatArray($row, SpyOmsTransitionLogTableMap::COL_CONDITIONS);
            $row = $this->formatArray($row, SpyOmsTransitionLogTableMap::COL_COMMANDS);

            $row = $this->formatEmptyValues($row, SpyOmsTransitionLogTableMap::COL_EVENT);
            $row = $this->formatEmptyValues($row, SpyOmsTransitionLogTableMap::COL_CONDITIONS);
            $row = $this->formatEmptyValues($row, SpyOmsTransitionLogTableMap::COL_COMMANDS);

            $result[$i] = $row;

        }


        return $result;
    }

    /**
     * @param $row
     * @return mixed
     */
    protected function formatArray(array $row, $column)
    {
        $array = $row[$column];

        if (!empty($array)) {
            foreach ($array as $i => $value) {
                $expl = explode('\\', $value);
                $conditionShort = end($expl);
                $array[$i] = '<alt title="' . $value . '">' . $conditionShort . '</alt>';
            }
        }
        $row[$column] = implode(',', $array);

        return $row;
    }

    /**
     * @return mixed
     */
    protected function getIdOrder()
    {
        $idOrder = $this->request->get('id-order');
        return $idOrder;
    }

    /**
     * @param $row
     * @param $column
     * @return mixed
     */
    protected function formatEmptyValues($row, $column)
    {
        $row[$column] = empty($row[$column]) ? '---' : $row[$column];
        return $row;
    }

}
