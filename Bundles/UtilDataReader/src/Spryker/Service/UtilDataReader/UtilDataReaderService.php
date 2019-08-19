<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilDataReader;

use Propel\Runtime\ActiveQuery\ModelCriteria;
use Spryker\Service\Kernel\AbstractService;
use Spryker\Service\UtilDataReader\Model\BatchIterator\CountableIteratorInterface;
use Spryker\Shared\SqlCriteriaBuilder\CriteriaBuilder\CriteriaBuilderInterface;
use Spryker\Zed\Kernel\Persistence\QueryContainer\QueryContainerInterface;

/**
 * @method \Spryker\Service\UtilDataReader\UtilDataReaderServiceFactory getFactory()
 */
class UtilDataReaderService extends AbstractService implements UtilDataReaderServiceInterface
{
    /**
     * {@inheritdoc}
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
     * {@inheritdoc}
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
     * {@inheritdoc}
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
     * {@inheritdoc}
     *
     * @api
     *
     * @deprecated Use getBatchIteratorOrdered() instead, getPdoBatchIterator() does not work with sliced data.
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
     * {@inheritdoc}
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
    public function getBatchIteratorOrdered(CriteriaBuilderInterface $criteriaBuilder, QueryContainerInterface $connection, $chunkSize, $orderBy, $orderByDirection)
    {
        return $this->getFactory()->createPdoBatchIterator($criteriaBuilder, $connection, $chunkSize, $orderBy, $orderByDirection);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @deprecated Use getBatchIteratorOrdered() instead, getPropelBatchIteratorOrdered() does not work with sliced data.
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

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param int $chunkSize
     *
     * @return \Spryker\Service\UtilDataReader\Model\BatchIterator\CountableIteratorInterface
     */
    public function getPropelBatchIteratorOrdered(ModelCriteria $query, $chunkSize, $orderBy, $orderByDirection)
    {
        return $this->getFactory()->createPropelBatchIterator($query, $chunkSize, $orderBy, $orderByDirection);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $fileName
     * @param int $chunkSize
     *
     * @return \Spryker\Service\UtilDataReader\Model\BatchIterator\CountableIteratorInterface
     */
    public function getYamlBatchIterator($fileName, $chunkSize = -1)
    {
        return $this->getFactory()->createYamlBatchIterator($fileName, $chunkSize);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param int $chunkSize
     *
     * @return \Spryker\Service\UtilDataReader\Model\BatchIterator\CountableIteratorInterface
     */
    public function getPropelQueryBatchIterator(ModelCriteria $query, int $chunkSize = 100): CountableIteratorInterface
    {
        return $this->getFactory()->createPropelQueryBatchIterator($query, $chunkSize);
    }
}
