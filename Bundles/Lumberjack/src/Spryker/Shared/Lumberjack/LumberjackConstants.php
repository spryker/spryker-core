<?php

/**
 * (c) Copyright Spryker Systems GmbH 2015
 */

namespace Spryker\Shared\Lumberjack;
/**
 * @deprecated Lumberjack is deprecated use EventJournal instead.
 */
interface LumberjackConstants
{

    const WRITERS = 'LUMBERJACK_WRITERS';

    const COLLECTORS = 'LUMBERJACK_COLLECTORS';

    const LOG_PATH_WITH_SLASH_AT_THE_END = 'LUMBERJACK_LOG_PATH';

    const WRITER_OPTIONS = 'LUMBERJACK_WRITER_OPTIONS';

    const COLLECTOR_OPTIONS = 'LUMBERJACK_COLLECTOR_OPTIONS';

}
