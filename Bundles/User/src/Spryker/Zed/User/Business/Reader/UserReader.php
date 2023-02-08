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
     * @var array<\Spryker\Zed\UserExtension\Dependency\Plugin\UserExpanderPluginInterface>
     */
    protected array $userExpanderPlugins;

    /**
     * @param \Spryker\Zed\User\Persistence\UserRepositoryInterface $userRepository
     * @param array<\Spryker\Zed\UserExtension\Dependency\Plugin\UserExpanderPluginInterface> $userExpanderPlugins
     */
    public function __construct(UserRepositoryInterface $userRepository, array $userExpanderPlugins)
    {
        $this->userRepository = $userRepository;
        $this->userExpanderPlugins = $userExpanderPlugins;
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

        return $this->executeUserExpanderPlugins($userCollectionTransfer);
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
}
