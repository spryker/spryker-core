# MessageBroker Module
[![Latest Stable Version](https://poser.pugx.org/spryker/message-broker/v/stable.svg)](https://packagist.org/packages/spryker/message-broker)
[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%207.4-8892BF.svg)](https://php.net/)

Wraps message in a Symfony Envelope and sends it through the configured transport.
Starts a worker processes for the defined channels.

## Installation

```
composer require spryker/message-broker
```

## Documentation

[Spryker Documentation](https://docs.spryker.com)


### Configuration example (config_x.php)

This is just an example for testing purposes and the real configuration will be made via env variables.

```
$config[MessageBrokerAwsConstants::SNS_SENDER_CONFIG] = [
    'endpoint' => 'https://sns.eu-central-1.amazonaws.com',
    'accessKeyId' => '...',
    'accessKeySecret' => '...',
    'region' => 'eu-central-1',
    'topic' => 'arn:aws:sns:eu-central-1:...:message-broker-test',
];

$config[MessageBrokerAwsConstants::SQS_RECEIVER_CONFIG] = [
    'endpoint' => 'https://sqs.eu-central-1.amazonaws.com',
    'account' => '...',
    'accessKeyId' => '...',
    'accessKeySecret' => '...',
    'region' => 'eu-central-1',
    'queue_name' => 'message-broker-test',
    'poll_timeout' => 5,
    'queueUrl' => 'https://sqs.eu-central-1.amazonaws.com/.../message-broker-test',
    'auto_setup' => false,
];

$config[MessageBrokerAwsConstants::CHANNEL_TO_SENDER_CLIENT_MAP] = [
    'payment' => 'sns',
];

$config[MessageBrokerAwsConstants::CHANNEL_TO_RECEIVER_CLIENT_MAP] = [
    'payment' => 'sqs',
];

$config[MessageBrokerConstants::MESSAGE_TO_CHANNEL_MAP] = [
    PaymentMethodTransfer::class => 'payment',
];


$config[MessageBrokerAwsConstants::MESSAGE_TO_CHANNEL_MAP] = [
    PaymentMethodTransfer::class => 'payment',
];
```
