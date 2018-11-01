<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Synchronization\Business\Validation;

interface OutdatedValidatorInterface
{
    /**
     * @param string $queueName
     * @param array $newEntry
     * @param array $existingEntry
     *
     * @return bool
     */
    public function isInvalid($queueName, array $newEntry, array $existingEntry);
}
