<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Oms\Communication\Table;

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
            SpyOmsTransitionLogTableMap::COL_EVENT => 'Event',
            SpyOmsTransitionLogTableMap::COL_CONDITIONS => 'Conditions',
            SpyOmsTransitionLogTableMap::COL_SOURCE_STATE => 'Source state',
            SpyOmsTransitionLogTableMap::COL_TARGET_STATE => 'Target state',
            SpyOmsTransitionLogTableMap::COL_COMMANDS => 'Commands',
            SpyOmsTransitionLogTableMap::COL_ERROR => 'Error',
            SpyOmsTransitionLogTableMap::COL_ERROR_MESSAGE => 'Error message',
            SpyOmsTransitionLogTableMap::COL_PATH => 'Path',
            SpyOmsTransitionLogTableMap::COL_HOSTNAME => 'Hostname',
            SpyOmsTransitionLogTableMap::COL_CREATED_AT => 'Date',
        ];

        $config->setHeader($headers);

        $config->setUrl('table-ajax?id-order='.$this->getIdOrder());

        return $config;
    }

    /**
     * @inheritDoc
     */
    protected function prepareData(TableConfiguration $config)
    {
        $idOrder = $this->getIdOrder();

        $query = $this->omsQueryContainer->queryLogByIdOrder($idOrder);

        $result = $this->runQuery($query, $config);

        foreach ($result as $i => $row) {

            $row = $this->formatArray($row, SpyOmsTransitionLogTableMap::COL_CONDITIONS);
            $row = $this->formatArray($row, SpyOmsTransitionLogTableMap::COL_COMMANDS);

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

}
