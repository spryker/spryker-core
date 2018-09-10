<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\RestRequestValidator\Business\Saver;

use Generated\Shared\Transfer\StoreTransfer;

interface RestRequestValidatorSaverInterface
{
    /**
     * @param array $validatorConfig
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return void
     */
    public function store(array $validatorConfig, StoreTransfer $storeTransfer): void;
}
