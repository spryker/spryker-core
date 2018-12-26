<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Uuid\Business\Generator;

use Exception;
use Generated\Shared\Transfer\UuidGeneratorConfigurationTransfer;
use Generated\Shared\Transfer\UuidGeneratorReportTransfer;
use Spryker\Zed\Uuid\Persistence\UuidEntityManagerInterface;
use Spryker\Zed\Uuid\Persistence\UuidRepositoryInterface;

class UuidGenerator implements UuidGeneratorInterface
{
    protected const ERROR_MESSAGE_UUID = 'Table %s does not contain field uuid.';

    /**
     * @var \Spryker\Zed\Uuid\Persistence\UuidRepositoryInterface
     */
    protected $repository;

    /**
     * @var \Spryker\Zed\Uuid\Persistence\UuidEntityManagerInterface
     */
    protected $entityManager;

    /**
     * @param \Spryker\Zed\Uuid\Persistence\UuidRepositoryInterface $repository
     * @param \Spryker\Zed\Uuid\Persistence\UuidEntityManagerInterface $entityManager
     */
    public function __construct(
        UuidRepositoryInterface $repository,
        UuidEntityManagerInterface $entityManager
    ) {
        $this->repository = $repository;
        $this->entityManager = $entityManager;
    }

    /**
     * @param \Generated\Shared\Transfer\UuidGeneratorConfigurationTransfer $uuidGeneratorConfigurationTransfer
     *
     * @throws \Exception
     *
     * @return \Generated\Shared\Transfer\UuidGeneratorReportTransfer
     */
    public function generate(UuidGeneratorConfigurationTransfer $uuidGeneratorConfigurationTransfer): UuidGeneratorReportTransfer
    {
        $uuidGeneratorConfigurationTransfer->requireModule()
            ->requireTable();

        if (!$this->repository->isUuidColumnDefinedInTable($uuidGeneratorConfigurationTransfer)) {
            throw new Exception(sprintf(static::ERROR_MESSAGE_UUID, $uuidGeneratorConfigurationTransfer->getTable()));
        }

        return $this->entityManager->fillEmptyUuids($uuidGeneratorConfigurationTransfer);
    }
}
