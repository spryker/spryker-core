<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Application\Log\Processor;

class ResponseProcessor
{
    const EXTRA = 'response';
    const CONTEXT_KEY = 'response';

    const RECORD_CONTEXT = 'context';

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
