<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DynamicEntity\Business\Creator;

use ArrayObject;
use Generated\Shared\Transfer\DynamicEntityConfigurationCollectionRequestTransfer;
use Generated\Shared\Transfer\DynamicEntityConfigurationCollectionResponseTransfer;
use Generated\Shared\Transfer\DynamicEntityConfigurationCollectionTransfer;
use Spryker\Zed\DynamicEntity\Business\Creator\DynamicEntityConfiguration\DynamicEntityConfigurationColumnDetailProviderInterface;
use Spryker\Zed\DynamicEntity\Business\Validator\DynamicEntityConfigurationValidatorInterface;
use Spryker\Zed\DynamicEntity\Persistence\DynamicEntityEntityManagerInterface;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;

class DynamicEntityConfigurationCreator implements DynamicEntityConfigurationCreatorInterface
{
    use TransactionTrait;

    /**
     * @param \Spryker\Zed\DynamicEntity\Business\Validator\DynamicEntityConfigurationValidatorInterface $dynamicEntityConfigurationValidator
     * @param \Spryker\Zed\DynamicEntity\Persistence\DynamicEntityEntityManagerInterface $dynamicEntityManager
     * @param \Spryker\Zed\DynamicEntity\Business\Creator\DynamicEntityConfiguration\DynamicEntityConfigurationColumnDetailProviderInterface $dynamicEntityConfigurationColumnDetailProvider
     */
    public function __construct(
        protected DynamicEntityConfigurationValidatorInterface $dynamicEntityConfigurationValidator,
        protected DynamicEntityEntityManagerInterface $dynamicEntityManager,
        protected DynamicEntityConfigurationColumnDetailProviderInterface $dynamicEntityConfigurationColumnDetailProvider
    ) {
    }

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityConfigurationCollectionRequestTransfer $dynamicEntityConfigurationCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\DynamicEntityConfigurationCollectionResponseTransfer
     */
    public function createDynamicEntityConfigurationCollection(
        DynamicEntityConfigurationCollectionRequestTransfer $dynamicEntityConfigurationCollectionRequestTransfer
    ): DynamicEntityConfigurationCollectionResponseTransfer {
        $this->assertRequiredFields($dynamicEntityConfigurationCollectionRequestTransfer);

        $dynamicEntityConfigurationCollectionTransfer = new DynamicEntityConfigurationCollectionTransfer();
        $dynamicEntityConfigurationCollectionTransfer->setDynamicEntityConfigurations(
            $dynamicEntityConfigurationCollectionRequestTransfer->getDynamicEntityConfigurations(),
        );

        // Add column details to the DynamicEntityConfigurations e.g. description and examples
        $dynamicEntityConfigurationCollectionTransfer = $this->dynamicEntityConfigurationColumnDetailProvider->provideColumDetails(
            $dynamicEntityConfigurationCollectionTransfer,
        );

        $dynamicEntityConfigurationCollectionResponseTransfer = new DynamicEntityConfigurationCollectionResponseTransfer();
        $dynamicEntityConfigurationCollectionResponseTransfer->setDynamicEntityConfigurations(
            $dynamicEntityConfigurationCollectionTransfer->getDynamicEntityConfigurations(),
        );

        $dynamicEntityConfigurationCollectionResponseTransfer = $this->dynamicEntityConfigurationValidator->validate(
            $dynamicEntityConfigurationCollectionResponseTransfer,
        );

        if ($dynamicEntityConfigurationCollectionResponseTransfer->getErrors()->count()) {
            return $dynamicEntityConfigurationCollectionResponseTransfer;
        }

        $createdDynamicEntityConfigurationTransfers = $this->getTransactionHandler()->handleTransaction(
            function () use ($dynamicEntityConfigurationCollectionResponseTransfer) {
                return $this->executeCreateDynamicEntityConfigurationCollectionTransaction(
                    $dynamicEntityConfigurationCollectionResponseTransfer->getDynamicEntityConfigurations(),
                );
            },
        );

        return $dynamicEntityConfigurationCollectionResponseTransfer->setDynamicEntityConfigurations($createdDynamicEntityConfigurationTransfers);
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer> $dynamicEntityConfigurationTransfers
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\DynamicEntityConfigurationTransfer>
     */
    protected function executeCreateDynamicEntityConfigurationCollectionTransaction(
        ArrayObject $dynamicEntityConfigurationTransfers
    ): ArrayObject {
        $persistedDynamicEntityConfigurationTransfers = new ArrayObject();

        foreach ($dynamicEntityConfigurationTransfers as $entityIdentifier => $dynamicEntityConfigurationTransfer) {
            $dynamicEntityConfigurationTransfer = $this->dynamicEntityManager->createDynamicEntityConfiguration($dynamicEntityConfigurationTransfer);
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
            $dynamicEntityConfigurationTransfer->requireDynamicEntityDefinition();
            $dynamicEntityConfigurationTransfer->requireTableName();
            $dynamicEntityConfigurationTransfer->requireTableAlias();
            $dynamicEntityConfigurationTransfer->requireIsActive();
            $dynamicEntityConfigurationTransfer->requireDynamicEntityDefinition();
        }
    }
}
