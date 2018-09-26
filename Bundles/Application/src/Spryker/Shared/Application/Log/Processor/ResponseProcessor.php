<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Application\Log\Processor;

/**
 * @deprecated Use `ResponseProcessorPlugin` from Log module instead.
 */
class ResponseProcessor
{
    public const EXTRA = 'response';
    public const CONTEXT_KEY = 'response';

    public const RECORD_CONTEXT = 'context';

    /**
     * @param array $record
     *
     * @return array
     */
    public function __invoke(array $record)
    {
        if (isset($record[static::RECORD_CONTEXT][static::EXTRA])) {
            unset($record[static::RECORD_CONTEXT][static::EXTRA]);
        }

        return $record;
    }
}
