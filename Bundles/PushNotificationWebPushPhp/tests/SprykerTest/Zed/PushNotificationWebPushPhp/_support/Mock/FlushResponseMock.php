<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PushNotificationWebPushPhp\Mock;

use Generator;

class FlushResponseMock
{
    /**
     * @var array<\Minishlink\WebPush\MessageSentReport>
     */
    protected array $data;

    /**
     * @param array<\Minishlink\WebPush\MessageSentReport> $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * @return \Generator<\Minishlink\WebPush\MessageSentReport>
     */
    public function getResponse(): Generator
    {
        foreach ($this->data as $report) {
            yield $report;
        }
    }
}
