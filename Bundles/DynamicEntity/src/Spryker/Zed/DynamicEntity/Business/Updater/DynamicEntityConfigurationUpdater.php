<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DynamicEntity\Business\Updater;

use ArrayObject;
use Generated\Shared\Transfer\DynamicEntityConfigurationCollectionRequestTransfer;
use Generated\Shared\Transfer\DynamicEntityConfigurationCollectionResponseTransfer;
use Generated\Shared\Transfer\DynamicEntityConfigurationTransfer;
use Spryker\Zed\DynamicEntity\Business\Validator\DynamicEntityConfigurationValidatorInterface;
use Spryker\Zed\DynamicEntity\Persistence\DynamicEntityEntityManagerInterface;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;

class DynamicEntityConfigurationUpdater implements DynamicEntityConfigurationUpdaterInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\DynamicEntity\Business\Validator\DynamicEntityConfigurationValidatorInterface
     */
    protected DynamicEntityConfigurationValidatorInterface $dynamicEntityConfigurationValidator;

    /**
     * @var \Spryker\Zed\DynamicEntity\Persistence\DynamicEntityEntityManagerInterface
     */
    protected DynamicEntityEntityManagerInterface $dynamicEntityManager;

    /**
     * @param \Spryker\Zed\DynamicEntity\Business\Validator\DynamicEntityConfigurationValidatorInterface $dynamicEntityConfigurationValidator
     * @param \Spryker\Zed\DynamicEntity\Persistence\DynamicEntityEntityManagerInterface $dynamicEntityManager
     */
    public function __construct(
        DynamicEntityConfigurationValidatorInterface $dynamicEntityConfigurationValidator,
        DynamicEntityEntityManagerInterface $dynamicEntityManager
    ) {
        $this->dynamicEntityConfigurationValidator = $dynamicEntityConfigurationValidator;
        $this->dynamicEntityManager = $dynamicEntityManager;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationCollectionRequestTransfer $dynamicEntityConfigurationCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\DynamicEntityConfigurationCollectionResponseTransfer
     */
    public function updateDynamicEntityConfigurationCollection(
        DynamicEntityConfigurationCollectionRequestTransfer $dynamicEntityConfigurationCollectionRequestTransfer
    ): DynamicEntityConfigurationCollectionResponseTransfer {
        $this->assertRequiredFields($dynamicEntityConfigurationCollectionRequestTransfer);

        $dynamicEntityConfigurationCollectionResponseTransfer = new DynamicEntityConfigurationCollectionResponseTransfer();
        $dynamicEntityConfigurationCollectionResponseTransfer->setDynamicEntityConfigurations(
            $dynamicEntityConfigurationCollectionRequestTransfer->getDynamicEntityConfigurations(),
        );

        $dynamicEntityConfigurationCollectionResponseTransfer = $this->dynamicEntityConfigurationValidator->validate(
            $dynamicEntityConfigurationCollectionResponseTransfer,
        );

        if ($dynamicEntityConfigurationCollectionResponseTransfer->getErrors()->count()) {
            return $dynamicEntityConfigurationCollectionResponseTransfer;
        }

        $updatedDynamicEntityConfigurationTransfers = $this->getTransactionHandler()->handleTransaction(
            function () use ($dynamicEntityConfigurationCollectionResponseTransfer) {
                return $this->executeUpdateDynamicEntityConfigurationCollectionTransaction(
                    $dynamicEntityConfigurationCollectionResponseTransfer->getDynamicEntityConfigurations(),
                );
            },
        );

        return $dynamicEntityConfigurationCollectionResponseTransfer->setDynamicEntityConfigurations($updatedDynamicEntityConfigurationTransfers);
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer> $dynamicEntityConfigurationTransfers
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer>
     */
    protected function executeUpdateDynamicEntityConfigurationCollectionTransaction(
        ArrayObject $dynamicEntityConfigurationTransfers
    ): ArrayObject {
        $persistedDynamicEntityConfigurationTransfers = new ArrayObject();

        foreach ($dynamicEntityConfigurationTransfers as $entityIdentifier => $dynamicEntityConfigurationTransfer) {
            $dynamicEntityConfigurationTransfer = $this->dynamicEntityManager->updateDynamicEntityConfiguration($dynamicEntityConfigurationTransfer);
            $persistedDynamicEntityConfigurationTransfers->offsetSet(
                $entityIdentifier,
                $dynamicEntityConfigurationTransfer,
            );
        }

        return $persistedDynamicEntityConfigurationTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationCollectionRequestTransfer $dynamicEntityConfigurationCollectionRequestTransfer
     *
     * @return void
     */
    protected function assertRequiredFields(DynamicEntityConfigurationCollectionRequestTransfer $dynamicEntityConfigurationCollectionRequestTransfer): void
    {
        $dynamicEntityConfigurationCollectionRequestTransfer->requireDynamicEntityConfigurations();

        foreach ($dynamicEntityConfigurationCollectionRequestTransfer->getDynamicEntityConfigurations() as $dynamicEntityConfigurationTransfer) {
            $dynamicEntityConfigurationTransfer->requireIdDynamicEntityConfiguration();
            $dynamicEntityConfigurationTransfer->requireDynamicEntityDefinition();
            $dynamicEntityConfigurationTransfer->requireTableName();
            $dynamicEntityConfigurationTransfer->requireTableAlias();
            $dynamicEntityConfigurationTransfer->requireIsActive();

            $this->assertRequiredFieldsDynamicEntityDefinitions($dynamicEntityConfigurationTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer
     *
     * @return void
     */
    protected function assertRequiredFieldsDynamicEntityDefinitions(DynamicEntityConfigurationTransfer $dynamicEntityConfigurationTransfer): void
    {
        $dynamicEntityConfigurationTransfer->requireDynamicEntityDefinition();

        $dynamicEntityDefinitionTransfer = $dynamicEntityConfigurationTransfer->getDynamicEntityDefinitionOrFail();
        $dynamicEntityDefinitionTransfer->requireIdentifier();
    }
}
