<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\Propel\Business\Formatter;

use Propel\Runtime\ActiveQuery\BaseModelCriteria;
use Propel\Runtime\DataFetcher\DataFetcherInterface;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Formatter\AbstractFormatter;
use Propel\Runtime\Formatter\SimpleArrayFormatter;

class PropelArraySetFormatter extends SimpleArrayFormatter
{

    /**
     * Define the hydration schema based on a query object.
     * Fills the Formatter's properties using a Criteria as source
     *
     * @param BaseModelCriteria    $criteria
     * @param DataFetcherInterface $dataFetcher
     *
     * @return $this|AbstractFormatter The current formatter object
     */
    public function init(BaseModelCriteria $criteria, DataFetcherInterface $dataFetcher = null)
    {
        $this->dbName = $criteria->getDbName();
        $this->setClass($criteria->getModelName());
        $this->setWith($criteria->getWith());
        $this->asColumns = array_merge($criteria->getSelectColumns(), $criteria->getAsColumns());
        $this->hasLimit = $criteria->getLimit() != -1;
        if ($dataFetcher) {
            $this->setDataFetcher($dataFetcher);
        }

        return $this;
    }
    /**
     * @param DataFetcherInterface $dataFetcher
     *
     * @return array
     * @throws PropelException
     */
    public function format(DataFetcherInterface $dataFetcher = null)
    {
        $this->checkInit();
        if ($dataFetcher) {
            $this->setDataFetcher($dataFetcher);
        } else {
            $dataFetcher = $this->getDataFetcher();
        }
        $formattedArray = [];
        if ($this->isWithOneToMany() && $this->hasLimit) {
            throw new PropelException('Cannot use limit() in conjunction with with() on a one-to-many relationship. Please remove the with() call, or the limit() call.');
        }
        foreach ($dataFetcher as $row) {
            $rowArray = $this->getStructuredArrayFromRow($row);
            if ($rowArray !== false) {
                $formattedArray[] = $rowArray;
            }
        }
        $dataFetcher->close();

        return $formattedArray;
    }

}
