<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Log\Processor;

class AuditLogMetaDataProcessor implements ProcessorInterface
{
    /**
     * @var string
     */
    protected const RECORD_KEY_EXTRA = 'extra';

    /**
     * @var string
     */
    protected const RECORD_KEY_LOG_TYPE = 'log_type';

    /**
     * @var string
     */
    protected const TAG_AUDIT_LOG = 'audit_log';

    /**
     * @param array<string, mixed> $data
     *
     * @return array<string, mixed>
     */
    public function __invoke(array $data): array
    {
        $data[static::RECORD_KEY_EXTRA][static::RECORD_KEY_LOG_TYPE] = static::TAG_AUDIT_LOG;

        return $data;
    }
}
