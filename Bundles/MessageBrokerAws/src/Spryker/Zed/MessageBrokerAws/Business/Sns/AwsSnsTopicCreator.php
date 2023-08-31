<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MessageBrokerAws\Business\Sns;

use Aws\Sns\SnsClient;
use Spryker\Zed\MessageBrokerAws\MessageBrokerAwsConfig;

/**
 * @deprecated Will be removed without replacement.
 */
class AwsSnsTopicCreator implements AwsSnsTopicCreatorInterface
{
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
    public function createTopics(): void
    {
        foreach ($this->messageBrokerAwsConfig->getSnsTopicNames() as $snsTopicName) {
            $this->snsClient->createTopic([
                'Name' => $snsTopicName,
                'Attributes' => [
                    'FifoTopic' => 'true',
                ],
            ]);
        }
    }
}
