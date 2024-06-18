<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StoreContext\Business\Validator\Rule;

use Generated\Shared\Transfer\ErrorTransfer;
use Generated\Shared\Transfer\StoreContextTransfer;
use Spryker\Zed\StoreContext\Business\Reader\StoreContextReaderInterface;

class ContextAlreadyExistRule implements StoreContextValidatorRuleInterface
{
    /**
     * @var string
     */
    protected const ERROR_MESSAGE_STORE_CONTEXT_EXISTS = 'Store context already exist for id: %id%.';

    /**
     * @var string
     */
    protected const PLACEHOLDER_ID = '%id%';

    /**
     * @var \Spryker\Zed\StoreContext\Business\Reader\StoreContextReaderInterface
     */
    protected StoreContextReaderInterface $storeContextReader;

    /**
     * @param \Spryker\Zed\StoreContext\Business\Reader\StoreContextReaderInterface $storeContextReader
     */
    public function __construct(StoreContextReaderInterface $storeContextReader)
    {
        $this->storeContextReader = $storeContextReader;
    }

    /**
     * @param \Generated\Shared\Transfer\StoreContextTransfer $storeContextTransfer
     *
     * @return array<\Generated\Shared\Transfer\ErrorTransfer>
     */
    public function validateStoreContext(StoreContextTransfer $storeContextTransfer): array
    {
        $storeId = $storeContextTransfer->getStoreOrFail()->getIdStoreOrFail();
        $storeContextCollectionTransfer = $this->storeContextReader->getStoreApplicationContextCollectionByIdStore($storeId);

        if ($storeContextCollectionTransfer->getApplicationContexts()->count() > 0) {
            return [
                (new ErrorTransfer())
                    ->setMessage(static::ERROR_MESSAGE_STORE_CONTEXT_EXISTS)
                    ->setParameters([
                        static::PLACEHOLDER_ID => $storeId,
                    ]),
            ];
        }

        return [];
    }
}
