<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImport\Business\DetermineStrategy;

use RuntimeException;
use Spryker\Zed\DataImport\Business\Model\ApplicableDatabaseEngineAwareInterface;
use Spryker\Zed\DataImport\DataImportConfig;

class DatabaseDetermineStrategy implements DetermineStrategyInterface
{
    /**
     * @var \Spryker\Zed\DataImport\Business\Model\ApplicableDatabaseEngineAwareInterface[]
     */
    protected $databaseEngineAwares;

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\ApplicableDatabaseEngineAwareInterface[] $databaseEngineAwares
     */
    public function __construct(array $databaseEngineAwares)
    {
        $this->databaseEngineAwares = $databaseEngineAwares;
    }

    /**
     * @inheritDoc
     */
    public function getApplicable()
    {
        foreach ($this->databaseEngineAwares as $databaseEngineAware) {
            if ($this->isApplicable($databaseEngineAware)) {
                return $databaseEngineAware;
            }
        }

        throw new RuntimeException('Applicable DataSetWriter not found.');
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\ApplicableDatabaseEngineAwareInterface $databaseEngineAware
     *
     * @return bool
     */
    protected function isApplicable($databaseEngineAware): bool
    {
        return (
            (
                $databaseEngineAware instanceof ApplicableDatabaseEngineAwareInterface
                && $databaseEngineAware->isApplicableDatabaseEngine()
            )
            || !$databaseEngineAware instanceof ApplicableDatabaseEngineAwareInterface
        );
    }
}
