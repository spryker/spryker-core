<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\RestRequestValidator\Business;

interface RestRequestValidatorFacadeInterface
{
    /**
     * Specification:
     * - Collects the validation rules declarations from all the modules.
     * - Stores the rules in the cache.
     * - Removes outdated validation rules cache file.
     *
     * @api
     *
     * @return void
     */
    public function buildValidationCache(): void;
}
