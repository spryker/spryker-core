<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\WarehouseUser\Business\Validator\Rules;

use ArrayObject;
use Generated\Shared\Transfer\ErrorTransfer;
use Generated\Shared\Transfer\UserCollectionTransfer;
use Generated\Shared\Transfer\UserConditionsTransfer;
use Generated\Shared\Transfer\UserCriteriaTransfer;
use Generated\Shared\Transfer\WarehouseUserAssignmentCollectionResponseTransfer;
use Spryker\Zed\WarehouseUser\Business\IdentifierBuilder\WarehouseUserAssignmentIdentifierBuilderInterface;
use Spryker\Zed\WarehouseUser\Dependency\Facade\WarehouseUserToUserFacadeInterface;

class UserExistsValidatorRule implements WarehouseUserAssignmentValidatorRuleInterface
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_USER_NOT_FOUND = 'warehouse_user_assignment.validation.user_not_found';

    /**
     * @var \Spryker\Zed\WarehouseUser\Dependency\Facade\WarehouseUserToUserFacadeInterface
     */
    protected WarehouseUserToUserFacadeInterface $userFacade;

    /**
     * @var \Spryker\Zed\WarehouseUser\Business\IdentifierBuilder\WarehouseUserAssignmentIdentifierBuilderInterface
     */
    protected WarehouseUserAssignmentIdentifierBuilderInterface $warehouseUserAssignmentIdentifierBuilder;

    /**
     * @param \Spryker\Zed\WarehouseUser\Dependency\Facade\WarehouseUserToUserFacadeInterface $userFacade
     * @param \Spryker\Zed\WarehouseUser\Business\IdentifierBuilder\WarehouseUserAssignmentIdentifierBuilderInterface $warehouseUserAssignmentIdentifierBuilder
     */
    public function __construct(
        WarehouseUserToUserFacadeInterface $userFacade,
        WarehouseUserAssignmentIdentifierBuilderInterface $warehouseUserAssignmentIdentifierBuilder
    ) {
        $this->userFacade = $userFacade;
        $this->warehouseUserAssignmentIdentifierBuilder = $warehouseUserAssignmentIdentifierBuilder;
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\WarehouseUserAssignmentTransfer> $warehouseUserAssignmentTransfers
     * @param \Generated\Shared\Transfer\WarehouseUserAssignmentCollectionResponseTransfer $warehouseUserAssignmentCollectionResponseTransfer
     *
     * @return \Generated\Shared\Transfer\WarehouseUserAssignmentCollectionResponseTransfer
     */
    public function validateCollection(
        ArrayObject $warehouseUserAssignmentTransfers,
        WarehouseUserAssignmentCollectionResponseTransfer $warehouseUserAssignmentCollectionResponseTransfer
    ): WarehouseUserAssignmentCollectionResponseTransfer {
        $userCriteriaTransfer = $this->createUserCriteriaTransfer(
            $warehouseUserAssignmentTransfers,
        );
        $userCollectionTransfer = $this->userFacade->getUserCollection($userCriteriaTransfer);

        return $this->validateProvidedUsersExist(
            $warehouseUserAssignmentTransfers,
            $userCollectionTransfer,
            $warehouseUserAssignmentCollectionResponseTransfer,
        );
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\WarehouseUserAssignmentTransfer> $warehouseUserAssignmentTransfers
     *
     * @return \Generated\Shared\Transfer\UserCriteriaTransfer
     */
    protected function createUserCriteriaTransfer(ArrayObject $warehouseUserAssignmentTransfers): UserCriteriaTransfer
    {
        $userConditionsTransfer = new UserConditionsTransfer();
        foreach ($warehouseUserAssignmentTransfers as $warehouseUserAssignmentTransfer) {
            if ($warehouseUserAssignmentTransfer->getUserUuid()) {
                $userConditionsTransfer->addUuid($warehouseUserAssignmentTransfer->getUserUuidOrFail());
            }
        }

        return (new UserCriteriaTransfer())->setUserConditions($userConditionsTransfer);
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\WarehouseUserAssignmentTransfer> $warehouseUserAssignmentTransfers
     * @param \Generated\Shared\Transfer\UserCollectionTransfer $userCollectionTransfer
     * @param \Generated\Shared\Transfer\WarehouseUserAssignmentCollectionResponseTransfer $warehouseUserAssignmentCollectionResponseTransfer
     *
     * @return \Generated\Shared\Transfer\WarehouseUserAssignmentCollectionResponseTransfer
     */
    protected function validateProvidedUsersExist(
        ArrayObject $warehouseUserAssignmentTransfers,
        UserCollectionTransfer $userCollectionTransfer,
        WarehouseUserAssignmentCollectionResponseTransfer $warehouseUserAssignmentCollectionResponseTransfer
    ): WarehouseUserAssignmentCollectionResponseTransfer {
        $indexedUserTransfers = $this->getUserTransfersIndexedByUuid($userCollectionTransfer);
        /** @var \Generated\Shared\Transfer\WarehouseUserAssignmentTransfer $warehouseUserAssignmentTransfer */
        foreach ($warehouseUserAssignmentTransfers as $warehouseUserAssignmentTransfer) {
            if ($warehouseUserAssignmentTransfer->getUserUuid() && !isset($indexedUserTransfers[$warehouseUserAssignmentTransfer->getUserUuidOrFail()])) {
                $warehouseUserAssignmentCollectionResponseTransfer->addError(
                    $this->createErrorTransfer(
                        static::GLOSSARY_KEY_VALIDATION_USER_NOT_FOUND,
                        $this->warehouseUserAssignmentIdentifierBuilder->buildIdentifier($warehouseUserAssignmentTransfer),
                    ),
                );
            }
        }

        return $warehouseUserAssignmentCollectionResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\UserCollectionTransfer $userCollectionTransfer
     *
     * @return array<string, \Generated\Shared\Transfer\UserTransfer>
     */
    protected function getUserTransfersIndexedByUuid(UserCollectionTransfer $userCollectionTransfer): array
    {
        $indexedUserTransfers = [];
        foreach ($userCollectionTransfer->getUsers() as $userTransfer) {
            $indexedUserTransfers[$userTransfer->getUuidOrFail()] = $userTransfer;
        }

        return $indexedUserTransfers;
    }

    /**
     * @param string $message
     * @param string $identifier
     *
     * @return \Generated\Shared\Transfer\ErrorTransfer
     */
    protected function createErrorTransfer(string $message, string $identifier): ErrorTransfer
    {
        return (new ErrorTransfer())
            ->setMessage($message)
            ->setEntityIdentifier($identifier);
    }
}
