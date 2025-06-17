<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Business\Inquiry\Writer;

use ArrayObject;
use Generated\Shared\Transfer\SspInquiryCollectionRequestTransfer;
use Generated\Shared\Transfer\SspInquiryCollectionResponseTransfer;
use Generated\Shared\Transfer\SspInquiryTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\SequenceNumber\Business\SequenceNumberFacadeInterface;
use Spryker\Zed\StateMachine\Business\StateMachineFacadeInterface;
use SprykerFeature\Zed\SelfServicePortal\Business\Inquiry\Validator\SspInquiryValidatorInterface;
use SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalEntityManagerInterface;
use SprykerFeature\Zed\SelfServicePortal\SelfServicePortalConfig;

class SspInquiryWriter implements SspInquiryWriterInterface
{
    use TransactionTrait;

    /**
     * @param \SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalEntityManagerInterface $selfServicePortalEntityManager
     * @param \Spryker\Zed\SequenceNumber\Business\SequenceNumberFacadeInterface $sequenceNumberFacade
     * @param \Spryker\Zed\StateMachine\Business\StateMachineFacadeInterface $stateMachineFacade
     * @param \SprykerFeature\Zed\SelfServicePortal\SelfServicePortalConfig $selfServicePortalConfig
     * @param \SprykerFeature\Zed\SelfServicePortal\Business\Inquiry\Validator\SspInquiryValidatorInterface $sspInquiryValidator
     * @param array<\SprykerFeature\Zed\SelfServicePortal\Business\Inquiry\Hooks\PreCreate\SspInquiryPreCreateHookInterface> $preCreateHooks
     * @param array<\SprykerFeature\Zed\SelfServicePortal\Business\Inquiry\Hooks\PostCreate\SspInquiryPostCreateHookInterface> $postCreateHooks
     */
    public function __construct(
        protected SelfServicePortalEntityManagerInterface $selfServicePortalEntityManager,
        protected SequenceNumberFacadeInterface $sequenceNumberFacade,
        protected StateMachineFacadeInterface $stateMachineFacade,
        protected SelfServicePortalConfig $selfServicePortalConfig,
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
            $sequenceNumberSetting = $this->selfServicePortalConfig->getInquirySequenceNumberSettings(
                $sspInquiryTransfer->getStoreOrFail()->getNameOrFail(),
            );
             $sspInquiryTransfer->setReference($this->sequenceNumberFacade->generate($sequenceNumberSetting));

            $validationErrors = $this->sspInquiryValidator->validateSspInquiry($sspInquiryTransfer);

            if ($validationErrors->count()) {
                $this->addValidationErrors($validationErrors, $sspInquiryCollectionResponseTransfer);

                continue;
            }
            $this->getTransactionHandler()->handleTransaction(function () use ($sspInquiryTransfer): void {
                 $sspInquiryTransfer = $this->executePreCreateHooks($sspInquiryTransfer);

                 $sspInquiryTransfer = $this->selfServicePortalEntityManager->createSspInquiry($sspInquiryTransfer);

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
