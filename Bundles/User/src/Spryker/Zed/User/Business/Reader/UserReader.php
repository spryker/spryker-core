<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\User\Business\Reader;

use Generated\Shared\Transfer\UserCollectionTransfer;
use Generated\Shared\Transfer\UserCriteriaTransfer;
use Spryker\Zed\User\Business\Exception\UserNotFoundException;
use Spryker\Zed\User\Persistence\UserRepositoryInterface;

class UserReader implements UserReaderInterface
{
    /**
     * @var \Spryker\Zed\User\Persistence\UserRepositoryInterface
     */
    protected UserRepositoryInterface $userRepository;

    /**
     * @var list<\Spryker\Zed\UserExtension\Dependency\Plugin\UserExpanderPluginInterface>
     */
    protected array $userExpanderPlugins;

    /**
     * @var list<\Spryker\Zed\UserExtension\Dependency\Plugin\UserTransferExpanderPluginInterface>
     */
    protected array $userTransferExpanderPlugins;

    /**
     * @param \Spryker\Zed\User\Persistence\UserRepositoryInterface $userRepository
     * @param array<\Spryker\Zed\UserExtension\Dependency\Plugin\UserExpanderPluginInterface> $userExpanderPlugins
     * @param array<\Spryker\Zed\UserExtension\Dependency\Plugin\UserTransferExpanderPluginInterface> $userTransferExpanderPlugins
     */
    public function __construct(
        UserRepositoryInterface $userRepository,
        array $userExpanderPlugins,
        array $userTransferExpanderPlugins = []
    ) {
        $this->userRepository = $userRepository;
        $this->userExpanderPlugins = $userExpanderPlugins;
        $this->userTransferExpanderPlugins = $userTransferExpanderPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\UserCriteriaTransfer $userCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\UserCollectionTransfer
     */
    public function getUserCollection(UserCriteriaTransfer $userCriteriaTransfer): UserCollectionTransfer
    {
        $userCollectionTransfer = $this->userRepository->getUserCollection($userCriteriaTransfer);
        if ($userCollectionTransfer->getUsers()->count() === 0) {
            $this->throwUserNotFoundExceptionAccordingToUserConditions($userCriteriaTransfer);

            return $userCollectionTransfer;
        }

        $userCollectionTransfer = $this->executeUserExpanderPlugins($userCollectionTransfer);
        $userCollectionTransfer = $this->executeUserTransferExpanderPlugins($userCollectionTransfer);

        return $userCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\UserCollectionTransfer $userCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\UserCollectionTransfer
     */
    protected function executeUserExpanderPlugins(UserCollectionTransfer $userCollectionTransfer): UserCollectionTransfer
    {
        foreach ($this->userExpanderPlugins as $userExpanderPlugin) {
            $userCollectionTransfer = $userExpanderPlugin->expand($userCollectionTransfer);
        }

        return $userCollectionTransfer;
    }

    /**
     * @deprecated Exists for BC reasons only.
     *
     * @param \Generated\Shared\Transfer\UserCriteriaTransfer $userCriteriaTransfer
     *
     * @throws \Spryker\Zed\User\Business\Exception\UserNotFoundException
     *
     * @return void
     */
    protected function throwUserNotFoundExceptionAccordingToUserConditions(UserCriteriaTransfer $userCriteriaTransfer): void
    {
        if (
            $userCriteriaTransfer->getUserConditions()
            && $userCriteriaTransfer->getUserConditionsOrFail()->getThrowUserNotFoundException() === true
        ) {
            throw new UserNotFoundException();
        }
    }

    /**
     * @deprecated Exists for BC reasons only.
     *
     * @param \Generated\Shared\Transfer\UserCollectionTransfer $userCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\UserCollectionTransfer
     */
    protected function executeUserTransferExpanderPlugins(UserCollectionTransfer $userCollectionTransfer): UserCollectionTransfer
    {
        foreach ($this->userTransferExpanderPlugins as $userTransferExpanderPlugin) {
            foreach ($userCollectionTransfer->getUsers() as $key => $userTransfer) {
                $userCollectionTransfer->getUsers()->offsetSet($key, $userTransferExpanderPlugin->expandUserTransfer($userTransfer));
            }
        }

        return $userCollectionTransfer;
    }
}
