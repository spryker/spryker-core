<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Store\Business\Validator;

use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\StoreResponseTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\Store\Persistence\StoreRepositoryInterface;

class StoreValidator implements StoreValidatorInterface
{
    /**
     * @var string
     */
    protected const ERROR_MESSAGE_NAME_IS_NOT_UNIQUE = 'A store with the same name already exists.';

    /**
     * @var \Spryker\Zed\Store\Persistence\StoreRepositoryInterface
     */
    protected StoreRepositoryInterface $storeRepository;

    /**
     * @var array<\Spryker\Zed\StoreExtension\Dependency\Plugin\StorePreCreateValidationPluginInterface>
     */
    protected array $storePreCreateValidationPlugins;

    /**
     * @var array<\Spryker\Zed\StoreExtension\Dependency\Plugin\StorePreUpdateValidationPluginInterface>
     */
    protected array $storePreUpdateValidationPlugins;

    /**
     * @param \Spryker\Zed\Store\Persistence\StoreRepositoryInterface $storeRepository
     * @param array<\Spryker\Zed\StoreExtension\Dependency\Plugin\StorePreCreateValidationPluginInterface> $storePreCreateValidationPlugins
     * @param array<\Spryker\Zed\StoreExtension\Dependency\Plugin\StorePreUpdateValidationPluginInterface> $storePreUpdateValidationPlugins
     */
    public function __construct(
        StoreRepositoryInterface $storeRepository,
        array $storePreCreateValidationPlugins,
        array $storePreUpdateValidationPlugins
    ) {
        $this->storeRepository = $storeRepository;
        $this->storePreCreateValidationPlugins = $storePreCreateValidationPlugins;
        $this->storePreUpdateValidationPlugins = $storePreUpdateValidationPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\StoreResponseTransfer
     */
    public function validatePreCreate(StoreTransfer $storeTransfer): StoreResponseTransfer
    {
        $storeResponseTransfer = (new StoreResponseTransfer())
            ->setStore($storeTransfer)
            ->setIsSuccessful(true);

        foreach ($this->storePreCreateValidationPlugins as $storePreCreateValidationPlugin) {
            $storeResponseTransfer = $this->mergeStoreResponseTransfers(
                $storeResponseTransfer,
                $storePreCreateValidationPlugin->validate($storeTransfer),
            );
        }

        return $storeResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\StoreResponseTransfer
     */
    public function validatePreUpdate(StoreTransfer $storeTransfer): StoreResponseTransfer
    {
        $storeResponseTransfer = (new StoreResponseTransfer())
            ->setStore($storeTransfer)
            ->setIsSuccessful(true);

        foreach ($this->storePreUpdateValidationPlugins as $storePreUpdateValidationPlugin) {
            $storeResponseTransfer = $this->mergeStoreResponseTransfers(
                $storeResponseTransfer,
                $storePreUpdateValidationPlugin->validate($storeTransfer),
            );
        }

        return $storeResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\StoreResponseTransfer
     */
    public function validateStoreNameIsUnique(StoreTransfer $storeTransfer): StoreResponseTransfer
    {
        $storeResponseTransfer = (new StoreResponseTransfer())->setIsSuccessful(true);

        $existingStoreTransfer = $this->storeRepository->findStoreByName($storeTransfer->getNameOrFail());

        if ($this->isStoreNameAllowed($storeTransfer, $existingStoreTransfer)) {
            $message = (new MessageTransfer())->setValue(static::ERROR_MESSAGE_NAME_IS_NOT_UNIQUE);

            return $storeResponseTransfer->setIsSuccessful(false)->addMessage($message);
        }

        return $storeResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer|null $existingStoreTransfer
     *
     * @return bool
     */
    protected function isStoreNameAllowed(StoreTransfer $storeTransfer, ?StoreTransfer $existingStoreTransfer): bool
    {
        return $existingStoreTransfer
            && ($storeTransfer->getIdStore() === null
                || (int)$storeTransfer->getIdStoreOrFail() !== $existingStoreTransfer->getIdStoreOrFail()
            );
    }

    /**
     * @param \Generated\Shared\Transfer\StoreResponseTransfer $sourceStoreResponseTransfer
     * @param \Generated\Shared\Transfer\StoreResponseTransfer $storeResponseTransfer
     *
     * @return \Generated\Shared\Transfer\StoreResponseTransfer
     */
    protected function mergeStoreResponseTransfers(
        StoreResponseTransfer $sourceStoreResponseTransfer,
        StoreResponseTransfer $storeResponseTransfer
    ): StoreResponseTransfer {
        foreach ($storeResponseTransfer->getMessages() as $messageTransfer) {
            $sourceStoreResponseTransfer->addMessage($messageTransfer);
        }

        return $sourceStoreResponseTransfer
            ->setIsSuccessful($sourceStoreResponseTransfer->getIsSuccessful() && ($storeResponseTransfer->getIsSuccessful() ?? true));
    }
}
