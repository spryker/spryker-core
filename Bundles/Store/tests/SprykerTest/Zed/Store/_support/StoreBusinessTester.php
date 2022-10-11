<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Store;

use Codeception\Actor;
use Codeception\Test\Feature\Stub;
use Generated\Shared\DataBuilder\MessageAttributesBuilder;
use Generated\Shared\DataBuilder\MessageBrokerTestMessageBuilder;
use Generated\Shared\Transfer\MessageAttributesTransfer;
use Generated\Shared\Transfer\MessageBrokerTestMessageTransfer;

/**
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = null)
 *
 * @SuppressWarnings(PHPMD)
 */
class StoreBusinessTester extends Actor
{
    use _generated\StoreBusinessTesterActions;

    use Stub;

    /**
     * @param string|null $storeReference
     *
     * @return \Generated\Shared\Transfer\MessageBrokerTestMessageTransfer
     */
    public function createMessageBrokerTestMessageTransfer(?string $storeReference = null): MessageBrokerTestMessageTransfer
    {
        return (new MessageBrokerTestMessageBuilder())
            ->withMessageAttributes(
                new MessageAttributesBuilder(
                    [
                        'storeReference' => $storeReference,
                    ],
                ),
            )
            ->build();
    }

    /**
     * @param array $seed
     *
     * @return \Generated\Shared\Transfer\MessageAttributesTransfer
     */
    public function createMessageAttributesTransfer(array $seed = []): MessageAttributesTransfer
    {
        return (new MessageAttributesBuilder($seed))->build();
    }
}
