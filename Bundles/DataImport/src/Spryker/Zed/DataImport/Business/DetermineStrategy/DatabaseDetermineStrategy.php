<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImport\Business\DetermineStrategy;

use RuntimeException;
use Spryker\Zed\DataImport\Business\Model\ApplicableDatabaseEngineAwareInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterInterface;

class DatabaseDetermineStrategy implements DetermineStrategyInterface
{
    /**
     * @var \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterInterface[]
     */
    protected $dataSetWriters;

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterInterface[] $dataSetWriters
     */
    public function __construct(array $dataSetWriters)
    {
        $this->dataSetWriters = $dataSetWriters;
    }

    /**
     * @inheritDoc
     *
     * @throws \RuntimeException
     */
    public function getApplicableDataSetWriter(): DataSetWriterInterface
    {
        foreach ($this->dataSetWriters as $dataSetWriter) {
            if ($this->isApplicable($dataSetWriter)) {
                return $dataSetWriter;
            }
        }

        throw new RuntimeException('Applicable DataSetWriter not found.');
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterInterface $dataSetWriter
     *
     * @return bool
     */
    protected function isApplicable($dataSetWriter): bool
    {
        return (
            (
                $dataSetWriter instanceof ApplicableDatabaseEngineAwareInterface
                && $dataSetWriter->isApplicable()
            )
            || !$dataSetWriter instanceof ApplicableDatabaseEngineAwareInterface
        );
    }
}
