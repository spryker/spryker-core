<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MessageBrokerAws\Business\Queue;

use Aws\Sqs\SqsClient;
use Spryker\Zed\MessageBrokerAws\MessageBrokerAwsConfig;

/**
 * @deprecated Will be removed without replacement.
 */
class AwsSqsQueuesCreator implements AwsSqsQueuesCreatorInterface
{
    /**
     * @var \Aws\Sqs\SqsClient
     */
    protected $sqsClient;

    /**
     * @var \Spryker\Zed\MessageBrokerAws\MessageBrokerAwsConfig
     */
    protected $messageBrokerAwsConfig;

    /**
     * @param \Aws\Sqs\SqsClient $sqsClient
     * @param \Spryker\Zed\MessageBrokerAws\MessageBrokerAwsConfig $messageBrokerAwsConfig
     */
    public function __construct(
        SqsClient $sqsClient,
        MessageBrokerAwsConfig $messageBrokerAwsConfig
    ) {
        $this->sqsClient = $sqsClient;
        $this->messageBrokerAwsConfig = $messageBrokerAwsConfig;
    }

    /**
     * @return void
     */
    public function createQueues(): void
    {
        foreach ($this->messageBrokerAwsConfig->getSqsQueuesNames() as $sqsQueueName) {
            $this->sqsClient->createQueue([
                'QueueName' => $sqsQueueName,
                'Attributes' => [
                    'FifoQueue' => 'true',
                ],
            ]);
        }
    }
}
