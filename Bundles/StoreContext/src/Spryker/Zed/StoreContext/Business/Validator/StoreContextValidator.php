<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StoreContext\Business\Validator;

use Generated\Shared\Transfer\ErrorTransfer;
use Generated\Shared\Transfer\StoreContextCollectionRequestTransfer;
use Generated\Shared\Transfer\StoreContextCollectionResponseTransfer;
use Generated\Shared\Transfer\StoreContextTransfer;

class StoreContextValidator implements StoreContextValidatorInterface
{
    /**
     * @var string
     */
    protected const ERROR_MESSAGE_STORE_CONTEXT_COLLECTION_MISSING = 'Store context collection is missing.';

    /**
     * @var string
     */
    protected const ERROR_MESSAGE_STORE_MISSING = 'Store is missing in store context.';

    /**
     * @var array<\Spryker\Zed\StoreContext\Business\Validator\Rule\StoreContextValidatorRuleInterface>
     */
    protected array $validatorRules = [];

    /**
     * @param array<\Spryker\Zed\StoreContext\Business\Validator\Rule\StoreContextValidatorRuleInterface> $validatorRules
     */
    public function __construct(array $validatorRules)
    {
        $this->validatorRules = $validatorRules;
    }

    /**
     * @param \Generated\Shared\Transfer\StoreContextCollectionRequestTransfer $storeContextCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\StoreContextCollectionResponseTransfer
     */
    public function validateStoreContextCollection(
        StoreContextCollectionRequestTransfer $storeContextCollectionRequestTransfer
    ): StoreContextCollectionResponseTransfer {
        $storeContextCollectionResponseTransfer = new StoreContextCollectionResponseTransfer();

        foreach ($storeContextCollectionRequestTransfer->getContexts() as $storeContextTransfer) {
            $errorTransfers = $this->validateStoreContext($storeContextTransfer);

            if (count($errorTransfers) === 0) {
                $storeContextCollectionResponseTransfer->addContext($storeContextTransfer);

                continue;
            }

            $storeContextCollectionResponseTransfer = $this->addErrorsToResponse($storeContextCollectionResponseTransfer, $errorTransfers);
        }

        return $storeContextCollectionResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\StoreContextCollectionResponseTransfer $storeContextCollectionResponseTransfer
     * @param array<\Generated\Shared\Transfer\ErrorTransfer> $errorTransfers
     *
     * @return \Generated\Shared\Transfer\StoreContextCollectionResponseTransfer
     */
    protected function addErrorsToResponse(
        StoreContextCollectionResponseTransfer $storeContextCollectionResponseTransfer,
        array $errorTransfers
    ): StoreContextCollectionResponseTransfer {
        /** @var \Generated\Shared\Transfer\ErrorTransfer $errorTransfer */
        foreach ($errorTransfers as $errorTransfer) {
            $storeContextCollectionResponseTransfer->addError($errorTransfer);
        }

        return $storeContextCollectionResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\StoreContextTransfer $storeContextTransfer
     *
     * @return array<\Generated\Shared\Transfer\ErrorTransfer>
     */
    protected function validateStoreContext(StoreContextTransfer $storeContextTransfer): array
    {
        $errorTransfers = [];

        if ($storeContextTransfer->getStore() === null) {
            $errorTransfers[] = (new ErrorTransfer())->setMessage(static::ERROR_MESSAGE_STORE_MISSING);
        }

        if ($storeContextTransfer->getApplicationContextCollection() === null) {
            $errorTransfers[] =
                (new ErrorTransfer())
                    ->setMessage(static::ERROR_MESSAGE_STORE_CONTEXT_COLLECTION_MISSING)
                    ->setEntityIdentifier($storeContextTransfer->getStoreOrFail()->getName());
        }

        if (count($errorTransfers) > 0) {
            return $errorTransfers;
        }

        foreach ($this->validatorRules as $validatorRule) {
            $errorTransfers = array_merge($errorTransfers, $validatorRule->validateStoreContext($storeContextTransfer));
        }

        return $errorTransfers;
    }
}
