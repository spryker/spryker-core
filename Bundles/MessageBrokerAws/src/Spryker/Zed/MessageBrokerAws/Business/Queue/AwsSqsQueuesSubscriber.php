<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MessageBrokerAws\Business\Queue;

use Aws\Sns\SnsClient;
use Spryker\Zed\MessageBrokerAws\MessageBrokerAwsConfig;

class AwsSqsQueuesSubscriber implements AwsSqsQueuesSubscriberInterface
{
    /**
     * @var string
     */
    protected const SQS_PROTOCOL = 'sqs';

    /**
     * @var \Aws\Sns\SnsClient
     */
    protected $snsClient;

    /**
     * @var \Spryker\Zed\MessageBrokerAws\MessageBrokerAwsConfig
     */
    protected $messageBrokerAwsConfig;

    /**
     * @param \Aws\Sns\SnsClient $snsClient
     * @param \Spryker\Zed\MessageBrokerAws\MessageBrokerAwsConfig $messageBrokerAwsConfig
     */
    public function __construct(
        SnsClient $snsClient,
        MessageBrokerAwsConfig $messageBrokerAwsConfig
    ) {
        $this->snsClient = $snsClient;
        $this->messageBrokerAwsConfig = $messageBrokerAwsConfig;
    }

    /**
     * @return void
     */
    public function subscribeSqsToSns(): void
    {
        foreach ($this->messageBrokerAwsConfig->getSqsToSnsSubscriptions() as $sqsSubscription) {
            $sqsSubscription['Protocol'] = $sqsSubscription['Protocol'] ?? static::SQS_PROTOCOL;
            $sqsSubscription['ReturnSubscriptionArn'] = false;

            if (!isset($sqsSubscription['Attributes'])) {
                $sqsSubscription['Attributes'] = [];
            }

            $sqsSubscription['Attributes']['RawMessageDelivery'] = 'true';

            if (isset($sqsSubscription['FilterPolicy'])) {
                $sqsSubscription['Attributes']['FilterPolicy'] = $sqsSubscription['FilterPolicy'];
                unset($sqsSubscription['FilterPolicy']);
            }

            $this->snsClient->subscribe($sqsSubscription);
        }
    }
}
