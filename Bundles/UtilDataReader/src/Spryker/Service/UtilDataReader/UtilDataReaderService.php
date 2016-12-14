<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilDataReader;

use Propel\Runtime\ActiveQuery\ModelCriteria;
use Spryker\Service\Kernel\AbstractService;
use Spryker\Shared\SqlCriteriaBuilder\CriteriaBuilder\CriteriaBuilderInterface;
use Spryker\Zed\Kernel\Persistence\QueryContainer\QueryContainerInterface;

/**
 * @method \Spryker\Service\UtilDataReader\UtilDataReaderServiceFactory getFactory()
 */
class UtilDataReaderService extends AbstractService implements UtilDataReaderServiceInterface
{

    /**
     * Specification:
     * - Returns a CSV Reader
     *
     * @api
     *
     * @return \Spryker\Service\UtilDataReader\Model\Reader\Csv\CsvReaderInterface
     */
    public function getCsvReader()
    {
        return $this->getFactory()->createCsvReader();
    }

    /**
     * Specification:
     * - Returns a CsvBatchIterator
     * - Uses CSV Reader to read csv files
     *
     * @api
     *
     * @param string $fileName
     * @param int $chunkSize
     *
     * @return \Spryker\Service\UtilDataReader\Model\BatchIterator\CountableIteratorInterface
     */
    public function getCsvBatchIterator($fileName, $chunkSize = 10)
    {
        return $this->getFactory()->createCsvBatchIterator($fileName, $chunkSize);
    }

    /**
     * Specification:
     * - Returns a XmlBatchIterator
     * - Loads entries from given xml file
     *
     * @api
     *
     * @param string $fileName
     * @param string $rootNodeName
     * @param int $chunkSize
     *
     * @return \Spryker\Service\UtilDataReader\Model\BatchIterator\CountableIteratorInterface
     */
    public function getXmlBatchIterator($fileName, $rootNodeName, $chunkSize = -1)
    {
        return $this->getFactory()->createXmlBatchIterator($fileName, $rootNodeName, $chunkSize);
    }

    /**
     * Specification:
     * - Returns a PdoBatchIterator
     * - Loads a chunk of entities with given CriteriaBuilderInterface
     *
     * @api
     *
     * @param \Spryker\Shared\SqlCriteriaBuilder\CriteriaBuilder\CriteriaBuilderInterface $criteriaBuilder
     * @param \Spryker\Zed\Kernel\Persistence\QueryContainer\QueryContainerInterface $connection
     * @param int $chunkSize
     *
     * @return \Spryker\Service\UtilDataReader\Model\BatchIterator\CountableIteratorInterface
     */
    public function getPdoBatchIterator(CriteriaBuilderInterface $criteriaBuilder, QueryContainerInterface $connection, $chunkSize = 100)
    {
        return $this->getFactory()->createPdoBatchIterator($criteriaBuilder, $connection, $chunkSize);
    }

    /**
     * Specification:
     * - Returns a PropelBatchIterator
     * - Loads a chunk of PropelEntities with given ModelCriteria
     *
     * @api
     *
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param int $chunkSize
     *
     * @return \Spryker\Service\UtilDataReader\Model\BatchIterator\CountableIteratorInterface
     */
    public function getPropelBatchIterator(ModelCriteria $query, $chunkSize = 100)
    {
        return $this->getFactory()->createPropelBatchIterator($query, $chunkSize);
    }

}
