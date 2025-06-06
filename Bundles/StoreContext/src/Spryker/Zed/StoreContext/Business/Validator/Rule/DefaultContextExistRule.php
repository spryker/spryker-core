<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StoreContext\Business\Validator\Rule;

use Generated\Shared\Transfer\ErrorTransfer;
use Generated\Shared\Transfer\StoreContextTransfer;

class DefaultContextExistRule implements StoreContextValidatorRuleInterface
{
    /**
     * @var string
     */
    protected const APPLICATION = 'application';

    /**
     * @var string
     */
    protected const ERROR_MESSAGE = 'The default context is always required. When you want to add an Application-specific configuration, please click `Add store settings` and then change the APPLICATION value to the desired value.';

    /**
     * @param \Generated\Shared\Transfer\StoreContextTransfer $storeContextTransfer
     *
     * @return array<\Generated\Shared\Transfer\ErrorTransfer>
     */
    public function validateStoreContext(StoreContextTransfer $storeContextTransfer): array
    {
        $errorTransfers = [];
        $defaultContextExists = false;

        /**
         * @var \Generated\Shared\Transfer\StoreApplicationContextTransfer $storeApplicationContextTransfer
         */
        foreach ($storeContextTransfer->getApplicationContextCollectionOrFail()->getApplicationContexts() as $storeApplicationContextTransfer) {
            $storeContextApplication = $storeApplicationContextTransfer->getApplication();

            if ($storeContextApplication === null) {
                $defaultContextExists = true;

                break;
            }
        }

        if ($defaultContextExists === false) {
            $errorTransfers[] = (new ErrorTransfer())
                ->setMessage(static::ERROR_MESSAGE)
                ->setEntityIdentifier(static::APPLICATION);
        }

        return $errorTransfers;
    }
}
