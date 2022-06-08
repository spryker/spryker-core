<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MessageBrokerAws\Business\Sns;

interface AwsSnsTopicCreatorInterface
{
    /**
     * @return void
     */
    public function createTopics(): void;
}
