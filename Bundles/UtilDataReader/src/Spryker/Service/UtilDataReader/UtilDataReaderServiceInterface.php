<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilDataReader;

use Propel\Runtime\ActiveQuery\ModelCriteria;
use Spryker\Shared\SqlCriteriaBuilder\CriteriaBuilder\CriteriaBuilderInterface;
use Spryker\Zed\Kernel\Persistence\QueryContainer\QueryContainerInterface;

/**
 * @method \Spryker\Service\UtilDataReader\UtilDataReaderServiceFactory getFactory()
 */
interface UtilDataReaderServiceInterface
{
    /**
     * Specification:
     * - Returns a CSV Reader
     *
     * @api
     *
     * @return \Spryker\Service\UtilDataReader\Model\Reader\Csv\CsvReaderInterface
     */
    public function getCsvReader();

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
    public function getCsvBatchIterator($fileName, $chunkSize = 10);

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
    public function getXmlBatchIterator($fileName, $rootNodeName, $chunkSize = -1);

    /**
     * @api
     *
     * Specification:
     * - Returns a PdoBatchIterator
     * - Loads a chunk of entities with given CriteriaBuilderInterface
     *
     * @deprecated Use getBatchIteratorOrdered() instead. Method getPdoBatchIterator() does not work with sliced data.
     *
     * @param \Spryker\Shared\SqlCriteriaBuilder\CriteriaBuilder\CriteriaBuilderInterface $criteriaBuilder
     * @param \Spryker\Zed\Kernel\Persistence\QueryContainer\QueryContainerInterface $connection
     * @param int $chunkSize
     *
     * @return \Spryker\Service\UtilDataReader\Model\BatchIterator\CountableIteratorInterface
     */
    public function getPdoBatchIterator(CriteriaBuilderInterface $criteriaBuilder, QueryContainerInterface $connection, $chunkSize = 100);

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
     * @param string $orderBy
     * @param string $orderByDirection
     *
     * @return \Spryker\Service\UtilDataReader\Model\BatchIterator\CountableIteratorInterface
     */
    public function getBatchIteratorOrdered(CriteriaBuilderInterface $criteriaBuilder, QueryContainerInterface $connection, $chunkSize, $orderBy, $orderByDirection);

    /**
     * @api
     *
     * Specification:
     * - Returns a PropelBatchIterator
     * - Loads a chunk of PropelEntities with given ModelCriteria
     *
     * @deprecated Use getBatchIteratorOrdered() instead. Method getPropelBatchIteratorOrdered() does not work with sliced data.
     *
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param int $chunkSize
     *
     * @return \Spryker\Service\UtilDataReader\Model\BatchIterator\CountableIteratorInterface
     */
    public function getPropelBatchIterator(ModelCriteria $query, $chunkSize = 100);

    /**
     * Specification:
     * - Returns a PropelBatchIterator
     * - Loads a chunk of PropelEntities with given ModelCriteria
     *
     * @api
     *
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param int $chunkSize
     * @param string $orderBy
     * @param string $orderByDirection
     *
     * @return \Spryker\Service\UtilDataReader\Model\BatchIterator\CountableIteratorInterface
     */
    public function getPropelBatchIteratorOrdered(ModelCriteria $query, $chunkSize, $orderBy, $orderByDirection);

    /**
     * Specification:
     * - Returns a CountableIteratorInterface
     * - Reads a YAML file and make data available for batch processing
     *
     * @api
     *
     * @param string $fileName
     * @param int $chunkSize
     *
     * @return \Spryker\Service\UtilDataReader\Model\BatchIterator\CountableIteratorInterface
     */
    public function getYamlBatchIterator($fileName, $chunkSize = -1);
}
