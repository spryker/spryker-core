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
use Generated\Shared\Transfer\AddPaymentMethodTransfer;
use Generated\Shared\Transfer\DeletePaymentMethodTransfer;
use Generated\Shared\Transfer\MessageAttributesTransfer;
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
 *
 * @method \Spryker\Zed\Payment\Business\PaymentFacadeInterface getFacade(?string $moduleName = NULL)
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
     * @param \Generated\Shared\Transfer\DeletePaymentMethodTransfer $deletePaymentMethodTransfer
     *
     * @return \Generated\Shared\Transfer\DeletePaymentMethodTransfer
     */
    public function mapPaymentMethodTransferToDeletePaymentMethodTransfer(
        PaymentMethodTransfer $paymentMethodTransfer,
        DeletePaymentMethodTransfer $deletePaymentMethodTransfer
    ): DeletePaymentMethodTransfer {
        $deletePaymentMethodTransfer
            ->setName($paymentMethodTransfer->getLabelName())
            ->setProviderName($paymentMethodTransfer->getGroupName());

        return $deletePaymentMethodTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentMethodTransfer $paymentMethodTransfer
     * @param \Generated\Shared\Transfer\AddPaymentMethodTransfer $addPaymentMethodTransfer
     *
     * @return \Generated\Shared\Transfer\AddPaymentMethodTransfer
     */
    public function mapPaymentMethodTransferToAddPaymentMethodTransfer(
        PaymentMethodTransfer $paymentMethodTransfer,
        AddPaymentMethodTransfer $addPaymentMethodTransfer
    ): AddPaymentMethodTransfer {
        $addPaymentMethodTransfer
            ->setName($paymentMethodTransfer->getLabelName())
            ->setProviderName($paymentMethodTransfer->getGroupName())
            ->setPaymentAuthorizationEndpoint($paymentMethodTransfer->getPaymentAuthorizationEndpoint());

        return $addPaymentMethodTransfer;
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
     * @return \Generated\Shared\Transfer\DeletePaymentMethodTransfer
     */
    public function haveDeletePaymentMethodTransferWithTimestamp(
        PaymentProviderTransfer $paymentProviderTransfer,
        string $timestamp
    ): DeletePaymentMethodTransfer {
        return $this->haveDeletePaymentMethodTransfer([
            DeletePaymentMethodTransfer::NAME => static::PAYMENT_METHOD_NAME,
            DeletePaymentMethodTransfer::PAYMENT_AUTHORIZATION_ENDPOINT => static::PAYMENT_REDIRECT_URL,
            DeletePaymentMethodTransfer::PROVIDER_NAME => $paymentProviderTransfer->getPaymentProviderKey(),
            DeletePaymentMethodTransfer::MESSAGE_ATTRIBUTES => [
                MessageAttributesTransfer::STORE_REFERENCE => static::STORE_REFERENCE,
                MessageAttributesTransfer::TIMESTAMP => $timestamp,
            ],
        ]);
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentProviderTransfer|null $paymentProviderTransfer
     *
     * @return \Generated\Shared\Transfer\DeletePaymentMethodTransfer
     */
    public function haveDeletePaymentMethodTransferWithoutTimestamp(
        ?PaymentProviderTransfer $paymentProviderTransfer = null
    ): DeletePaymentMethodTransfer {
        $providerKey = $paymentProviderTransfer ? $paymentProviderTransfer->getPaymentProviderKey() : static::PAYMENT_PROVIDER_KEY;

        return $this->haveDeletePaymentMethodTransfer([
            DeletePaymentMethodTransfer::NAME => static::PAYMENT_METHOD_NAME,
            DeletePaymentMethodTransfer::PAYMENT_AUTHORIZATION_ENDPOINT => static::PAYMENT_REDIRECT_URL,
            DeletePaymentMethodTransfer::PROVIDER_NAME => $providerKey,
            DeletePaymentMethodTransfer::MESSAGE_ATTRIBUTES => [
                MessageAttributesTransfer::STORE_REFERENCE => static::STORE_REFERENCE,
            ],
        ]);
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentProviderTransfer $paymentProviderTransfer
     * @param string $timestamp
     *
     * @return \Generated\Shared\Transfer\AddPaymentMethodTransfer
     */
    public function haveAddPaymentMethodTransferWithTimestamp(
        PaymentProviderTransfer $paymentProviderTransfer,
        string $timestamp
    ): AddPaymentMethodTransfer {
        return $this->haveAddPaymentMethodTransfer([
            AddPaymentMethodTransfer::NAME => static::PAYMENT_METHOD_NAME,
            AddPaymentMethodTransfer::PAYMENT_AUTHORIZATION_ENDPOINT => static::PAYMENT_REDIRECT_URL,
            AddPaymentMethodTransfer::PROVIDER_NAME => $paymentProviderTransfer->getPaymentProviderKey(),
            AddPaymentMethodTransfer::MESSAGE_ATTRIBUTES => [
                MessageAttributesTransfer::STORE_REFERENCE => static::STORE_REFERENCE,
                MessageAttributesTransfer::TIMESTAMP => $timestamp,
            ],
        ]);
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentProviderTransfer $paymentProviderTransfer
     *
     * @return \Generated\Shared\Transfer\AddPaymentMethodTransfer
     */
    public function haveAddPaymentMethodTransferWithoutTimestamp(
        PaymentProviderTransfer $paymentProviderTransfer
    ): AddPaymentMethodTransfer {
        return $this->haveAddPaymentMethodTransfer([
            AddPaymentMethodTransfer::NAME => static::PAYMENT_METHOD_NAME,
            AddPaymentMethodTransfer::PAYMENT_AUTHORIZATION_ENDPOINT => static::PAYMENT_REDIRECT_URL,
            AddPaymentMethodTransfer::PROVIDER_NAME => $paymentProviderTransfer->getPaymentProviderKey(), DeletePaymentMethodTransfer::MESSAGE_ATTRIBUTES => [
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
     * @param \Generated\Shared\Transfer\DeletePaymentMethodTransfer $deletePaymentMethodTransfer
     * @param bool $addStore
     *
     * @return void
     */
    public function assertDisabledPaymentMethodWasCreatedWithSoftDeletion(
        DeletePaymentMethodTransfer $deletePaymentMethodTransfer,
        bool $addStore = true
    ): void {
        $filterPaymentMethodTransfer = (new PaymentMethodTransfer())
            ->setPaymentMethodKey(
                $this->generatePaymentMethodKey(
                    $deletePaymentMethodTransfer->getProviderName(),
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
            $deletePaymentMethodTransfer->getName(),
            $createdPaymentMethodTransfer->getName(),
            'The disabled Payment Method must have the same name of the original Payment Method Deleted Transfer',
        );

        $this->assertSame(
            $deletePaymentMethodTransfer->getProviderName(),
            $createdPaymentMethodTransfer->getGroupName(),
            'The disabled Payment Method must have the same provider name of the original Payment Method Deleted Transfer',
        );
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentMethodTransfer $paymentMethodTransfer
     *
     * @return void
     */
    public function assertDisabledPaymentMethodDidNotChange(
        PaymentMethodTransfer $paymentMethodTransfer
    ): void {
        $filterPaymentMethodTransfer = (new PaymentMethodTransfer())->setIdPaymentMethod(
            $paymentMethodTransfer->getIdPaymentMethod(),
        );

        $paymentMethodFound = $this->findPaymentMethod($filterPaymentMethodTransfer);

        $this->assertTrue(
            $paymentMethodFound->getIsHidden(),
            'The disabled Payment Method must remain hidden',
        );

        $this->assertEquals(
            str_replace('T', ' ', $paymentMethodTransfer->getLastMessageTimestamp()),
            $paymentMethodFound->getLastMessageTimestamp(),
            'The disabled Payment Method\'s last message timestamp must remain the same',
        );
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentMethodTransfer $paymentMethodTransfer
     * @param \Generated\Shared\Transfer\AddPaymentMethodTransfer $addPaymentMethodTransfer
     *
     * @return void
     */
    public function assertDisabledPaymentMethodWasEnabledAndTimestampChanged(
        PaymentMethodTransfer $paymentMethodTransfer,
        AddPaymentMethodTransfer $addPaymentMethodTransfer
    ): void {
        $filterPaymentMethodTransfer = (new PaymentMethodTransfer())->setIdPaymentMethod(
            $paymentMethodTransfer->getIdPaymentMethod(),
        );

        $paymentMethodFound = $this->findPaymentMethod($filterPaymentMethodTransfer);

        $this->assertFalse(
            $paymentMethodFound->getIsHidden(),
            'The disabled Payment Method must have been enabled',
        );

        $this->assertEquals(
            str_replace('T', ' ', $addPaymentMethodTransfer->getMessageAttributes()->getTimestamp()),
            $paymentMethodFound->getLastMessageTimestamp(),
            'The disabled Payment Method\'s last message timestamp equals to message timestamp',
        );
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentMethodTransfer $paymentMethodTransfer
     *
     * @return void
     */
    public function assertDisabledPaymentMethodWasEnabledAndTimestampWasUpdated(
        PaymentMethodTransfer $paymentMethodTransfer
    ): void {
        $filterPaymentMethodTransfer = (new PaymentMethodTransfer())->setIdPaymentMethod(
            $paymentMethodTransfer->getIdPaymentMethod(),
        );

        $paymentMethodFound = $this->findPaymentMethod($filterPaymentMethodTransfer);

        $this->assertFalse(
            $paymentMethodFound->getIsHidden(),
            'The disabled Payment Method must have been enabled',
        );

        $this->assertNotEquals(
            str_replace('T', ' ', $paymentMethodTransfer->getLastMessageTimestamp()),
            $paymentMethodFound->getLastMessageTimestamp(),
            'The disabled Payment Method\'s last message timestamp must had to be updated',
        );

        $disabledPaymentMethodDatetime = new DateTime(
            $paymentMethodTransfer->getLastMessageTimestamp(),
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
     * @param \Generated\Shared\Transfer\PaymentMethodTransfer $paymentMethodTransfer
     *
     * @return void
     */
    public function assertEnabledPaymentMethodDidNotChange(
        PaymentMethodTransfer $paymentMethodTransfer
    ): void {
        $filterPaymentMethodTransfer = (new PaymentMethodTransfer())->setIdPaymentMethod(
            $paymentMethodTransfer->getIdPaymentMethod(),
        );

        $paymentMethodFound = $this->findPaymentMethod($filterPaymentMethodTransfer);

        $this->assertFalse(
            $paymentMethodFound->getIsHidden(),
            'The enabled Payment Method must remain NOT hidden',
        );

        $this->assertEquals(
            str_replace('T', ' ', $paymentMethodTransfer->getLastMessageTimestamp()),
            $paymentMethodFound->getLastMessageTimestamp(),
            'The enabled Payment Method\'s last message timestamp must remain the same',
        );
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentMethodTransfer $paymentMethodTransfer
     * @param \Generated\Shared\Transfer\DeletePaymentMethodTransfer $deletePaymentMethodTransfer
     *
     * @return void
     */
    public function assertEnabledPaymentMethodWasDisabledAndTimestampChanged(
        PaymentMethodTransfer $paymentMethodTransfer,
        DeletePaymentMethodTransfer $deletePaymentMethodTransfer
    ): void {
        $filterPaymentMethodTransfer = (new PaymentMethodTransfer())->setIdPaymentMethod(
            $paymentMethodTransfer->getIdPaymentMethod(),
        );

        $paymentMethodFound = $this->findPaymentMethod($filterPaymentMethodTransfer);

        $this->assertTrue(
            $paymentMethodFound->getIsHidden(),
            'The enabled Payment Method must have been disabled',
        );

        $this->assertEquals(
            str_replace('T', ' ', $deletePaymentMethodTransfer->getMessageAttributes()->getTimestamp()),
            $paymentMethodFound->getLastMessageTimestamp(),
            'The enabled Payment Method\'s last message timestamp equals to message timestamp',
        );
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentMethodTransfer $paymentMethodTransfer
     * @param \Generated\Shared\Transfer\DeletePaymentMethodTransfer $deletePaymentMethodTransfer
     *
     * @return void
     */
    public function assertEnabledPaymentMethodWasDisabledAndTimestampWasUpdated(
        PaymentMethodTransfer $paymentMethodTransfer,
        DeletePaymentMethodTransfer $deletePaymentMethodTransfer
    ): void {
        $filterPaymentMethodTransfer = (new PaymentMethodTransfer())->setIdPaymentMethod(
            $paymentMethodTransfer->getIdPaymentMethod(),
        );

        $paymentMethodFound = $this->findPaymentMethod($filterPaymentMethodTransfer);

        $this->assertTrue(
            $paymentMethodFound->getIsHidden(),
            'The enabled Payment Method must have been disabled',
        );

        $this->assertNotEquals(
            str_replace('T', ' ', $paymentMethodTransfer->getLastMessageTimestamp()),
            $paymentMethodFound->getLastMessageTimestamp(),
            'The enabled Payment Method\'s last message timestamp must had to be updated',
        );

        $enabledPaymentMethodDatetime = new DateTime(
            $paymentMethodTransfer->getLastMessageTimestamp(),
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
     * @param \Generated\Shared\Transfer\PaymentMethodTransfer $paymentMethodTransfer
     * @param string $providerName *
     * @param bool $addStore
     *
     * @return void
     */
    public function assertRightPaymentMethodWasUpdated(
        PaymentMethodTransfer $paymentMethodTransfer,
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
            $paymentMethodTransfer->getIsHidden(),
            $paymentMethodFound->getIsHidden(),
            'The existent Payment Method must remain unchanged',
        );

        $this->assertNotEquals(
            $paymentMethodTransfer->getIdPaymentMethod(),
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
