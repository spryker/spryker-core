<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Payment;

use Codeception\Actor;
use DateTime;
use Generated\Shared\DataBuilder\PaymentMethodBuilder;
use Generated\Shared\DataBuilder\StoreBuilder;
use Generated\Shared\Transfer\MessageAttributesTransfer;
use Generated\Shared\Transfer\PaymentMethodAddedTransfer;
use Generated\Shared\Transfer\PaymentMethodDeletedTransfer;
use Generated\Shared\Transfer\PaymentMethodTransfer;
use Generated\Shared\Transfer\PaymentProviderTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\Payment\Persistence\SpyPaymentMethodQuery;
use Orm\Zed\Payment\Persistence\SpyPaymentProviderQuery;

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
 * @SuppressWarnings(\SprykerTest\Zed\Payment\PHPMD)
 */
class PaymentBusinessTester extends Actor
{
    use _generated\PaymentBusinessTesterActions;

    /**
     * @var string
     */
    protected const STORE_REFERENCE = 'dev-DE';

    /**
     * @var string
     */
    protected const STORE_NAME = 'DE';

    /**
     * @var string
     */
    protected const PAYMENT_METHOD_NAME = 'name-2';

    /**
     * @var string
     */
    protected const PAYMENT_REDIRECT_URL = 'redirect-url';

    /**
     * @var string
     */
    protected const PAYMENT_PROVIDER_KEY = 'provider-key';

    /**
     * @param array<mixed> $seedData
     *
     * @return \Generated\Shared\Transfer\PaymentMethodTransfer
     */
    public function getPaymentMethodTransfer(array $seedData = []): PaymentMethodTransfer
    {
        return (new PaymentMethodBuilder($seedData))->build();
    }

    /**
     * @param array<mixed> $seedData
     *
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    public function getStoreTransfer(array $seedData = []): StoreTransfer
    {
        return (new StoreBuilder($seedData))->build();
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentMethodTransfer $paymentMethodTransfer
     * @param \Generated\Shared\Transfer\PaymentMethodDeletedTransfer $paymentMethodDeletedTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentMethodDeletedTransfer
     */
    public function mapPaymentMethodTransferToPaymentMethodDeletedTransfer(
        PaymentMethodTransfer $paymentMethodTransfer,
        PaymentMethodDeletedTransfer $paymentMethodDeletedTransfer
    ): PaymentMethodDeletedTransfer {
        $paymentMethodDeletedTransfer
            ->setName($paymentMethodTransfer->getLabelName())
            ->setProviderName($paymentMethodTransfer->getGroupName());

        return $paymentMethodDeletedTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentMethodTransfer $paymentMethodTransfer
     * @param \Generated\Shared\Transfer\PaymentMethodAddedTransfer $paymentMethodAddedTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentMethodAddedTransfer
     */
    public function mapPaymentMethodTransferToPaymentMethodAddedTransfer(
        PaymentMethodTransfer $paymentMethodTransfer,
        PaymentMethodAddedTransfer $paymentMethodAddedTransfer
    ): PaymentMethodAddedTransfer {
        $paymentMethodAddedTransfer
            ->setName($paymentMethodTransfer->getLabelName())
            ->setProviderName($paymentMethodTransfer->getGroupName())
            ->setPaymentAuthorizationEndpoint($paymentMethodTransfer->getPaymentAuthorizationEndpoint());

        return $paymentMethodAddedTransfer;
    }

    /**
     * @return int
     */
    public function getNumberOfPersistentPaymentMethods(): int
    {
        return SpyPaymentMethodQuery::create()->count();
    }

    /**
     * @return int
     */
    public function getNumberOfPersistentPaymentProviders(): int
    {
        return SpyPaymentProviderQuery::create()->count();
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentProviderTransfer $paymentProviderTransfer
     * @param string $timestamp
     *
     * @return \Generated\Shared\Transfer\PaymentMethodDeletedTransfer
     */
    public function havePaymentMethodDeletedTransferWithTimestamp(
        PaymentProviderTransfer $paymentProviderTransfer,
        string $timestamp
    ): PaymentMethodDeletedTransfer {
        return $this->havePaymentMethodDeletedTransfer([
            PaymentMethodDeletedTransfer::NAME => static::PAYMENT_METHOD_NAME,
            PaymentMethodDeletedTransfer::PAYMENT_AUTHORIZATION_ENDPOINT => static::PAYMENT_REDIRECT_URL,
            PaymentMethodDeletedTransfer::PROVIDER_NAME => $paymentProviderTransfer->getPaymentProviderKey(),
            PaymentMethodDeletedTransfer::MESSAGE_ATTRIBUTES => [
                MessageAttributesTransfer::STORE_REFERENCE => static::STORE_REFERENCE,
                MessageAttributesTransfer::TIMESTAMP => $timestamp,
            ],
        ]);
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentProviderTransfer|null $paymentProviderTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentMethodDeletedTransfer
     */
    public function havePaymentMethodDeletedTransferWithoutTimestamp(
        ?PaymentProviderTransfer $paymentProviderTransfer = null
    ): PaymentMethodDeletedTransfer {
        $providerKey = $paymentProviderTransfer ? $paymentProviderTransfer->getPaymentProviderKey() : static::PAYMENT_PROVIDER_KEY;

        return $this->havePaymentMethodDeletedTransfer([
            PaymentMethodDeletedTransfer::NAME => static::PAYMENT_METHOD_NAME,
            PaymentMethodDeletedTransfer::PAYMENT_AUTHORIZATION_ENDPOINT => static::PAYMENT_REDIRECT_URL,
            PaymentMethodDeletedTransfer::PROVIDER_NAME => $providerKey,
            PaymentMethodDeletedTransfer::MESSAGE_ATTRIBUTES => [
                MessageAttributesTransfer::STORE_REFERENCE => static::STORE_REFERENCE,
            ],
        ]);
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentProviderTransfer $paymentProviderTransfer
     * @param string $timestamp
     *
     * @return \Generated\Shared\Transfer\PaymentMethodAddedTransfer
     */
    public function havePaymentMethodAddedTransferWithTimestamp(
        PaymentProviderTransfer $paymentProviderTransfer,
        string $timestamp
    ): PaymentMethodAddedTransfer {
        return $this->havePaymentMethodAddedTransfer([
            PaymentMethodAddedTransfer::NAME => static::PAYMENT_METHOD_NAME,
            PaymentMethodAddedTransfer::PAYMENT_AUTHORIZATION_ENDPOINT => static::PAYMENT_REDIRECT_URL,
            PaymentMethodAddedTransfer::PROVIDER_NAME => $paymentProviderTransfer->getPaymentProviderKey(),
            PaymentMethodAddedTransfer::MESSAGE_ATTRIBUTES => [
                MessageAttributesTransfer::STORE_REFERENCE => static::STORE_REFERENCE,
                MessageAttributesTransfer::TIMESTAMP => $timestamp,
            ],
        ]);
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentProviderTransfer $paymentProviderTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentMethodAddedTransfer
     */
    public function havePaymentMethodAddedTransferWithoutTimestamp(
        PaymentProviderTransfer $paymentProviderTransfer
    ): PaymentMethodAddedTransfer {
        return $this->havePaymentMethodAddedTransfer([
            PaymentMethodAddedTransfer::NAME => static::PAYMENT_METHOD_NAME,
            PaymentMethodAddedTransfer::PAYMENT_AUTHORIZATION_ENDPOINT => static::PAYMENT_REDIRECT_URL,
            PaymentMethodAddedTransfer::PROVIDER_NAME => $paymentProviderTransfer->getPaymentProviderKey(), PaymentMethodDeletedTransfer::MESSAGE_ATTRIBUTES => [
                MessageAttributesTransfer::STORE_REFERENCE => static::STORE_REFERENCE,
            ],
        ]);
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentProviderTransfer $paymentProviderTransfer
     * @param string $timestamp
     * @param bool $addStore
     *
     * @return \Generated\Shared\Transfer\PaymentMethodTransfer
     */
    public function createDisabledPaymentMethodWithTimestampOnDatabase(
        PaymentProviderTransfer $paymentProviderTransfer,
        string $timestamp,
        bool $addStore = true
    ): PaymentMethodTransfer {
        return $this->havePaymentMethod([
            PaymentMethodTransfer::IS_HIDDEN => true,
            PaymentMethodTransfer::ID_PAYMENT_PROVIDER => $paymentProviderTransfer->getIdPaymentProvider(),
            PaymentMethodTransfer::LAST_MESSAGE_TIMESTAMP => $timestamp,
            PaymentMethodTransfer::PAYMENT_METHOD_KEY => $this->generatePaymentMethodKey(
                $paymentProviderTransfer->getPaymentProviderKey(),
                $addStore,
            ),
        ]);
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentProviderTransfer $paymentProviderTransfer
     * @param bool $addStore
     *
     * @return \Generated\Shared\Transfer\PaymentMethodTransfer
     */
    public function createDisabledPaymentMethodWithoutTimestampOnDatabase(
        PaymentProviderTransfer $paymentProviderTransfer,
        bool $addStore = true
    ): PaymentMethodTransfer {
        return $this->havePaymentMethod([
            PaymentMethodTransfer::IS_HIDDEN => true,
            PaymentMethodTransfer::ID_PAYMENT_PROVIDER => $paymentProviderTransfer->getIdPaymentProvider(),
            PaymentMethodTransfer::LAST_MESSAGE_TIMESTAMP => null,
            PaymentMethodTransfer::PAYMENT_METHOD_KEY => $this->generatePaymentMethodKey(
                $paymentProviderTransfer->getPaymentProviderKey(),
                $addStore,
            ),
        ]);
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentProviderTransfer $paymentProviderTransfer
     * @param string $timestamp *
     * @param bool $addStore
     *
     * @return \Generated\Shared\Transfer\PaymentMethodTransfer
     */
    public function createEnabledPaymentMethodWithTimestampOnDatabase(
        PaymentProviderTransfer $paymentProviderTransfer,
        string $timestamp,
        bool $addStore = true
    ): PaymentMethodTransfer {
        return $this->havePaymentMethod([
            PaymentMethodTransfer::IS_HIDDEN => false,
            PaymentMethodTransfer::ID_PAYMENT_PROVIDER => $paymentProviderTransfer->getIdPaymentProvider(),
            PaymentMethodTransfer::LAST_MESSAGE_TIMESTAMP => $timestamp,
            PaymentMethodTransfer::PAYMENT_METHOD_KEY => $this->generatePaymentMethodKey(
                $paymentProviderTransfer->getPaymentProviderKey(),
                $addStore,
            ),
        ]);
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentProviderTransfer $paymentProviderTransfer
     * @param bool $addStore
     *
     * @return \Generated\Shared\Transfer\PaymentMethodTransfer
     */
    public function createEnabledPaymentMethodWithoutTimestampOnDatabase(
        PaymentProviderTransfer $paymentProviderTransfer,
        bool $addStore = true
    ): PaymentMethodTransfer {
        return $this->havePaymentMethod([
            PaymentMethodTransfer::IS_HIDDEN => false,
            PaymentMethodTransfer::ID_PAYMENT_PROVIDER => $paymentProviderTransfer->getIdPaymentProvider(),
            PaymentMethodTransfer::LAST_MESSAGE_TIMESTAMP => null,
            PaymentMethodTransfer::PAYMENT_METHOD_KEY => $this->generatePaymentMethodKey(
                $paymentProviderTransfer->getPaymentProviderKey(),
                $addStore,
            ),
        ]);
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentMethodDeletedTransfer $messagePaymentMethodDeletedTransfer
     * @param bool $addStore
     *
     * @return void
     */
    public function assertDisabledPaymentMethodWasCreatedWithSoftDeletion(
        PaymentMethodDeletedTransfer $messagePaymentMethodDeletedTransfer,
        bool $addStore = true
    ): void {
        $filterPaymentMethodTransfer = (new PaymentMethodTransfer())
            ->setPaymentMethodKey(
                $this->generatePaymentMethodKey(
                    $messagePaymentMethodDeletedTransfer->getProviderName(),
                    $addStore,
                ),
            );

        $createdPaymentMethodTransfer = $this->findPaymentMethod($filterPaymentMethodTransfer);

        $this->assertNotNull(
            $createdPaymentMethodTransfer->getIdPaymentMethod(),
            'The disabled Payment Method must have an ID',
        );

        $this->assertNotNull(
            $createdPaymentMethodTransfer->getIdPaymentProvider(),
            'The disabled Payment Method must belong to a Payment Provider',
        );

        $this->assertTrue(
            $createdPaymentMethodTransfer->getIsHidden(),
            'The disabled Payment Method must be created with is_hidden equals true',
        );

        $this->assertSame(
            $messagePaymentMethodDeletedTransfer->getName(),
            $createdPaymentMethodTransfer->getName(),
            'The disabled Payment Method must have the same name of the original Payment Method Deleted Transfer',
        );

        $this->assertSame(
            $messagePaymentMethodDeletedTransfer->getProviderName(),
            $createdPaymentMethodTransfer->getGroupName(),
            'The disabled Payment Method must have the same provider name of the original Payment Method Deleted Transfer',
        );
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentMethodTransfer $disabledPaymentMethodTransfer
     *
     * @return void
     */
    public function assertDisabledPaymentMethodDidNotChange(
        PaymentMethodTransfer $disabledPaymentMethodTransfer
    ): void {
        $filterPaymentMethodTransfer = (new PaymentMethodTransfer())->setIdPaymentMethod(
            $disabledPaymentMethodTransfer->getIdPaymentMethod(),
        );

        $paymentMethodFound = $this->findPaymentMethod($filterPaymentMethodTransfer);

        $this->assertTrue(
            $paymentMethodFound->getIsHidden(),
            'The disabled Payment Method must remain hidden',
        );

        $this->assertEquals(
            str_replace('T', ' ', $disabledPaymentMethodTransfer->getLastMessageTimestamp()),
            $paymentMethodFound->getLastMessageTimestamp(),
            'The disabled Payment Method\'s last message timestamp must remain the same',
        );
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentMethodTransfer $disabledPaymentMethod
     * @param \Generated\Shared\Transfer\PaymentMethodAddedTransfer $messagePaymentMethodAddedTransfer
     *
     * @return void
     */
    public function assertDisabledPaymentMethodWasEnabledAndTimestampChanged(
        PaymentMethodTransfer $disabledPaymentMethod,
        PaymentMethodAddedTransfer $messagePaymentMethodAddedTransfer
    ): void {
        $filterPaymentMethodTransfer = (new PaymentMethodTransfer())->setIdPaymentMethod(
            $disabledPaymentMethod->getIdPaymentMethod(),
        );

        $paymentMethodFound = $this->findPaymentMethod($filterPaymentMethodTransfer);

        $this->assertFalse(
            $paymentMethodFound->getIsHidden(),
            'The disabled Payment Method must have been enabled',
        );

        $this->assertEquals(
            str_replace('T', ' ', $messagePaymentMethodAddedTransfer->getMessageAttributes()->getTimestamp()),
            $paymentMethodFound->getLastMessageTimestamp(),
            'The disabled Payment Method\'s last message timestamp equals to message timestamp',
        );
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentMethodTransfer $disabledPaymentMethod
     *
     * @return void
     */
    public function assertDisabledPaymentMethodWasEnabledAndTimestampWasUpdated(
        PaymentMethodTransfer $disabledPaymentMethod
    ): void {
        $filterPaymentMethodTransfer = (new PaymentMethodTransfer())->setIdPaymentMethod(
            $disabledPaymentMethod->getIdPaymentMethod(),
        );

        $paymentMethodFound = $this->findPaymentMethod($filterPaymentMethodTransfer);

        $this->assertFalse(
            $paymentMethodFound->getIsHidden(),
            'The disabled Payment Method must have been enabled',
        );

        $this->assertNotEquals(
            str_replace('T', ' ', $disabledPaymentMethod->getLastMessageTimestamp()),
            $paymentMethodFound->getLastMessageTimestamp(),
            'The disabled Payment Method\'s last message timestamp must had to be updated',
        );

        $disabledPaymentMethodDatetime = new DateTime(
            $disabledPaymentMethod->getLastMessageTimestamp(),
        );

        $paymentMethodFoundDatetime = new DateTime(
            $paymentMethodFound->getLastMessageTimestamp(),
        );

        $this->assertTrue(
            $paymentMethodFoundDatetime > $disabledPaymentMethodDatetime,
            'The disabled Payment Method\'s must update the last message timestamp to most a recent date',
        );
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentMethodTransfer $enabledPaymentMethodTransfer
     *
     * @return void
     */
    public function assertEnabledPaymentMethodDidNotChange(
        PaymentMethodTransfer $enabledPaymentMethodTransfer
    ): void {
        $filterPaymentMethodTransfer = (new PaymentMethodTransfer())->setIdPaymentMethod(
            $enabledPaymentMethodTransfer->getIdPaymentMethod(),
        );

        $paymentMethodFound = $this->findPaymentMethod($filterPaymentMethodTransfer);

        $this->assertFalse(
            $paymentMethodFound->getIsHidden(),
            'The enabled Payment Method must remain NOT hidden',
        );

        $this->assertEquals(
            str_replace('T', ' ', $enabledPaymentMethodTransfer->getLastMessageTimestamp()),
            $paymentMethodFound->getLastMessageTimestamp(),
            'The enabled Payment Method\'s last message timestamp must remain the same',
        );
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentMethodTransfer $enabledPaymentMethod
     * @param \Generated\Shared\Transfer\PaymentMethodDeletedTransfer $messagePaymentMethodDeletedTransfer
     *
     * @return void
     */
    public function assertEnabledPaymentMethodWasDisabledAndTimestampChanged(
        PaymentMethodTransfer $enabledPaymentMethod,
        PaymentMethodDeletedTransfer $messagePaymentMethodDeletedTransfer
    ): void {
        $filterPaymentMethodTransfer = (new PaymentMethodTransfer())->setIdPaymentMethod(
            $enabledPaymentMethod->getIdPaymentMethod(),
        );

        $paymentMethodFound = $this->findPaymentMethod($filterPaymentMethodTransfer);

        $this->assertTrue(
            $paymentMethodFound->getIsHidden(),
            'The enabled Payment Method must have been disabled',
        );

        $this->assertEquals(
            str_replace('T', ' ', $messagePaymentMethodDeletedTransfer->getMessageAttributes()->getTimestamp()),
            $paymentMethodFound->getLastMessageTimestamp(),
            'The enabled Payment Method\'s last message timestamp equals to message timestamp',
        );
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentMethodTransfer $enabledPaymentMethod
     * @param \Generated\Shared\Transfer\PaymentMethodDeletedTransfer $messagePaymentMethodDeletedTransfer
     *
     * @return void
     */
    public function assertEnabledPaymentMethodWasDisabledAndTimestampWasUpdated(
        PaymentMethodTransfer $enabledPaymentMethod,
        PaymentMethodDeletedTransfer $messagePaymentMethodDeletedTransfer
    ): void {
        $filterPaymentMethodTransfer = (new PaymentMethodTransfer())->setIdPaymentMethod(
            $enabledPaymentMethod->getIdPaymentMethod(),
        );

        $paymentMethodFound = $this->findPaymentMethod($filterPaymentMethodTransfer);

        $this->assertTrue(
            $paymentMethodFound->getIsHidden(),
            'The enabled Payment Method must have been disabled',
        );

        $this->assertNotEquals(
            str_replace('T', ' ', $enabledPaymentMethod->getLastMessageTimestamp()),
            $paymentMethodFound->getLastMessageTimestamp(),
            'The enabled Payment Method\'s last message timestamp must had to be updated',
        );

        $enabledPaymentMethodDatetime = new DateTime(
            $enabledPaymentMethod->getLastMessageTimestamp(),
        );

        $paymentMethodFoundDatetime = new DateTime(
            $paymentMethodFound->getLastMessageTimestamp(),
        );

        $this->assertTrue(
            $paymentMethodFoundDatetime > $enabledPaymentMethodDatetime,
            'The enabled Payment Method\'s must update the last message timestamp to most a recent date',
        );
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentMethodTransfer $existentPaymentMethod
     * @param string $providerName *
     * @param bool $addStore
     *
     * @return void
     */
    public function assertRightPaymentMethodWasUpdated(
        PaymentMethodTransfer $existentPaymentMethod,
        string $providerName,
        bool $addStore = true
    ): void {
        $filterPaymentMethodTransfer = (new PaymentMethodTransfer())->setPaymentMethodKey(
            $this->generatePaymentMethodKey(
                $providerName,
                $addStore,
            ),
        );

        $paymentMethodFound = $this->findPaymentMethod($filterPaymentMethodTransfer);

        $this->assertNotNull(
            $paymentMethodFound,
            'It must have exist a new Payment Method record on database',
        );

        $this->assertNotEquals(
            $existentPaymentMethod->getIsHidden(),
            $paymentMethodFound->getIsHidden(),
            'The existent Payment Method must remain unchanged',
        );

        $this->assertNotEquals(
            $existentPaymentMethod->getIdPaymentMethod(),
            $paymentMethodFound->getIdPaymentMethod(),
            'The updated Payment Method must not be the existent Payment Method',
        );
    }

    /**
     * @param string $paymentProviderKey
     * @param bool $addStore
     *
     * @return string
     */
    protected function generatePaymentMethodKey(string $paymentProviderKey, bool $addStore = true): string
    {
        $paymentMethodKey = $paymentProviderKey . '-' . static::PAYMENT_METHOD_NAME;
        if ($addStore) {
            $paymentMethodKey .= '-' . static::STORE_NAME;
        }

        return strtolower($paymentMethodKey);
    }
}
