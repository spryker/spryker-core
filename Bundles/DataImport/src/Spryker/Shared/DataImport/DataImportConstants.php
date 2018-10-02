<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\DataImport;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
interface DataImportConstants
{
    /**
     * Specification:
     * - Root path to the import files.
     * - Can be used to have a small set of import data for e.g. testing or development.
     */
    public const IMPORT_FILE_ROOT_PATH = 'IMPORT_FILE_ROOT_PATH';
}
