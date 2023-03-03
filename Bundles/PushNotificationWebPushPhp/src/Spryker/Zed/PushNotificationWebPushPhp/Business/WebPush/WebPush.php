<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PushNotificationWebPushPhp\Business\WebPush;

use Closure;
use ErrorException;
use Generator;
use InvalidArgumentException;
use Minishlink\WebPush\Encryption;
use Minishlink\WebPush\SubscriptionInterface;
use Minishlink\WebPush\Utils;
use Minishlink\WebPush\VAPID;
use Minishlink\WebPush\WebPush as MinishlinkWebPush;
use Psr\Http\Message\RequestInterface;

class WebPush extends MinishlinkWebPush
{
    /**
     * Queue a notification. Will be sent when flush() is called.
     *
     * @param \Minishlink\WebPush\SubscriptionInterface $subscription
     * @param string|null $payload If you want to send an array or object, json_encode it
     * @param array<string, mixed> $options Array with several options tied to this notification. If not set, will use the default options that you can set in the WebPush object
     * @param array<string, mixed> $auth Use this auth details instead of what you provided when creating WebPush
     * @param int|null $pushNotificationIdentifier
     * @param int|null $pushNotificationSubscriptionIdentifier
     *
     * @throws \ErrorException
     * @throws \InvalidArgumentException
     *
     * @return void
     */
    public function queueNotification(
        SubscriptionInterface $subscription,
        ?string $payload = null,
        array $options = [],
        array $auth = [],
        ?int $pushNotificationIdentifier = null,
        ?int $pushNotificationSubscriptionIdentifier = null
    ): void {
        if (!$pushNotificationIdentifier || !$pushNotificationSubscriptionIdentifier) {
            throw new InvalidArgumentException(
                'pushNotificationIdentifier and pushNotificationSubscriptionIdentifier should be defined.',
            );
        }
        if ($payload) {
            if (Utils::safeStrlen($payload) > Encryption::MAX_PAYLOAD_LENGTH) {
                throw new ErrorException(
                    'Size of payload must not be greater than ' . Encryption::MAX_PAYLOAD_LENGTH . ' octets.',
                );
            }

            $contentEncoding = $subscription->getContentEncoding();
            if (!$contentEncoding) {
                throw new ErrorException('Subscription should have a content encoding');
            }

            $payload = Encryption::padPayload($payload, $this->automaticPadding, $contentEncoding);
        }

        if (array_key_exists('VAPID', $auth)) {
            $auth['VAPID'] = VAPID::validate($auth['VAPID']);
        }

        $this->notifications[] = new Notification(
            $pushNotificationIdentifier,
            $pushNotificationSubscriptionIdentifier,
            $subscription,
            $payload,
            $options,
            $auth,
        );
    }

    /**
     * Flush notifications. Triggers the requests.
     *
     * @param int|null $batchSize Defaults the value defined in defaultOptions during instantiation (which defaults to 1000).
     *
     * @return \Generator<\Minishlink\WebPush\MessageSentReport>
     */
    public function flush(?int $batchSize = null): Generator
    {
        if (!$this->notifications) {
            yield from [];

            return;
        }

        if ($batchSize === null) {
            $batchSize = $this->defaultOptions['batchSize'];
        }

        $batches = array_chunk($this->notifications, $batchSize);

        $this->notifications = [];
        foreach ($batches as $batch) {
            $promises = [];
            $promises = $this->sendBatch($batch, $promises);

            foreach ($promises as $promise) {
                yield $promise->wait();
            }
        }

        if ($this->reuseVAPIDHeaders) {
            $this->vapidHeaders = [];
        }
    }

    /**
     * @param array<\Spryker\Zed\PushNotificationWebPushPhp\Business\WebPush\Notification> $notifications
     * @param array<\GuzzleHttp\Promise\PromiseInterface> $promises
     *
     * @return array<\GuzzleHttp\Promise\PromiseInterface>
     */
    protected function sendBatch(array $notifications, array $promises): array
    {
        /** @var \Spryker\Zed\PushNotificationWebPushPhp\Business\WebPush\Notification $notification */
        foreach ($notifications as $notification) {
            $request = $this->prepare([$notification])[0];
            $promises[] = $this->client->sendAsync($request)
                ->then($this->getSuccessfulHandler($request, $notification))
                ->otherwise($this->getFailureHandler($notification));
        }

        return $promises;
    }

    /**
     * @param \Psr\Http\Message\RequestInterface $request
     * @param \Spryker\Zed\PushNotificationWebPushPhp\Business\WebPush\Notification $notification
     *
     * @return \Closure
     */
    protected function getSuccessfulHandler(RequestInterface $request, Notification $notification): Closure
    {
        return function ($response) use ($request, $notification) {
            /** @var \Psr\Http\Message\ResponseInterface $response */
            return new MessageSentReport(
                $notification->getPushNotificationIdentifier(),
                $notification->getPushNotificationSubscriptionIdentifier(),
                $request,
                $response,
            );
        };
    }

    /**
     * @param \Spryker\Zed\PushNotificationWebPushPhp\Business\WebPush\Notification $notification
     *
     * @return \Closure
     */
    protected function getFailureHandler(Notification $notification): Closure
    {
        return function ($reason) use ($notification) {
            if (method_exists($reason, 'getResponse')) {
                $response = $reason->getResponse();
            } else {
                $response = null;
            }

            return new MessageSentReport(
                $notification->getPushNotificationIdentifier(),
                $notification->getPushNotificationSubscriptionIdentifier(),
                $reason->getRequest(),
                $response,
                false,
                $reason->getMessage(),
            );
        };
    }
}
