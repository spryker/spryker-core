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
     * - collects the validation rules declarations from all the modules
     * - stores the rules in the cache.
     *
     * @api
     *
     * @return void
     */
    public function buildCache(): void;
}
