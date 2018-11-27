<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\UtilUuidGenerator\Business\Generator;

use Exception;
use Spryker\Zed\UtilUuidGenerator\Business\Builder\QueryBuilderInterface;
use Spryker\Zed\UtilUuidGenerator\Persistence\UtilUuidGeneratorEntityManagerInterface;

class UuidGenerator implements UuidGeneratorInterface
{
    protected const COLUMN_UUID = 'uuid';
    protected const ERROR_MESSAGE_UUID = 'Table %s does not contain field %s.';

    /**
     * @var \Spryker\Zed\UtilUuidGenerator\Business\Builder\QueryBuilderInterface
     */
    protected $queryBuilder;

    /**
     * @var \Spryker\Zed\UtilUuidGenerator\Persistence\UtilUuidGeneratorEntityManagerInterface
     */
    protected $entityManager;

    /**
     * @param \Spryker\Zed\UtilUuidGenerator\Business\Builder\QueryBuilderInterface $queryBuilder
     * @param \Spryker\Zed\UtilUuidGenerator\Persistence\UtilUuidGeneratorEntityManagerInterface $entityManager
     */
    public function __construct(QueryBuilderInterface $queryBuilder, UtilUuidGeneratorEntityManagerInterface $entityManager)
    {
        $this->queryBuilder = $queryBuilder;
        $this->entityManager = $entityManager;
    }

    /**
     * @param string $tableName
     *
     * @throws \Exception
     *
     * @return int
     */
    public function generate(string $tableName): int
    {
        $query = $this->queryBuilder->buildQuery($tableName);

        if (!$query->getTableMap()->hasColumn(static::COLUMN_UUID)) {
            throw new Exception(sprintf(static::ERROR_MESSAGE_UUID, $tableName, static::COLUMN_UUID));
        }

        return $this->entityManager->setEmptyUuids($query);
    }
}
