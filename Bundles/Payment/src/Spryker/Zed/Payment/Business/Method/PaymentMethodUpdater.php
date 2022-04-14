<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payment\Business\Method;

use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\PaymentMethodAddedTransfer;
use Generated\Shared\Transfer\PaymentMethodDeletedTransfer;
use Generated\Shared\Transfer\PaymentMethodResponseTransfer;
use Generated\Shared\Transfer\PaymentMethodTransfer;
use Generated\Shared\Transfer\PaymentProviderTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\Payment\Business\Generator\PaymentMethodKeyGeneratorInterface;
use Spryker\Zed\Payment\Business\Mapper\PaymentMethodEventMapperInterface;
use Spryker\Zed\Payment\Business\Writer\PaymentWriterInterface;
use Spryker\Zed\Payment\Dependency\Facade\PaymentToStoreReferenceFacadeInterface;
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
    protected PaymentMethodEventMapperInterface $paymentMethodEventMapper;

    /**
     * @var \Spryker\Zed\Payment\Dependency\Facade\PaymentToStoreReferenceFacadeInterface
     */
    protected PaymentToStoreReferenceFacadeInterface $storeReferenceFacade;

    /**
     * @param \Spryker\Zed\Payment\Persistence\PaymentEntityManagerInterface $paymentEntityManager
     * @param \Spryker\Zed\Payment\Business\Method\PaymentMethodStoreRelationUpdaterInterface $storeRelationUpdater
     * @param \Spryker\Zed\Payment\Persistence\PaymentRepositoryInterface $paymentRepository
     * @param \Spryker\Zed\Payment\Business\Writer\PaymentWriterInterface $paymentWriter
     * @param \Spryker\Zed\Payment\Business\Generator\PaymentMethodKeyGeneratorInterface $paymentMethodKeyGenerator
     * @param \Spryker\Zed\Payment\Business\Mapper\PaymentMethodEventMapperInterface $paymentMethodEventMapper
     * @param \Spryker\Zed\Payment\Dependency\Facade\PaymentToStoreReferenceFacadeInterface $storeReferenceFacade
     */
    public function __construct(
        PaymentEntityManagerInterface $paymentEntityManager,
        PaymentMethodStoreRelationUpdaterInterface $storeRelationUpdater,
        PaymentRepositoryInterface $paymentRepository,
        PaymentWriterInterface $paymentWriter,
        PaymentMethodKeyGeneratorInterface $paymentMethodKeyGenerator,
        PaymentMethodEventMapperInterface $paymentMethodEventMapper,
        PaymentToStoreReferenceFacadeInterface $storeReferenceFacade
    ) {
        $this->paymentEntityManager = $paymentEntityManager;
        $this->storeRelationUpdater = $storeRelationUpdater;
        $this->paymentRepository = $paymentRepository;
        $this->paymentWriter = $paymentWriter;
        $this->paymentMethodKeyGenerator = $paymentMethodKeyGenerator;
        $this->paymentMethodEventMapper = $paymentMethodEventMapper;
        $this->storeReferenceFacade = $storeReferenceFacade;
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
    public function enablePaymentMethod(PaymentMethodAddedTransfer $paymentMethodAddedTransfer): PaymentMethodTransfer
    {
        $paymentMethodTransfer = $this->paymentMethodEventMapper->mapPaymentMethodAddedTransferToPaymentMethodTransfer(
            $paymentMethodAddedTransfer,
            new PaymentMethodTransfer(),
        );

        $storeTransfer = $this->storeReferenceFacade->getStoreByStoreReference(
            $paymentMethodAddedTransfer->getMessageAttributesOrFail()->getStoreReferenceOrFail(),
        );

        $paymentMethodKey = $this->paymentMethodKeyGenerator->generatePaymentMethodKey(
            $paymentMethodTransfer->getGroupNameOrFail(),
            $paymentMethodTransfer->getLabelNameOrFail(),
            $storeTransfer->getNameOrFail(),
        );

        $paymentProviderTransfer = $this->findOrCreatePaymentProvider($paymentMethodTransfer->getGroupNameOrFail());

        $paymentMethodTransfer
            ->setName($paymentMethodTransfer->getLabelName())
            ->setIdPaymentProvider($paymentProviderTransfer->getIdPaymentProvider())
            ->setPaymentMethodKey($paymentMethodKey)
            ->setIsActive(false)
            ->setIsHidden(false);

        $existingPaymentMethodTransfer = $this->paymentRepository->findPaymentMethod($paymentMethodTransfer);
        if ($existingPaymentMethodTransfer) {
            $existingPaymentMethodTransfer->fromArray($paymentMethodTransfer->modifiedToArray());

            $paymentMethodResponseTransfer = $this->updatePaymentMethod($existingPaymentMethodTransfer);

            return $paymentMethodResponseTransfer->getPaymentMethodOrFail();
        }

        $paymentMethodResponseTransfer = $this->paymentWriter->createPaymentMethod($paymentMethodTransfer);

        return $paymentMethodResponseTransfer->getPaymentMethodOrFail();
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentMethodDeletedTransfer $paymentMethodDeletedTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentMethodTransfer
     */
    public function disablePaymentMethod(PaymentMethodDeletedTransfer $paymentMethodDeletedTransfer): PaymentMethodTransfer
    {
        $paymentMethodTransfer = $this->paymentMethodEventMapper->mapPaymentMethodDeletedTransferToPaymentMethodTransfer(
            $paymentMethodDeletedTransfer,
            new PaymentMethodTransfer(),
        );

        $paymentMethodTransfer->requireLabelName()
            ->requireGroupName();

        $storeTransfer = $this->storeReferenceFacade->getStoreByStoreReference(
            $paymentMethodDeletedTransfer->getMessageAttributesOrFail()->getStoreReferenceOrFail(),
        );

        $paymentMethodKey = $this->paymentMethodKeyGenerator->generatePaymentMethodKey(
            $paymentMethodTransfer->getGroupNameOrFail(),
            $paymentMethodTransfer->getLabelNameOrFail(),
            $storeTransfer->getNameOrFail(),
        );

        $paymentMethodTransfer->setPaymentMethodKey($paymentMethodKey);
        $this->paymentEntityManager->hidePaymentMethod($paymentMethodTransfer);

        return $paymentMethodTransfer;
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
}
