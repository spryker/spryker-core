<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesPayment\Helper;

use SprykerTest\Shared\Testify\Helper\AbstractHelper;
use SprykerTest\Zed\MessageBroker\Helper\InMemoryMessageBrokerHelperTrait;

class SalesPaymentHelper extends AbstractHelper
{
    use InMemoryMessageBrokerHelperTrait;

    /**
     * @param string $messageClassName
     * @param array $properties
     *
     * @return void
     */
    public function assertSentMessageProperties(string $messageClassName, array $properties): void
    {
        $this->getInMemoryMessageBrokerHelper()->assertMessagesByCallbackForMessageName(
            function (array $envelopes) use ($properties): void {
                /** @var array<\Symfony\Component\Messenger\Envelope> $envelopes */
                $this->assertCount(1, $envelopes);

                /** @var \Spryker\Shared\Kernel\Transfer\TransferInterface $message */
                $message = $envelopes[0]->getMessage();

                $this->assertEquals(
                    $properties,
                    array_replace_recursive(
                        $properties,
                        array_intersect_key($message->toArray(), $properties),
                    ),
                );
            },
            $messageClassName,
        );
    }
}
