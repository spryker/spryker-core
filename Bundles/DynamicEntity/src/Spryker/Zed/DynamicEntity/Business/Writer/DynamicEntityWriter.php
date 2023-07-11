<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DynamicEntity\Business\Writer;

use Generated\Shared\Transfer\DynamicEntityCollectionRequestTransfer;
use Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer;
use Spryker\Zed\DynamicEntity\Business\Exception\DynamicEntityConfigurationNotFoundException;
use Spryker\Zed\DynamicEntity\Business\Validator\DynamicEntityValidatorInterface;
use Spryker\Zed\DynamicEntity\Persistence\DynamicEntityEntityManagerInterface;
use Spryker\Zed\DynamicEntity\Persistence\DynamicEntityRepositoryInterface;

class DynamicEntityWriter implements DynamicEntityWriterInterface
{
    /**
     * @var \Spryker\Zed\DynamicEntity\Persistence\DynamicEntityRepositoryInterface
     */
    protected DynamicEntityRepositoryInterface $repository;

    /**
     * @var \Spryker\Zed\DynamicEntity\Persistence\DynamicEntityEntityManagerInterface
     */
    protected DynamicEntityEntityManagerInterface $entityManager;

    /**
     * @var \Spryker\Zed\DynamicEntity\Business\Validator\DynamicEntityValidatorInterface
     */
    protected DynamicEntityValidatorInterface $dynamicEntityValidator;

    /**
     * @var \Spryker\Zed\DynamicEntity\Business\Validator\DynamicEntityValidatorInterface
     */
    protected DynamicEntityValidatorInterface $dynamicEntityUpdateValidator;

    /**
     * @param \Spryker\Zed\DynamicEntity\Persistence\DynamicEntityRepositoryInterface $repository
     * @param \Spryker\Zed\DynamicEntity\Persistence\DynamicEntityEntityManagerInterface $entityManager
     * @param \Spryker\Zed\DynamicEntity\Business\Validator\DynamicEntityValidatorInterface $dynamicEntityValidator
     * @param \Spryker\Zed\DynamicEntity\Business\Validator\DynamicEntityValidatorInterface $dynamicEntityUpdateValidator
     */
    public function __construct(
        DynamicEntityRepositoryInterface $repository,
        DynamicEntityEntityManagerInterface $entityManager,
        DynamicEntityValidatorInterface $dynamicEntityValidator,
        DynamicEntityValidatorInterface $dynamicEntityUpdateValidator
    ) {
        $this->repository = $repository;
        $this->entityManager = $entityManager;
        $this->dynamicEntityValidator = $dynamicEntityValidator;
        $this->dynamicEntityUpdateValidator = $dynamicEntityUpdateValidator;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityCollectionRequestTransfer $dynamicEntityCollectionRequestTransfer
     *
     * @throws \Spryker\Zed\DynamicEntity\Business\Exception\DynamicEntityConfigurationNotFoundException
     *
     * @return \Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer
     */
    public function create(DynamicEntityCollectionRequestTransfer $dynamicEntityCollectionRequestTransfer): DynamicEntityCollectionResponseTransfer
    {
        $dynamicEntityConfigurationTransfer = $this->repository->findDynamicEntityConfigurationByTableAlias(
            $dynamicEntityCollectionRequestTransfer->getTableAliasOrFail(),
        );

        if ($dynamicEntityConfigurationTransfer === null) {
            throw new DynamicEntityConfigurationNotFoundException();
        }

        $dynamicEntityCollectionResponseTransfer = $this->dynamicEntityValidator->validate(
            $dynamicEntityCollectionRequestTransfer,
            $dynamicEntityConfigurationTransfer->getDynamicEntityDefinitionOrFail(),
            new DynamicEntityCollectionResponseTransfer(),
        );

        if ($dynamicEntityCollectionResponseTransfer->getErrors()->count()) {
            return $dynamicEntityCollectionResponseTransfer;
        }

        return $this->entityManager->createDynamicEntityCollection($dynamicEntityCollectionRequestTransfer, $dynamicEntityConfigurationTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityCollectionRequestTransfer $dynamicEntityCollectionRequestTransfer
     *
     * @throws \Spryker\Zed\DynamicEntity\Business\Exception\DynamicEntityConfigurationNotFoundException
     *
     * @return \Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer
     */
    public function update(DynamicEntityCollectionRequestTransfer $dynamicEntityCollectionRequestTransfer): DynamicEntityCollectionResponseTransfer
    {
        $dynamicEntityConfigurationTransfer = $this->repository->findDynamicEntityConfigurationByTableAlias(
            $dynamicEntityCollectionRequestTransfer->getTableAliasOrFail(),
        );

        if ($dynamicEntityConfigurationTransfer === null) {
            throw new DynamicEntityConfigurationNotFoundException();
        }

        $dynamicEntityCollectionResponseTransfer = $this->dynamicEntityUpdateValidator->validate(
            $dynamicEntityCollectionRequestTransfer,
            $dynamicEntityConfigurationTransfer->getDynamicEntityDefinitionOrFail(),
            new DynamicEntityCollectionResponseTransfer(),
        );

        if ($dynamicEntityCollectionResponseTransfer->getErrors()->count()) {
            return $dynamicEntityCollectionResponseTransfer;
        }

        return $this->entityManager->updateDynamicEntityCollection($dynamicEntityCollectionRequestTransfer, $dynamicEntityConfigurationTransfer);
    }
}
