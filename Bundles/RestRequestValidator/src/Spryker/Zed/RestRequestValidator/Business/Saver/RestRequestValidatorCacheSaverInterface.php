<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\RestRequestValidator\Business\Saver;

interface RestRequestValidatorCacheSaverInterface
{
    /**
     * @param array $validatorConfig
     * @param string $storeName
     *
     * @return void
     */
    public function save(array $validatorConfig, string $storeName): void;
}
