<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspInquiryManagement\Business\Writer;

use ArrayObject;
use Generated\Shared\Transfer\SspInquiryCollectionRequestTransfer;
use Generated\Shared\Transfer\SspInquiryCollectionResponseTransfer;
use Generated\Shared\Transfer\SspInquiryTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\SequenceNumber\Business\SequenceNumberFacadeInterface;
use Spryker\Zed\StateMachine\Business\StateMachineFacadeInterface;
use SprykerFeature\Zed\SspInquiryManagement\Business\Validator\SspInquiryValidatorInterface;
use SprykerFeature\Zed\SspInquiryManagement\Persistence\SspInquiryManagementEntityManagerInterface;
use SprykerFeature\Zed\SspInquiryManagement\SspInquiryManagementConfig;

class SspInquiryWriter implements SspInquiryWriterInterface
{
    use TransactionTrait;

    /**
     * @param \SprykerFeature\Zed\SspInquiryManagement\Persistence\SspInquiryManagementEntityManagerInterface $sspInquiryManagementEntityManager
     * @param \Spryker\Zed\SequenceNumber\Business\SequenceNumberFacadeInterface $sequenceNumberFacade
     * @param \Spryker\Zed\StateMachine\Business\StateMachineFacadeInterface $stateMachineFacade
     * @param \SprykerFeature\Zed\SspInquiryManagement\SspInquiryManagementConfig $sspInquiryManagementConfig
     * @param \SprykerFeature\Zed\SspInquiryManagement\Business\Validator\SspInquiryValidatorInterface $sspInquiryValidator
     * @param array<\SprykerFeature\Zed\SspInquiryManagement\Business\Hooks\PreCreate\SspInquiryPreCreateHookInterface> $preCreateHooks
     * @param array<\SprykerFeature\Zed\SspInquiryManagement\Business\Hooks\PostCreate\SspInquiryPostCreateHookInterface> $postCreateHooks
     */
    public function __construct(
        protected SspInquiryManagementEntityManagerInterface $sspInquiryManagementEntityManager,
        protected SequenceNumberFacadeInterface $sequenceNumberFacade,
        protected StateMachineFacadeInterface $stateMachineFacade,
        protected SspInquiryManagementConfig $sspInquiryManagementConfig,
        protected SspInquiryValidatorInterface $sspInquiryValidator,
        protected array $preCreateHooks,
        protected array $postCreateHooks
    ) {
    }

    /**
     * @param \Generated\Shared\Transfer\SspInquiryCollectionRequestTransfer $sspInquiryCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\SspInquiryCollectionResponseTransfer
     */
    public function createSspInquiryCollection(SspInquiryCollectionRequestTransfer $sspInquiryCollectionRequestTransfer): SspInquiryCollectionResponseTransfer
    {
         $sspInquiryCollectionResponseTransfer = new SspInquiryCollectionResponseTransfer();

        foreach ($sspInquiryCollectionRequestTransfer->getSspInquiries() as $sspInquiryTransfer) {
            $sequenceNumberSetting = $this->sspInquiryManagementConfig->getSspInquirySequenceNumberSettings(
                $sspInquiryTransfer->getStoreOrFail()->getNameOrFail(),
            );
             $sspInquiryTransfer->setReference($this->sequenceNumberFacade->generate($sequenceNumberSetting));

            $validationErrors = $this->sspInquiryValidator->validateSspInquiry($sspInquiryTransfer);

            if ($validationErrors->count()) {
                $this->addValidationErrors($validationErrors, $sspInquiryCollectionResponseTransfer);

                continue;
            }
            $this->getTransactionHandler()->handleTransaction(function () use ($sspInquiryTransfer): void {
                 $sspInquiryTransfer = $this->executePreCreateHooks($sspInquiryTransfer); // TODO: use for ssp inquiry collection instead of single sspInquiry

                 $sspInquiryTransfer = $this->sspInquiryManagementEntityManager->createSspInquiry($sspInquiryTransfer);

                 $sspInquiryTransfer = $this->executePostCreateHooks($sspInquiryTransfer);
            });

             $sspInquiryCollectionResponseTransfer->addSspInquiry($sspInquiryTransfer);
        }

        return $sspInquiryCollectionResponseTransfer;
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ErrorTransfer> $validationErrors
     * @param \Generated\Shared\Transfer\SspInquiryCollectionResponseTransfer $sspInquiryCollectionResponseTransfer
     *
     * @return void
     */
    protected function addValidationErrors(
        ArrayObject $validationErrors,
        SspInquiryCollectionResponseTransfer $sspInquiryCollectionResponseTransfer
    ): void {
        foreach ($validationErrors as $errorTransfer) {
             $sspInquiryCollectionResponseTransfer->addError($errorTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\SspInquiryTransfer $sspInquiryTransfer
     *
     * @return \Generated\Shared\Transfer\SspInquiryTransfer
     */
    protected function executePreCreateHooks(SspInquiryTransfer $sspInquiryTransfer): SspInquiryTransfer
    {
        foreach ($this->preCreateHooks as $preCreateHook) {
            if (!$preCreateHook->isApplicable($sspInquiryTransfer)) {
                continue;
            }
             $sspInquiryTransfer = $preCreateHook->execute($sspInquiryTransfer);
        }

        return $sspInquiryTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SspInquiryTransfer $sspInquiryTransfer
     *
     * @return \Generated\Shared\Transfer\SspInquiryTransfer
     */
    protected function executePostCreateHooks(SspInquiryTransfer $sspInquiryTransfer): SspInquiryTransfer
    {
        foreach ($this->postCreateHooks as $postCreateHook) {
            if (!$postCreateHook->isApplicable($sspInquiryTransfer)) {
                continue;
            }

             $sspInquiryTransfer = $postCreateHook->execute($sspInquiryTransfer);
        }

        return $sspInquiryTransfer;
    }
}
