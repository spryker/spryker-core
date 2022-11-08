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
     * - Switches bulk mode of data import process.
     *
     * @api
     *
     * @var string
     */
    public const IS_BULK_MODE_ENABLED = 'DATA_IMPORT:IS_BULK_MODE_ENABLED';

    /**
     * Specification:
     * - Defines graduality factor for memory usage during bulk import.
     * - Graduality factor affects how many iteration we will need to fill in allowed memory.
     *
     * @api
     *
     * @var string
     */
    public const BULK_MODE_GRADUALITY_FACTOR = 'DATA_IMPORT:BULK_MODE_GRADUALITY_FACTOR';

    /**
     * Specification:
     * - Defines a percent from memory could be used for bulk import.
     *
     * @api
     *
     * @var string
     */
    public const BULK_MODE_MEMORY_TRESHOLD_PERCENT = 'DATA_IMPORT:BULK_MODE_MEMORY_TRESHOLD_PERCENT';

    /**
     * @deprecated Use {@link \Spryker\Shared\DataImport\DataImportConstants::BULK_MODE_MEMORY_TRESHOLD_PERCENT} instead.
     *
     * Specification:
     * - Defines a percent from memory could be used for bulk import.
     *
     * @api
     *
     * @var string
     */
    public const BULK_MODE_MEMORY_THESHOLD_PERCENT = 'DATA_IMPORT:BULK_MODE_MEMORY_THESHOLD_PERCENT';

    /**
     * Specification:
     * - Root path to the import files.
     * - Can be used to have a small set of import data for e.g. testing or development.
     *
     * @var string
     */
    public const IMPORT_FILE_ROOT_PATH = 'IMPORT_FILE_ROOT_PATH';

    /**
     * Specification:
     * - Defines data import queue reader chunk size.
     *
     * @api
     *
     * @var string
     */
    public const QUEUE_READER_CHUNK_SIZE = 'DATA_IMPORT:QUEUE_READER_CHUNK_SIZE';

    /**
     * Specification:
     * - Defines the size of the chunk to be used for writing messages into the queue in bulk.
     *
     * @api
     *
     * @var string
     */
    public const QUEUE_WRITER_CHUNK_SIZE = 'DATA_IMPORT:QUEUE_WRITER_CHUNK_SIZE';

    /**
     * Specification:
     * - Defines the size of the chunk to be used in publisher for triggering events to the queue.
     *
     * @api
     *
     * @var string
     */
    public const PUBLISHER_TRIGGER_CHUNK_SIZE = 'DATA_IMPORT:PUBLISHER_TRIGGER_CHUNK_SIZE';

    /**
     * Specification:
     * - Defines the size of the chunk to be used in publisher for flushing local cache of events.
     *
     * @api
     *
     * @var string
     */
    public const PUBLISHER_FLUSH_CHUNK_SIZE = 'DATA_IMPORT:PUBLISHER_FLUSH_CHUNK_SIZE';
}
