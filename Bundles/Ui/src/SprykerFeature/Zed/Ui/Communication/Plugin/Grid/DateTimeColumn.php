<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Ui\Communication\Plugin\Grid;

use SprykerFeature\Zed\Ui\Communication\Grid\DateTimeColumn\FilterFormatAbstract;
use SprykerFeature\Zed\Ui\Communication\Grid\DateTimeColumn\TimeRangeGenerator\TimeRangeGeneratorInterface;
use Propel\Runtime\ActiveQuery\ModelCriteria;

class DateTimeColumn extends DefaultColumn
{

    protected $type = 'DATETIME';

    /**
     * @param ModelCriteria $query
     *
     * @return mixed|ModelCriteria
     */
    public function applyFilter(ModelCriteria $query)
    {
        $timeRangeGenerator = $this->getUnambiguousTimeRangeGenerator();

        if ($this->isFilterable && $timeRangeGenerator) {
            $qualifiedColumnName = (new \SprykerFeature_Zed_Library_Propel_Helper())
                                        ->getFullyQualifiedColumnName($query, $this->name);

            $start = $timeRangeGenerator->getStartDateTimeString();
            $end = $timeRangeGenerator->getEndDateTimeString();

            $query->where($qualifiedColumnName . \Propel\Runtime\ActiveQuery\Criteria::GREATER_THAN . ' ?', $start, \PDO::PARAM_STR);
            $query->where($qualifiedColumnName . \Propel\Runtime\ActiveQuery\Criteria::LESS_THAN . ' ?', $end, \PDO::PARAM_STR);
        }

        return $query;
    }

    /**
     * @return null|TimeRangeGeneratorInterface
     */
    protected function getUnambiguousTimeRangeGenerator()
    {
        $timeRangeGenerators = [];
        foreach ($this->getFilterFormats() as $filterFormat) {
            $timeRangeGenerator = $filterFormat->getTimeRangeGenerator();
            if ($timeRangeGenerator) {
                $timeRangeGenerators[] = $timeRangeGenerator;
            }
        }

        if (count($timeRangeGenerators) === 1) {
            return $timeRangeGenerators[0];
        }

        return;
    }

    /**
     * @return FilterFormatAbstract[]
     */
    protected function getFilterFormats()
    {
        $filterValue = $this->getFilterValue();

        if (!$filterValue) {
            return [];
        }

        return $this->getDependencyContainer()->getDateTimeColumnFormats($filterValue);
    }

    /**
     * @param array $data
     *
     * @return array|void
     */
    public function getData(array $data)
    {
        $data = parent::getData($data);

        $data['suggestions'][$this->name] = $this->getSuggestions();

        return $data;
    }

    /**
     * @return array
     */
    protected function getSuggestions()
    {
        $suggestions = [];

        foreach ($this->getFilterFormats() as $filterFormat) {
            $suggestions = array_merge($suggestions, $filterFormat->getSuggestions());
        }

        return $suggestions;
    }

}
