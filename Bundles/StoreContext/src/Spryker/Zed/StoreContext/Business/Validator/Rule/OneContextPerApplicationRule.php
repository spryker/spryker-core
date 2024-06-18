<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StoreContext\Business\Validator\Rule;

use Generated\Shared\Transfer\ErrorTransfer;
use Generated\Shared\Transfer\StoreApplicationContextCollectionTransfer;
use Generated\Shared\Transfer\StoreContextTransfer;
use Spryker\Zed\StoreContext\StoreContextConfig;

class OneContextPerApplicationRule implements StoreContextValidatorRuleInterface
{
    /**
     * @var string
     */
    protected const APPLICATION = '%application%';

    /**
     * @var string
     */
    protected const ERROR_MESSAGE = 'Application %application% is used more than once.';

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
        foreach ($this->countStoreContextsPerApplications($storeContextTransfer->getApplicationContextCollectionOrFail()) as $storeContextApplication => $count) {
            if ($count > 1) {
                $errorTransfers[] = $this->createErrorTransfer($storeContextApplication);
            }
        }

        return $errorTransfers;
    }

    /**
     * @param string $storeContextApplication
     *
     * @return \Generated\Shared\Transfer\ErrorTransfer
     */
    protected function createErrorTransfer(string $storeContextApplication): ErrorTransfer
    {
        return (new ErrorTransfer())
            ->setEntityIdentifier($storeContextApplication)
            ->setMessage(static::ERROR_MESSAGE)
            ->setParameters([
                static::APPLICATION => $storeContextApplication,
            ]);
    }

    /**
     * @param \Generated\Shared\Transfer\StoreApplicationContextCollectionTransfer $storeApplicationContextCollectionTransfer
     *
     * @return array<string, int>
     */
    protected function countStoreContextsPerApplications(StoreApplicationContextCollectionTransfer $storeApplicationContextCollectionTransfer): array
    {
        $storeContextApplications = $this->storeContextConfig->getStoreContextApplications();

        $storeContextApplicationsCount = [];

        /**
         * @var \Generated\Shared\Transfer\StoreApplicationContextTransfer $storeApplicationContextTransfer
         */
        foreach ($storeApplicationContextCollectionTransfer->getApplicationContexts() as $storeApplicationContextTransfer) {
            $storeContextApplication = $storeApplicationContextTransfer->getApplication();

            if (!isset($storeContextApplicationsCount[$storeContextApplication])) {
                $storeContextApplicationsCount[$storeContextApplication] = 0;
            }

            $storeContextApplicationsCount[$storeContextApplication]++;
        }

        return $storeContextApplicationsCount;
    }
}
