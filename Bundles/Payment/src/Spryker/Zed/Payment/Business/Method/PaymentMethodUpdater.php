<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payment\Business\Method;

use DateTime;
use DateTimeZone;
use Generated\Shared\Transfer\MessageAttributesTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\PaymentMethodAddedTransfer;
use Generated\Shared\Transfer\PaymentMethodDeletedTransfer;
use Generated\Shared\Transfer\PaymentMethodResponseTransfer;
use Generated\Shared\Transfer\PaymentMethodTransfer;
use Generated\Shared\Transfer\PaymentProviderTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\Payment\Business\Generator\PaymentMethodKeyGeneratorInterface;
use Spryker\Zed\Payment\Business\Mapper\PaymentMethodEventMapperInterface;
use Spryker\Zed\Payment\Business\Writer\PaymentWriterInterface;
use Spryker\Zed\Payment\Dependency\Facade\PaymentToStoreFacadeInterface;
use Spryker\Zed\Payment\Persistence\PaymentEntityManagerInterface;
use Spryker\Zed\Payment\Persistence\PaymentRepositoryInterface;

class PaymentMethodUpdater implements PaymentMethodUpdaterInterface
{
    use TransactionTrait;

    /**
     * @var string
     */
    protected const MESSAGE_UPDATE_ERROR = 'It is impossible to update this payment method';

    /**
     * @var \Spryker\Zed\Payment\Persistence\PaymentEntityManagerInterface
     */
    protected $paymentEntityManager;

    /**
     * @var \Spryker\Zed\Payment\Business\Method\PaymentMethodStoreRelationUpdaterInterface
     */
    protected $storeRelationUpdater;

    /**
     * @var \Spryker\Zed\Payment\Persistence\PaymentRepositoryInterface
     */
    protected $paymentRepository;

    /**
     * @var \Spryker\Zed\Payment\Business\Writer\PaymentWriterInterface
     */
    protected $paymentWriter;

    /**
     * @var \Spryker\Zed\Payment\Business\Generator\PaymentMethodKeyGeneratorInterface
     */
    protected $paymentMethodKeyGenerator;

    /**
     * @var \Spryker\Zed\Payment\Business\Mapper\PaymentMethodEventMapperInterface
     */
    protected $paymentMethodEventMapper;

    /**
     * @var \Spryker\Zed\Payment\Dependency\Facade\PaymentToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @param \Spryker\Zed\Payment\Persistence\PaymentEntityManagerInterface $paymentEntityManager
     * @param \Spryker\Zed\Payment\Business\Method\PaymentMethodStoreRelationUpdaterInterface $storeRelationUpdater
     * @param \Spryker\Zed\Payment\Persistence\PaymentRepositoryInterface $paymentRepository
     * @param \Spryker\Zed\Payment\Business\Writer\PaymentWriterInterface $paymentWriter
     * @param \Spryker\Zed\Payment\Business\Generator\PaymentMethodKeyGeneratorInterface $paymentMethodKeyGenerator
     * @param \Spryker\Zed\Payment\Business\Mapper\PaymentMethodEventMapperInterface $paymentMethodEventMapper
     * @param \Spryker\Zed\Payment\Dependency\Facade\PaymentToStoreFacadeInterface $storeFacade
     */
    public function __construct(
        PaymentEntityManagerInterface $paymentEntityManager,
        PaymentMethodStoreRelationUpdaterInterface $storeRelationUpdater,
        PaymentRepositoryInterface $paymentRepository,
        PaymentWriterInterface $paymentWriter,
        PaymentMethodKeyGeneratorInterface $paymentMethodKeyGenerator,
        PaymentMethodEventMapperInterface $paymentMethodEventMapper,
        PaymentToStoreFacadeInterface $storeFacade
    ) {
        $this->paymentEntityManager = $paymentEntityManager;
        $this->storeRelationUpdater = $storeRelationUpdater;
        $this->paymentRepository = $paymentRepository;
        $this->paymentWriter = $paymentWriter;
        $this->paymentMethodKeyGenerator = $paymentMethodKeyGenerator;
        $this->paymentMethodEventMapper = $paymentMethodEventMapper;
        $this->storeFacade = $storeFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentMethodTransfer $paymentMethodTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentMethodResponseTransfer
     */
    public function updatePaymentMethod(
        PaymentMethodTransfer $paymentMethodTransfer
    ): PaymentMethodResponseTransfer {
        return $this->getTransactionHandler()->handleTransaction(function () use ($paymentMethodTransfer) {
            return $this->executeUpdatePaymentMethodTransaction($paymentMethodTransfer);
        });
    }

    /**
     * Business requirement - by default payment method is not active and should be activated manually.
     *
     * @param \Generated\Shared\Transfer\PaymentMethodAddedTransfer $paymentMethodAddedTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentMethodTransfer
     */
    public function enableForeignPaymentMethod(PaymentMethodAddedTransfer $paymentMethodAddedTransfer): PaymentMethodTransfer
    {
        $paymentMethodTransfer = $this->paymentMethodEventMapper->mapPaymentMethodAddedTransferToPaymentMethodTransfer(
            $paymentMethodAddedTransfer,
            new PaymentMethodTransfer(),
        );

        $messageAtributes = $paymentMethodAddedTransfer->getMessageAttributesOrFail();
        $storeTransfer = $this->storeFacade->getStoreByStoreReference(
            $paymentMethodAddedTransfer->getMessageAttributesOrFail()->getStoreReferenceOrFail(),
        );
        $existingPaymentMethodTransfer = $this->findExistentPaymentMethod(
            $paymentMethodTransfer,
            $storeTransfer,
        );

        if ($existingPaymentMethodTransfer && !$this->canSavePaymentMethod($messageAtributes, $existingPaymentMethodTransfer)) {
            return $paymentMethodTransfer;
        }

        $paymentMethodTransfer = $this->preparePaymentMethodToSave(
            $paymentMethodTransfer,
            $existingPaymentMethodTransfer,
            $messageAtributes,
        );

        $paymentMethodTransfer->setIsHidden(false);

        return $this->savePaymentMethod($paymentMethodTransfer, $existingPaymentMethodTransfer, $storeTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentMethodDeletedTransfer $paymentMethodDeletedTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentMethodTransfer
     */
    public function disableForeignPaymentMethod(PaymentMethodDeletedTransfer $paymentMethodDeletedTransfer): PaymentMethodTransfer
    {
        $paymentMethodTransfer = $this->paymentMethodEventMapper->mapPaymentMethodDeletedTransferToPaymentMethodTransfer(
            $paymentMethodDeletedTransfer,
            new PaymentMethodTransfer(),
        );

        $paymentMethodTransfer->requireLabelName()
            ->requireGroupName();

        $messageAtributes = $paymentMethodDeletedTransfer->getMessageAttributesOrFail();
        $storeTransfer = $this->storeFacade->getStoreByStoreReference(
            $paymentMethodDeletedTransfer->getMessageAttributesOrFail()->getStoreReferenceOrFail(),
        );
        $existingPaymentMethodTransfer = $this->findExistentPaymentMethod(
            $paymentMethodTransfer,
            $storeTransfer,
        );

        if ($existingPaymentMethodTransfer && !$this->canSavePaymentMethod($messageAtributes, $existingPaymentMethodTransfer)) {
            return $paymentMethodTransfer;
        }

        $paymentMethodTransfer = $this->preparePaymentMethodToSave(
            $paymentMethodTransfer,
            $existingPaymentMethodTransfer,
            $messageAtributes,
        );

        $paymentMethodTransfer->setIsHidden(true);

        return $this->savePaymentMethod($paymentMethodTransfer, $existingPaymentMethodTransfer, $storeTransfer);
    }

    /**
     * A Payment Method can be saved if the message attributes has a timestamp and it's newer than
     * the last message timestamp stored on the existing payment method record. That behavior
     * prevents out-of-order or duplicated messages be overlapped.
     *
     * @param \Generated\Shared\Transfer\MessageAttributesTransfer $messageAttributesTransfer
     * @param \Generated\Shared\Transfer\PaymentMethodTransfer $existingPaymentMethodTransfer
     *
     * @return bool
     */
    protected function canSavePaymentMethod(
        MessageAttributesTransfer $messageAttributesTransfer,
        PaymentMethodTransfer $existingPaymentMethodTransfer
    ): bool {
        $currentMessageTimestamp = $messageAttributesTransfer->getTimestamp();

        if (!$currentMessageTimestamp) {
            return true;
        }

        if (!$existingPaymentMethodTransfer->getLastMessageTimestamp()) {
            return true;
        }

        $currentMessageDatetime = new DateTime($currentMessageTimestamp);
        $lastMessageDatetime = new DateTime(
            $existingPaymentMethodTransfer->getLastMessageTimestamp(),
        );

        return $lastMessageDatetime < $currentMessageDatetime;
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentMethodTransfer $paymentMethodTransfer
     * @param \Generated\Shared\Transfer\PaymentMethodTransfer|null $existingPaymentMethodTransfer
     * @param \Generated\Shared\Transfer\MessageAttributesTransfer $messageAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentMethodTransfer
     */
    protected function preparePaymentMethodToSave(
        PaymentMethodTransfer $paymentMethodTransfer,
        ?PaymentMethodTransfer $existingPaymentMethodTransfer,
        MessageAttributesTransfer $messageAttributesTransfer
    ): PaymentMethodTransfer {
        $messageTimestamp = $messageAttributesTransfer->getTimestamp();

        if (!$messageTimestamp) {
            trigger_error(
                'The MessageAttributes.Timestamp field is empty and will default to current time. This field will be required in a future version.',
                E_USER_DEPRECATED,
            );

            $messageTimestamp = $this->generateNowTimestamp();
        }

        if (!$existingPaymentMethodTransfer) {
            $storeReference = $messageAttributesTransfer->getStoreReferenceOrFail();
            $storeTransfer = $this->storeFacade->getStoreByStoreReference($storeReference);

            $paymentMethodKey = $this->paymentMethodKeyGenerator->generatePaymentMethodKey(
                $paymentMethodTransfer->getGroupNameOrFail(),
                $paymentMethodTransfer->getLabelNameOrFail(),
                $storeTransfer->getNameOrFail(),
            );

            $paymentProviderTransfer = $this->findOrCreatePaymentProvider($paymentMethodTransfer->getGroupNameOrFail());

            $paymentMethodTransfer
                ->setName($paymentMethodTransfer->getLabelName())
                ->setIdPaymentProvider($paymentProviderTransfer->getIdPaymentProvider())
                ->setPaymentMethodKey($paymentMethodKey);
        }

        $paymentMethodTransfer
            ->setIsActive(false)
            ->setIsForeign(true)
            ->setLastMessageTimestamp($messageTimestamp);

        return $paymentMethodTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentMethodTransfer $paymentMethodTransfer
     * @param \Generated\Shared\Transfer\PaymentMethodTransfer|null $existingPaymentMethodTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentMethodTransfer
     */
    protected function savePaymentMethod(
        PaymentMethodTransfer $paymentMethodTransfer,
        ?PaymentMethodTransfer $existingPaymentMethodTransfer,
        StoreTransfer $storeTransfer
    ): PaymentMethodTransfer {
        if ($existingPaymentMethodTransfer) {
            $existingPaymentMethodTransfer->fromArray($paymentMethodTransfer->modifiedToArray());

            $existingPaymentMethodTransfer->getStoreRelation()
                ->addStores($storeTransfer)
                ->addIdStores($storeTransfer->getIdStore());

            $paymentMethodResponseTransfer = $this->updatePaymentMethod($existingPaymentMethodTransfer);
        } else {
            $storeRelationTransfer = $paymentMethodTransfer->getStoreRelation() ?? new StoreRelationTransfer();
            $storeRelationTransfer
                ->addStores($storeTransfer)
                ->addIdStores($storeTransfer->getIdStore());

            $paymentMethodTransfer->setStoreRelation($storeRelationTransfer);

            $paymentMethodResponseTransfer = $this->paymentWriter->createPaymentMethod($paymentMethodTransfer);
        }

        return $paymentMethodResponseTransfer->getPaymentMethodOrFail();
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentMethodTransfer $paymentMethodTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentMethodResponseTransfer
     */
    protected function executeUpdatePaymentMethodTransaction(
        PaymentMethodTransfer $paymentMethodTransfer
    ): PaymentMethodResponseTransfer {
        $paymentMethodTransfer->requireIdPaymentMethod()
            ->requireStoreRelation();

        $storeRelationTransfer = $paymentMethodTransfer->getStoreRelation()
            ->setIdEntity($paymentMethodTransfer->getIdPaymentMethod());

        $paymentMethodTransfer = $this->paymentEntityManager
            ->updatePaymentMethod($paymentMethodTransfer);

        if ($paymentMethodTransfer === null) {
            return (new PaymentMethodResponseTransfer())
                ->setIsSuccessful(false)
                ->addMessage($this->getErrorMessageTransfer(static::MESSAGE_UPDATE_ERROR));
        }

        $this->storeRelationUpdater->update($storeRelationTransfer);

        return (new PaymentMethodResponseTransfer())
            ->setIsSuccessful(true)
            ->setPaymentMethod($paymentMethodTransfer);
    }

    /**
     * @param string $paymentProviderName
     *
     * @return \Generated\Shared\Transfer\PaymentProviderTransfer
     */
    protected function findOrCreatePaymentProvider(string $paymentProviderName): PaymentProviderTransfer
    {
        $foundPaymentProviderTransfer = $this->paymentRepository->findPaymentProviderByKey(
            $paymentProviderName,
        );

        if ($foundPaymentProviderTransfer) {
            return $foundPaymentProviderTransfer;
        }

        $paymentProviderTransfer = (new PaymentProviderTransfer())
            ->setPaymentProviderKey($paymentProviderName)
            ->setName($paymentProviderName);
        $paymentProviderResponseTransfer = $this->paymentWriter->createPaymentProvider($paymentProviderTransfer);

        return $paymentProviderResponseTransfer->getPaymentProvider() ?? $paymentProviderTransfer;
    }

    /**
     * @param string $message
     *
     * @return \Generated\Shared\Transfer\MessageTransfer
     */
    protected function getErrorMessageTransfer(string $message): MessageTransfer
    {
        return (new MessageTransfer())->setValue($message);
    }

    /**
     * @return string
     */
    protected function generateNowTimestamp(): string
    {
        return (new DateTime('now', new DateTimeZone('UTC')))->format('Y-m-d\TH:i:s.u');
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentMethodTransfer $paymentMethodTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentMethodTransfer|null
     */
    protected function findExistentPaymentMethod(
        PaymentMethodTransfer $paymentMethodTransfer,
        StoreTransfer $storeTransfer
    ): ?PaymentMethodTransfer {
        $foundPaymentProviderTransfer = $this->paymentRepository->findPaymentProviderByKey(
            $paymentMethodTransfer->getGroupNameOrFail(),
        );

        if (!$foundPaymentProviderTransfer) {
            return null;
        }

        $paymentMethodKey = $this->paymentMethodKeyGenerator->generatePaymentMethodKey(
            $paymentMethodTransfer->getGroupNameOrFail(),
            $paymentMethodTransfer->getLabelNameOrFail(),
            $storeTransfer->getNameOrFail(),
        );

        $filterPaymentMethodTransfer = (new PaymentMethodTransfer())
            ->setPaymentMethodKey($paymentMethodKey);

        return $this->paymentRepository->findPaymentMethod($filterPaymentMethodTransfer);
    }
}
