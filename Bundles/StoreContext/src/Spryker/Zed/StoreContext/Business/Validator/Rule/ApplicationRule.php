<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StoreContext\Business\Validator\Rule;

use Generated\Shared\Transfer\ErrorTransfer;
use Generated\Shared\Transfer\StoreContextTransfer;
use Spryker\Zed\StoreContext\StoreContextConfig;

class ApplicationRule implements StoreContextValidatorRuleInterface
{
    /**
     * @var string
     */
    protected const APPLICATION = '%application%';

    /**
     * @var string
     */
    protected const ERROR_MESSAGE = 'Application %application% is not valid.';

    /**
     * @var \Spryker\Zed\StoreContext\StoreContextConfig
     */
    protected StoreContextConfig $storeContextConfig;

    /**
     * @param \Spryker\Zed\StoreContext\StoreContextConfig $storeContextConfig
     */
    public function __construct(StoreContextConfig $storeContextConfig)
    {
        $this->storeContextConfig = $storeContextConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\StoreContextTransfer $storeContextTransfer
     *
     * @return array<\Generated\Shared\Transfer\ErrorTransfer>
     */
    public function validateStoreContext(StoreContextTransfer $storeContextTransfer): array
    {
        $errorTransfers = [];
        $storeContextApplications = $this->storeContextConfig->getStoreContextApplications();
        $storeApplicationContextTransfers = $storeContextTransfer->getApplicationContextCollectionOrFail()->getApplicationContexts();

        foreach ($storeApplicationContextTransfers as $storeApplicationContextTransfer) {
            $storeContextApplication = $storeApplicationContextTransfer->getApplication();

            if ($storeContextApplication === null) {
                continue;
            }

            if (!in_array($storeContextApplication, $storeContextApplications)) {
                $errorTransfers[] = (new ErrorTransfer())
                    ->setMessage(static::ERROR_MESSAGE)
                    ->setEntityIdentifier($storeContextApplication)
                    ->setParameters([
                        static::APPLICATION => $storeContextApplication,
                    ]);
            }
        }

        return $errorTransfers;
    }
}
