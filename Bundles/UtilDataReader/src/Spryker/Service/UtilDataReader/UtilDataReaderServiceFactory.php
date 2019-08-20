<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilDataReader;

use Propel\Runtime\ActiveQuery\ModelCriteria;
use Spryker\Service\Kernel\AbstractServiceFactory;
use Spryker\Service\UtilDataReader\Model\BatchIterator\CsvBatchIterator;
use Spryker\Service\UtilDataReader\Model\BatchIterator\PdoBatchIterator;
use Spryker\Service\UtilDataReader\Model\BatchIterator\PropelBatchIterator;
use Spryker\Service\UtilDataReader\Model\BatchIterator\XmlBatchIterator;
use Spryker\Service\UtilDataReader\Model\BatchIterator\YamlBatchIterator;
use Spryker\Service\UtilDataReader\Model\Reader\Csv\CsvReader;
use Spryker\Shared\SqlCriteriaBuilder\CriteriaBuilder\CriteriaBuilderInterface;
use Spryker\Zed\Kernel\Persistence\QueryContainer\QueryContainerInterface;

class UtilDataReaderServiceFactory extends AbstractServiceFactory
{
    /**
     * @return \Spryker\Service\UtilDataReader\Model\Reader\Csv\CsvReaderInterface
     */
    public function createCsvReader()
    {
        return new CsvReader();
    }

    /**
     * @param string $fileName
     * @param int $chunkSize
     *
     * @return \Spryker\Service\UtilDataReader\Model\BatchIterator\CountableIteratorInterface
     */
    public function createCsvBatchIterator($fileName, $chunkSize)
    {
        return new CsvBatchIterator($this->createCsvReader(), $fileName, $chunkSize);
    }

    /**
     * @param string $fileName
     * @param string $rootNodeName
     * @param int $chunkSize
     *
     * @return \Spryker\Service\UtilDataReader\Model\BatchIterator\CountableIteratorInterface
     */
    public function createXmlBatchIterator($fileName, $rootNodeName, $chunkSize)
    {
        return new XmlBatchIterator($fileName, $rootNodeName, $chunkSize);
    }

    /**
     * @param \Spryker\Shared\SqlCriteriaBuilder\CriteriaBuilder\CriteriaBuilderInterface $criteriaBuilder
     * @param \Spryker\Zed\Kernel\Persistence\QueryContainer\QueryContainerInterface $connection
     * @param int $chunkSize
     * @param string|null $orderBy
     * @param string|null $orderByDirection
     *
     * @return \Spryker\Service\UtilDataReader\Model\BatchIterator\PdoBatchIterator
     */
    public function createPdoBatchIterator(CriteriaBuilderInterface $criteriaBuilder, QueryContainerInterface $connection, $chunkSize, $orderBy = null, $orderByDirection = null)
    {
        return new PdoBatchIterator($criteriaBuilder, $connection, $chunkSize, $orderBy, $orderByDirection);
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param int $chunkSize
     * @param string|null $orderBy
     * @param string|null $orderByDirection
     *
     * @return \Spryker\Service\UtilDataReader\Model\BatchIterator\PropelBatchIterator
     */
    public function createPropelBatchIterator(ModelCriteria $query, $chunkSize, $orderBy = null, $orderByDirection = null)
    {
        return new PropelBatchIterator($query, $chunkSize, $orderBy, $orderByDirection);
    }

    /**
     * @param string $fileName
     * @param int $chunkSize
     *
     * @return \Spryker\Service\UtilDataReader\Model\BatchIterator\YamlBatchIterator|\Spryker\Service\UtilDataReader\Model\BatchIterator\CountableIteratorInterface
     */
    public function createYamlBatchIterator($fileName, $chunkSize)
    {
        return new YamlBatchIterator(
            $this->getYamlReader(),
            $fileName,
            $chunkSize
        );
    }

    /**
     * @return \Spryker\Service\UtilDataReader\Dependency\YamlReaderInterface
     */
    protected function getYamlReader()
    {
        return $this->getProvidedDependency(UtilDataReaderDependencyProvider::YAML_READER);
    }
}
