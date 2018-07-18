<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Dataset;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
interface DatasetConstants
{
    /**
     * Specification:
     * - Maximum allowed size for uploaded files
     *
     * @api
     */
    public const DATASET_FILE_SIZE = 'DATASET:FILE_SIZE';
}
