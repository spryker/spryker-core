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
    const TYPE = 'file';

    const OPTION_LOG_PATH = 'log_path';
    const WRITERS = 'EVENT_JOURNAL_WRITERS';
    const COLLECTORS = 'EVENT_JOURNAL_COLLECTORS';
    const FILTERS = 'EVENT_JOURNAL_FILTERS';
    const WRITER_OPTIONS = 'EVENT_JOURNAL_WRITER_OPTIONS';
    const COLLECTOR_OPTIONS = 'EVENT_JOURNAL_COLLECTOR_OPTIONS';
    const FILTER_OPTIONS = 'EVENT_JOURNAL_FILTER_OPTIONS';
    const LOCK_OPTIONS = 'LOCK_OPTIONS';
    const NO_LOCK = 'NO_LOCK';

    /**
     * @deprecated This is only used to disable EventJournal in all new Shops until it gets removed from ZedRequest bundle
     */
    const DISABLE_EVENT_JOURNAL = 'DISABLE_EVENT_JOURNAL';
}
