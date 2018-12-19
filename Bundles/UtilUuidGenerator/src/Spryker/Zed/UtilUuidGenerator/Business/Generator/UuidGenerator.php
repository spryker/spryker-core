<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\UtilUuidGenerator\Business\Generator;

use Exception;
use Generated\Shared\Transfer\UuidGeneratorConfigurationTransfer;
use Spryker\Zed\UtilUuidGenerator\Persistence\UtilUuidGeneratorEntityManagerInterface;
use Spryker\Zed\UtilUuidGenerator\Persistence\UtilUuidGeneratorRepositoryInterface;

class UuidGenerator implements UuidGeneratorInterface
{
    protected const ERROR_MESSAGE_UUID = 'Table %s does not contain field uuid.';

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
     * @param \Generated\Shared\Transfer\UuidGeneratorConfigurationTransfer $uuidGeneratorConfigurationTransfer
     *
     * @throws \Exception
     *
     * @return int
     */
    public function generate(UuidGeneratorConfigurationTransfer $uuidGeneratorConfigurationTransfer): int
    {
        $uuidGeneratorConfigurationTransfer->requireModule()
            ->requireTable();

        if (!$this->repository->hasUuidField($uuidGeneratorConfigurationTransfer)) {
            throw new Exception(sprintf(static::ERROR_MESSAGE_UUID, $uuidGeneratorConfigurationTransfer->getTable()));
        }

        return $this->entityManager->fillEmptyUuids($uuidGeneratorConfigurationTransfer);
    }
}
