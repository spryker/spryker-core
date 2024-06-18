<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StoreContext\Business\Writer;

use Generated\Shared\Transfer\ErrorTransfer;
use Generated\Shared\Transfer\StoreContextCollectionRequestTransfer;
use Generated\Shared\Transfer\StoreContextCollectionResponseTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\StoreContext\Persistence\StoreContextEntityManagerInterface;

class StoreContextWriter implements StoreContextWriterInterface
{
    use TransactionTrait;

    /**
     * @var string
     */
    protected const ERROR_MESSAGE_FAILED_TO_UPDATE_STORE_CONTEXT = 'Failed to update store context for store %store%.';

    /**
     * @var string
     */
    protected const ERROR_MESSAGE_STORE_PLACEHOLDER = '%store%';

    /**
     * @var \Spryker\Zed\StoreContext\Persistence\StoreContextEntityManagerInterface
     */
    protected StoreContextEntityManagerInterface $storeContextEntityManager;

    /**
     * @param \Spryker\Zed\StoreContext\Persistence\StoreContextEntityManagerInterface $storeContextEntityManager
     */
    public function __construct(StoreContextEntityManagerInterface $storeContextEntityManager)
    {
        $this->storeContextEntityManager = $storeContextEntityManager;
    }

    /**
     * @param \Generated\Shared\Transfer\StoreContextCollectionRequestTransfer $storeContextCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\StoreContextCollectionResponseTransfer
     */
    public function createStoreContextCollection(
        StoreContextCollectionRequestTransfer $storeContextCollectionRequestTransfer
    ): StoreContextCollectionResponseTransfer {
        return $this->getTransactionHandler()->handleTransaction(function () use ($storeContextCollectionRequestTransfer) {
            return $this->executeCreateStoreContextCollectionTransaction($storeContextCollectionRequestTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\StoreContextCollectionRequestTransfer $storeContextCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\StoreContextCollectionResponseTransfer
     */
    protected function executeCreateStoreContextCollectionTransaction(
        StoreContextCollectionRequestTransfer $storeContextCollectionRequestTransfer
    ): StoreContextCollectionResponseTransfer {
        $storeContextCollectionResponseTransfer = new StoreContextCollectionResponseTransfer();

        /**
         * @var \Generated\Shared\Transfer\StoreContextTransfer $storeContextTransfer
         */
        foreach ($storeContextCollectionRequestTransfer->getContexts() as $storeContextTransfer) {
            $storeContextTransfer = $this->storeContextEntityManager->createStoreContext($storeContextTransfer);
            $storeContextCollectionResponseTransfer->addContext($storeContextTransfer);
        }

        return $storeContextCollectionResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\StoreContextCollectionRequestTransfer $storeContextCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\StoreContextCollectionResponseTransfer
     */
    public function updateStoreContextCollection(
        StoreContextCollectionRequestTransfer $storeContextCollectionRequestTransfer
    ): StoreContextCollectionResponseTransfer {
        return $this->getTransactionHandler()->handleTransaction(function () use ($storeContextCollectionRequestTransfer) {
            return $this->executeUpdateStoreContextCollectionTransaction($storeContextCollectionRequestTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\StoreContextCollectionRequestTransfer $storeContextCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\StoreContextCollectionResponseTransfer
     */
    protected function executeUpdateStoreContextCollectionTransaction(
        StoreContextCollectionRequestTransfer $storeContextCollectionRequestTransfer
    ): StoreContextCollectionResponseTransfer {
        $storeContextCollectionResponseTransfer = new StoreContextCollectionResponseTransfer();

        foreach ($storeContextCollectionRequestTransfer->getContexts() as $storeContextTransfer) {
            $storeContextTransfer = $this->storeContextEntityManager->updateStoreContext($storeContextTransfer);
            if ($storeContextTransfer->getApplicationContextCollection() !== null) {
                $storeContextCollectionResponseTransfer->addContext($storeContextTransfer);

                continue;
            }

            $storeContextCollectionResponseTransfer->addError(
                (new ErrorTransfer())
                    ->setMessage(static::ERROR_MESSAGE_FAILED_TO_UPDATE_STORE_CONTEXT)
                    ->setEntityIdentifier((string)$storeContextTransfer->getStoreOrFail()->getIdStore())
                    ->setParameters(
                        [
                           static::ERROR_MESSAGE_STORE_PLACEHOLDER => $storeContextTransfer->getStoreOrFail()->getNameOrFail(),
                        ],
                    ),
            );
        }

        return $storeContextCollectionResponseTransfer;
    }
}
