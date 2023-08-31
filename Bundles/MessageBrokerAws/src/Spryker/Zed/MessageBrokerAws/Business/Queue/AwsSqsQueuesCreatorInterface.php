<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MessageBrokerAws\Business\Queue;

/**
 * @deprecated Will be removed without replacement.
 */
interface AwsSqsQueuesCreatorInterface
{
    /**
     * @return void
     */
    public function createQueues(): void;
}
