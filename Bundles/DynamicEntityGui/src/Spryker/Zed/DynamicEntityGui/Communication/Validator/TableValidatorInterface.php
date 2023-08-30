<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DynamicEntityGui\Communication\Validator;

interface TableValidatorInterface
{
    /**
     * @param string $table
     *
     * @return bool
     */
    public function validateIsTableDisallowed(string $table): bool;

    /**
     * @param string $table
     *
     * @return bool
     */
    public function validateIsTableConfigured(string $table): bool;

    /**
     * @param string $table
     *
     * @return bool
     */
    public function validateIsTableExist(string $table): bool;
}
