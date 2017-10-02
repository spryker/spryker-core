<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Loggly;

interface LogglyConstants
{

    /**
     * Specification:
     * - Token for your loggly account.
     *
     * @api
     */
    const TOKEN = 'LOGGLY:TOKEN';

    /**
     * Specification:
     * - Name of your Loggly log queue (default: loggly-log-queue).
     *
     * @api
     */
    const QUEUE_NAME = 'LOGGLY:QUEUE_NAME';

    /**
     * Specification:
     * - Chunk size for messages to be processed from queue (default: 50).
     *
     * @api
     */
    const QUEUE_CHUNK_SIZE = 'LOGGLY:QUEUE_CHUNK_SIZE';

}
