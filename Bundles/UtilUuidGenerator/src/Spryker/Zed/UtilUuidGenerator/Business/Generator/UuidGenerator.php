<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\UtilUuidGenerator\Business\Generator;

use Spryker\Zed\UtilUuidGenerator\Persistence\UtilUuidGeneratorEntityManagerInterface;
use Spryker\Zed\UtilUuidGenerator\Persistence\UtilUuidGeneratorRepositoryInterface;

class UuidGenerator implements UuidGeneratorInterface
{
    /**
     * @var \Spryker\Zed\UtilUuidGenerator\Persistence\UtilUuidGeneratorRepositoryInterface
     */
    protected $repository;

    /**
     * @var \Spryker\Zed\UtilUuidGenerator\Persistence\UtilUuidGeneratorEntityManagerInterface
     */
    protected $entityManager;

    /**
     * @param \Spryker\Zed\UtilUuidGenerator\Persistence\UtilUuidGeneratorRepositoryInterface $repository
     * @param \Spryker\Zed\UtilUuidGenerator\Persistence\UtilUuidGeneratorEntityManagerInterface $entityManager
     */
    public function __construct(
        UtilUuidGeneratorRepositoryInterface $repository,
        UtilUuidGeneratorEntityManagerInterface $entityManager
    ) {
        $this->repository = $repository;
        $this->entityManager = $entityManager;
    }

    /**
     * @param string $tableName
     *
     * @return int
     */
    public function generate(string $tableName): int
    {
        if (!$this->repository->hasQueryUuidField($tableName)) {
            return 0;
        }

        return $this->entityManager->fillEmptyUuids($tableName);
    }
}
