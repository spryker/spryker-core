<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Ui\Communication\Plugin\Grid;

use Propel\Runtime\ActiveQuery\ModelCriteria;

class BooleanColumn extends DefaultColumn
{

    const PARAM_TRUE = 'true';
    const PARAM_FALSE = 'false';

    protected $type = 'BOOLEAN';

    /**
     * @param ModelCriteria $query
     *
     * @return mixed|ModelCriteria
     */
    protected function applyFilter(ModelCriteria $query)
    {
        $resolvedFilterValue = $this->getResolvedFilterValue();

        if ($this->isFilterable && $resolvedFilterValue !== null) {
            // TODO namespace class
            $qualifiedColumnName = (new \SprykerFeature_Zed_Library_Propel_Helper())
                                        ->getFullyQualifiedColumnName($query, $this->name);

            $query->where($qualifiedColumnName . \Propel\Runtime\ActiveQuery\Criteria::EQUAL . ' ?', $resolvedFilterValue, \PDO::PARAM_STR);
        }

        return $query;
    }

    /**
     * @return int|null
     */
    protected function getResolvedFilterValue()
    {
        $filterValue = strtolower($this->getFilterValue());

        if (!$filterValue) {
            return;
        }

        if (strpos('true', $filterValue) === 0) {
            return 1;
        }

        if (strpos('false', $filterValue) === 0) {
            return 0;
        }

        return;
    }

    /**
     * @param array $data
     *
     * @return array
     */
    public function getData(array $data)
    {
        $data = parent::getData($data);

        $data = $this->convertRowValuesToBoolean($data);

        return $data;
    }

    /**
     * @param array $data
     *
     * @return array
     */
    protected function convertRowValuesToBoolean(array $data)
    {
        foreach ($data[DefaultRowsRenderer::DATA_DEFINITION_ROWS] as $key => $row) {
            if (isset($row[$this->name])
                && !is_bool($row[$this->name])
            ) {
                $row[$this->name] = (boolean) $row[$this->name];
                $data[DefaultRowsRenderer::DATA_DEFINITION_ROWS][$key] = $row;
            }
        }

        return $data;
    }

}
