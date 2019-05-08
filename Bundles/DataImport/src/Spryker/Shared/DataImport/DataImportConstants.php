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

    /**
     * Specification:
     * - Defines data import queue reader chunk size.
     *
     * @api
     */
    public const QUEUE_READER_CHUNK_SIZE = 'DATA_IMPORT:QUEUE_READER_CHUNK_SIZE';

    /**
     * Specification:
     * - Defines the size of the chunk to be used for writing messages into the queue in bulk.
     *
     * @api
     */
    public const QUEUE_WRITER_CHUNK_SIZE = 'DATA_IMPORT:QUEUE_WRITER_CHUNK_SIZE';
}
