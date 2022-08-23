<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MessageBrokerAws;

use Codeception\Actor;
use Generated\Shared\DataBuilder\MessageAttributesBuilder;
use Generated\Shared\DataBuilder\MessageBrokerTestMessageBuilder;
use Generated\Shared\DataBuilder\MessageBrokerTestMessageWithArrayBuilder;
use Generated\Shared\DataBuilder\MessageBrokerTestMessageWithNestedArrayBuilder;
use Generated\Shared\Transfer\MessageAttributesTransfer;
use Generated\Shared\Transfer\MessageBrokerTestMessageTransfer;
use Generated\Shared\Transfer\MessageBrokerTestMessageWithArrayTransfer;
use Generated\Shared\Transfer\MessageBrokerTestMessageWithNestedArrayTransfer;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;

/**
 * Inherited Methods
 *
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause()
 *
 * @SuppressWarnings(PHPMD)
 *
 * @method \Spryker\Zed\MessageBrokerAws\Business\MessageBrokerAwsFacadeInterface getFacade()
 * @method \Spryker\Zed\MessageBrokerAws\Business\MessageBrokerAwsBusinessFactory getFactory(?string $moduleName = NULL)()
 */
class MessageBrokerAwsBusinessTester extends Actor
{
    use _generated\MessageBrokerAwsBusinessTesterActions;

    /**
     * @var string
     */
    public const PUBLISHER = 'publisher';

    /**
     * @var string
     */
    protected const MESSAGE_ATTRIBUTES_KEY = 'message_attributes';

    /**
     * @param array $seed
     *
     * @return \Generated\Shared\Transfer\MessageAttributesTransfer
     */
    public function createMessageAttributesTransfer(array $seed = []): MessageAttributesTransfer
    {
        return (new MessageAttributesBuilder($seed))->build();
    }

    /**
     * @param array $seed
     *
     * @return \Generated\Shared\Transfer\MessageBrokerTestMessageTransfer
     */
    public function createMessageBrokerTestMessageTransfer(array $seed = []): MessageBrokerTestMessageTransfer
    {
        return (new MessageBrokerTestMessageBuilder($seed))->build();
    }

    /**
     * @param array $seed
     *
     * @return \Generated\Shared\Transfer\MessageBrokerTestMessageWithArrayTransfer
     */
    public function createMessageBrokerTestMessageWithArrayTransfer(
        array $seed = []
    ): MessageBrokerTestMessageWithArrayTransfer {
        return (new MessageBrokerTestMessageWithArrayBuilder($seed))->build();
    }

    /**
     * @param array $seed
     *
     * @return \Generated\Shared\Transfer\MessageBrokerTestMessageWithNestedArrayTransfer
     */
    public function createMessageBrokerTestMessageWithNestedArrayTransfer(
        array $seed = []
    ): MessageBrokerTestMessageWithNestedArrayTransfer {
        return (new MessageBrokerTestMessageWithNestedArrayBuilder($seed))->build();
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $transfer
     *
     * @return array<string, mixed>
     */
    public function createPayload(AbstractTransfer $transfer): array
    {
        $filteredTransferData = $this->unsetMessageAttributesRecursive($transfer->toArray());

        return [
            'body' => json_encode($filteredTransferData),
            'headers' => [
                'transferName' => $this->getTransferName($transfer),
                'publisher' => static::PUBLISHER,
            ],
        ];
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $transfer
     *
     * @return string
     */
    protected function getTransferName(AbstractTransfer $transfer): string
    {
        $fqcnParts = explode('\\', get_class($transfer));
        $className = array_pop($fqcnParts);

        return substr($className, 0, -8);
    }

    /**
     * @param array<string, mixed> $data
     *
     * @return array<string, mixed>
     */
    protected function unsetMessageAttributesRecursive(array $data): array
    {
        $filteredData = [];

        foreach ($data as $key => $value) {
            if ($key === static::MESSAGE_ATTRIBUTES_KEY) {
                continue;
            }

            if (is_array($value)) {
                $value = $this->unsetMessageAttributesRecursive($value);
            }

            $filteredData[$key] = $value;
        }

        return $filteredData;
    }
}
