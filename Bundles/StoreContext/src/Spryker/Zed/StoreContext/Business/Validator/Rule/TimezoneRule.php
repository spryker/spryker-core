<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StoreContext\Business\Validator\Rule;

use Generated\Shared\Transfer\ErrorTransfer;
use Generated\Shared\Transfer\StoreContextTransfer;
use Spryker\Zed\StoreContext\Business\Reader\TimezoneReaderInterface;

class TimezoneRule implements StoreContextValidatorRuleInterface
{
    /**
     * @var string
     */
    protected const TIMEZONE = '%timezone%';

    /**
     * @var string
     */
    protected const ERROR_MESSAGE = 'Timezone %timezone% is not valid.';

    /**
     * @var \Spryker\Zed\StoreContext\Business\Reader\TimezoneReaderInterface
     */
    protected TimezoneReaderInterface $timezoneReader;

    /**
     * @param \Spryker\Zed\StoreContext\Business\Reader\TimezoneReaderInterface $timezoneReader
     */
    public function __construct(TimezoneReaderInterface $timezoneReader)
    {
        $this->timezoneReader = $timezoneReader;
    }

    /**
     * @param \Generated\Shared\Transfer\StoreContextTransfer $storeContextTransfer
     *
     * @return array<\Generated\Shared\Transfer\ErrorTransfer>
     */
    public function validateStoreContext(StoreContextTransfer $storeContextTransfer): array
    {
        $errorTransfers = [];
        /**
         * @var \Generated\Shared\Transfer\StoreApplicationContextTransfer $storeApplicationContextTransfer
         */
        foreach ($storeContextTransfer->getApplicationContextCollectionOrFail()->getApplicationContexts() as $storeApplicationContextTransfer) {
            $storeApplicationContextTimezone = $storeApplicationContextTransfer->getTimezone();

            if (!in_array($storeApplicationContextTimezone, $this->timezoneReader->getAvailableTimezones())) {
                $errorTransfers[] = (new ErrorTransfer())
                    ->setMessage(static::ERROR_MESSAGE)
                    ->setEntityIdentifier($storeApplicationContextTimezone)
                    ->setParameters([
                        static::TIMEZONE => $storeApplicationContextTimezone,
                    ]);
            }
        }

        return $errorTransfers;
    }
}
