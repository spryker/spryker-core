<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\EventJournal;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
interface EventJournalConstants
{
    public const TYPE = 'file';

    public const OPTION_LOG_PATH = 'log_path';
    public const WRITERS = 'EVENT_JOURNAL_WRITERS';
    public const COLLECTORS = 'EVENT_JOURNAL_COLLECTORS';
    public const FILTERS = 'EVENT_JOURNAL_FILTERS';
    public const WRITER_OPTIONS = 'EVENT_JOURNAL_WRITER_OPTIONS';
    public const COLLECTOR_OPTIONS = 'EVENT_JOURNAL_COLLECTOR_OPTIONS';
    public const FILTER_OPTIONS = 'EVENT_JOURNAL_FILTER_OPTIONS';
    public const LOCK_OPTIONS = 'LOCK_OPTIONS';
    public const NO_LOCK = 'NO_LOCK';

    /**
     * @deprecated This is only used to disable EventJournal in all new Shops until it gets removed from ZedRequest bundle
     */
    public const DISABLE_EVENT_JOURNAL = 'DISABLE_EVENT_JOURNAL';
}
