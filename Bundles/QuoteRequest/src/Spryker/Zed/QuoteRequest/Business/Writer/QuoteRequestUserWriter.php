<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteRequest\Business\Writer;

use ArrayObject;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\QuoteRequestFilterTransfer;
use Generated\Shared\Transfer\QuoteRequestResponseTransfer;
use Generated\Shared\Transfer\QuoteRequestTransfer;
use Generated\Shared\Transfer\QuoteRequestVersionTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\QuoteRequest\QuoteRequestConfig as SharedQuoteRequestConfig;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\QuoteRequest\Business\Reader\QuoteRequestReaderInterface;
use Spryker\Zed\QuoteRequest\Business\ReferenceGenerator\QuoteRequestReferenceGeneratorInterface;
use Spryker\Zed\QuoteRequest\Business\Sanitizer\QuoteRequestVersionSanitizerInterface;
use Spryker\Zed\QuoteRequest\Business\Status\QuoteRequestUserStatusInterface;
use Spryker\Zed\QuoteRequest\Persistence\QuoteRequestEntityManagerInterface;
use Spryker\Zed\QuoteRequest\QuoteRequestConfig;

class QuoteRequestUserWriter implements QuoteRequestUserWriterInterface
{
    use TransactionTrait;

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_QUOTE_REQUEST_COMPANY_USER_NOT_FOUND = 'quote_request.validation.error.company_user_not_found';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_QUOTE_REQUEST_WRONG_STATUS = 'quote_request.validation.error.wrong_status';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_QUOTE_REQUEST_EMPTY_QUOTE_ITEMS = 'quote_request.validation.error.empty_quote_items';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_WRONG_QUOTE_REQUEST_VALID_UNTIL = 'quote_request.update.validation.error.wrong_valid_until';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_CONCURRENT_CUSTOMERS = 'quote_request.update.validation.concurrent';

    /**
     * @var \Spryker\Zed\QuoteRequest\QuoteRequestConfig
     */
    protected $quoteRequestConfig;

    /**
     * @var \Spryker\Zed\QuoteRequest\Persistence\QuoteRequestEntityManagerInterface
     */
    protected $quoteRequestEntityManager;

    /**
     * @var \Spryker\Zed\QuoteRequest\Business\Reader\QuoteRequestReaderInterface
     */
    protected $quoteRequestReader;

    /**
     * @var \Spryker\Zed\QuoteRequest\Business\ReferenceGenerator\QuoteRequestReferenceGeneratorInterface
     */
    protected $quoteRequestReferenceGenerator;

    /**
     * @var \Spryker\Zed\QuoteRequest\Business\Sanitizer\QuoteRequestVersionSanitizerInterface
     */
    protected $quoteRequestVersionSanitizer;

    /**
     * @var \Spryker\Zed\QuoteRequest\Business\Status\QuoteRequestUserStatusInterface
     */
    protected $quoteRequestUserStatus;

    /**
     * @var array<\Spryker\Zed\QuoteRequestExtension\Dependency\Plugin\QuoteRequestUserValidatorPluginInterface>
     */
    protected $quoteRequestUserValidatorPlugins;

    /**
     * @param \Spryker\Zed\QuoteRequest\QuoteRequestConfig $quoteRequestConfig
     * @param \Spryker\Zed\QuoteRequest\Persistence\QuoteRequestEntityManagerInterface $quoteRequestEntityManager
     * @param \Spryker\Zed\QuoteRequest\Business\Reader\QuoteRequestReaderInterface $quoteRequestReader
     * @param \Spryker\Zed\QuoteRequest\Business\ReferenceGenerator\QuoteRequestReferenceGeneratorInterface $quoteRequestReferenceGenerator
     * @param \Spryker\Zed\QuoteRequest\Business\Sanitizer\QuoteRequestVersionSanitizerInterface $quoteRequestVersionSanitizer
     * @param \Spryker\Zed\QuoteRequest\Business\Status\QuoteRequestUserStatusInterface $quoteRequestUserStatus
     * @param array<\Spryker\Zed\QuoteRequestExtension\Dependency\Plugin\QuoteRequestUserValidatorPluginInterface> $quoteRequestUserValidatorPlugins
     */
    public function __construct(
        QuoteRequestConfig $quoteRequestConfig,
        QuoteRequestEntityManagerInterface $quoteRequestEntityManager,
        QuoteRequestReaderInterface $quoteRequestReader,
        QuoteRequestReferenceGeneratorInterface $quoteRequestReferenceGenerator,
        QuoteRequestVersionSanitizerInterface $quoteRequestVersionSanitizer,
        QuoteRequestUserStatusInterface $quoteRequestUserStatus,
        array $quoteRequestUserValidatorPlugins
    ) {
        $this->quoteRequestConfig = $quoteRequestConfig;
        $this->quoteRequestEntityManager = $quoteRequestEntityManager;
        $this->quoteRequestReader = $quoteRequestReader;
        $this->quoteRequestReferenceGenerator = $quoteRequestReferenceGenerator;
        $this->quoteRequestVersionSanitizer = $quoteRequestVersionSanitizer;
        $this->quoteRequestUserStatus = $quoteRequestUserStatus;
        $this->quoteRequestUserValidatorPlugins = $quoteRequestUserValidatorPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestResponseTransfer
     */
    public function createQuoteRequest(QuoteRequestTransfer $quoteRequestTransfer): QuoteRequestResponseTransfer
    {
        return $this->getTransactionHandler()->handleTransaction(function () use ($quoteRequestTransfer) {
            return $this->executeCreateQuoteRequestTransaction($quoteRequestTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestResponseTransfer
     */
    public function updateQuoteRequest(QuoteRequestTransfer $quoteRequestTransfer): QuoteRequestResponseTransfer
    {
        return $this->getTransactionHandler()->handleTransaction(function () use ($quoteRequestTransfer) {
            return $this->executeUpdateQuoteRequestTransaction($quoteRequestTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestFilterTransfer $quoteRequestFilterTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestResponseTransfer
     */
    public function reviseQuoteRequest(QuoteRequestFilterTransfer $quoteRequestFilterTransfer): QuoteRequestResponseTransfer
    {
        return $this->getTransactionHandler()->handleTransaction(function () use ($quoteRequestFilterTransfer) {
            return $this->executeReviseQuoteRequestTransaction($quoteRequestFilterTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestResponseTransfer
     */
    protected function executeCreateQuoteRequestTransaction(QuoteRequestTransfer $quoteRequestTransfer): QuoteRequestResponseTransfer
    {
        $quoteRequestTransfer->requireCompanyUser()
            ->getCompanyUser()
            ->requireIdCompanyUser();

        $customerReference = $this->quoteRequestReader->findCustomerReference($quoteRequestTransfer->getCompanyUser());

        if (!$customerReference) {
            return $this->getErrorResponse(static::GLOSSARY_KEY_QUOTE_REQUEST_COMPANY_USER_NOT_FOUND);
        }

        $quoteRequestResponseTransfer = $this->executeQuoteRequestUserValidatorPlugins($quoteRequestTransfer);
        if (!$quoteRequestResponseTransfer->getIsSuccessful()) {
            return $quoteRequestResponseTransfer;
        }

        $quoteRequestReference = $this->quoteRequestReferenceGenerator->generateQuoteRequestReference($customerReference);

        $quoteRequestTransfer
            ->setQuoteRequestReference($quoteRequestReference)
            ->setStatus(SharedQuoteRequestConfig::STATUS_IN_PROGRESS)
            ->setIsLatestVersionVisible(false);

        $quoteRequestTransfer = $this->quoteRequestEntityManager->createQuoteRequest($quoteRequestTransfer);

        $quoteRequestVersionTransfer = $this->createQuoteRequestVersionTransfer($quoteRequestTransfer);
        $quoteRequestTransfer->setLatestVersion($quoteRequestVersionTransfer);
        $quoteRequestTransfer->setQuoteRequestVersions(new ArrayObject([$quoteRequestTransfer->getLatestVersionOrFail()]));

        return $this->createSuccessfulResponse($quoteRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestResponseTransfer
     */
    protected function executeUpdateQuoteRequestTransaction(QuoteRequestTransfer $quoteRequestTransfer): QuoteRequestResponseTransfer
    {
        $quoteRequestTransfer->requireQuoteRequestReference()
            ->requireCompanyUser()
            ->getCompanyUser()
            ->requireIdCompanyUser();

        $quoteRequestFilterTransfer = (new QuoteRequestFilterTransfer())
            ->setQuoteRequestReference($quoteRequestTransfer->getQuoteRequestReference())
            ->setIdCompanyUser($quoteRequestTransfer->getCompanyUser()->getIdCompanyUser())
            ->setWithHidden(true);

        $quoteRequestResponseTransfer = $this->quoteRequestReader->getQuoteRequest($quoteRequestFilterTransfer);

        if (!$quoteRequestResponseTransfer->getIsSuccessful()) {
            return $quoteRequestResponseTransfer;
        }

        if (!$this->quoteRequestUserStatus->isQuoteRequestEditable($quoteRequestResponseTransfer->getQuoteRequest())) {
            return $this->getErrorResponse(static::GLOSSARY_KEY_QUOTE_REQUEST_WRONG_STATUS);
        }

        $quoteRequestResponseTransfer = $this->executeQuoteRequestUserValidatorPlugins($quoteRequestTransfer);
        if (!$quoteRequestResponseTransfer->getIsSuccessful()) {
            return $quoteRequestResponseTransfer;
        }

        $latestQuoteRequestVersionTransfer = $this->quoteRequestVersionSanitizer->reloadQuoteRequestVersionItems($quoteRequestTransfer->getLatestVersion());
        $latestQuoteRequestVersionTransfer = $this->quoteRequestEntityManager->updateQuoteRequestVersion($latestQuoteRequestVersionTransfer);

        $quoteRequestTransfer = $this->quoteRequestEntityManager->updateQuoteRequest($quoteRequestTransfer);

        $quoteRequestTransfer->setLatestVersion($latestQuoteRequestVersionTransfer);

        return $this->createSuccessfulResponse($quoteRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestFilterTransfer $quoteRequestFilterTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestResponseTransfer
     */
    protected function executeReviseQuoteRequestTransaction(QuoteRequestFilterTransfer $quoteRequestFilterTransfer): QuoteRequestResponseTransfer
    {
        $quoteRequestResponseTransfer = $this->quoteRequestReader->getQuoteRequest($quoteRequestFilterTransfer);

        if (!$quoteRequestResponseTransfer->getIsSuccessful()) {
            return $quoteRequestResponseTransfer;
        }

        $quoteRequestTransfer = $quoteRequestResponseTransfer->getQuoteRequest();

        if (!$this->quoteRequestUserStatus->isQuoteRequestRevisable($quoteRequestTransfer)) {
            return $this->getErrorResponse(static::GLOSSARY_KEY_QUOTE_REQUEST_WRONG_STATUS);
        }

        if (!$this->isQuoteRequestNonConcurrent($quoteRequestTransfer)) {
            return $this->getErrorResponse(static::GLOSSARY_KEY_CONCURRENT_CUSTOMERS);
        }

        $latestQuoteRequestVersionTransfer = $this->addQuoteRequestVersion($quoteRequestTransfer);

        $quoteRequestTransfer
            ->setStatus(SharedQuoteRequestConfig::STATUS_IN_PROGRESS)
            ->setLatestVersion($latestQuoteRequestVersionTransfer)
            ->setLatestVisibleVersion($latestQuoteRequestVersionTransfer)
            ->setIsLatestVersionVisible(false);

        $quoteRequestTransfer = $this->quoteRequestEntityManager->updateQuoteRequest($quoteRequestTransfer);

        return $this->createSuccessfulResponse($quoteRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestVersionTransfer
     */
    protected function addQuoteRequestVersion(QuoteRequestTransfer $quoteRequestTransfer): QuoteRequestVersionTransfer
    {
        $quoteRequestTransfer->requireIdQuoteRequest()
            ->requireLatestVersion();

        $latestQuoteRequestVersionTransfer = $quoteRequestTransfer->getLatestVersion();

        $quoteRequestVersionTransfer = (new QuoteRequestVersionTransfer())
            ->setVersion($quoteRequestTransfer->getLatestVersion()->getVersion() + 1)
            ->setFkQuoteRequest($quoteRequestTransfer->getIdQuoteRequest())
            ->setQuote($latestQuoteRequestVersionTransfer->getQuote())
            ->setMetadata($latestQuoteRequestVersionTransfer->getMetadata());

        $quoteRequestVersionTransfer->setVersionReference(
            $this->quoteRequestReferenceGenerator->generateQuoteRequestVersionReference($quoteRequestTransfer, $quoteRequestVersionTransfer),
        );

        return $this->quoteRequestEntityManager->createQuoteRequestVersion($quoteRequestVersionTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestVersionTransfer
     */
    protected function createQuoteRequestVersionTransfer(QuoteRequestTransfer $quoteRequestTransfer): QuoteRequestVersionTransfer
    {
        $quoteRequestTransfer->requireIdQuoteRequest()
            ->requireQuoteRequestReference();

        $quoteRequestVersionTransfer = (new QuoteRequestVersionTransfer())
            ->setQuote(new QuoteTransfer())
            ->setVersion($this->quoteRequestConfig->getInitialVersion())
            ->setFkQuoteRequest($quoteRequestTransfer->getIdQuoteRequest());

        $quoteRequestVersionTransfer->setVersionReference(
            $this->quoteRequestReferenceGenerator->generateQuoteRequestVersionReference($quoteRequestTransfer, $quoteRequestVersionTransfer),
        );

        return $this->quoteRequestEntityManager->createQuoteRequestVersion($quoteRequestVersionTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestResponseTransfer
     */
    protected function validateQuoteRequestBeforeSend(QuoteRequestTransfer $quoteRequestTransfer): QuoteRequestResponseTransfer
    {
        if ($quoteRequestTransfer->getStatus() !== SharedQuoteRequestConfig::STATUS_IN_PROGRESS) {
            return $this->getErrorResponse(static::GLOSSARY_KEY_QUOTE_REQUEST_WRONG_STATUS);
        }

        if (!$quoteRequestTransfer->getLatestVersion()->getQuote()->getItems()->count()) {
            return $this->getErrorResponse(static::GLOSSARY_KEY_QUOTE_REQUEST_EMPTY_QUOTE_ITEMS);
        }

        if ($quoteRequestTransfer->getValidUntil() && strtotime($quoteRequestTransfer->getValidUntil()) < time()) {
            return $this->getErrorResponse(static::GLOSSARY_KEY_WRONG_QUOTE_REQUEST_VALID_UNTIL);
        }

        return (new QuoteRequestResponseTransfer())
            ->setIsSuccessful(true);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return bool
     */
    protected function isQuoteRequestNonConcurrent(QuoteRequestTransfer $quoteRequestTransfer): bool
    {
        return $this->quoteRequestEntityManager->updateQuoteRequestStatus(
            $quoteRequestTransfer->getQuoteRequestReference(),
            $quoteRequestTransfer->getStatus(),
            SharedQuoteRequestConfig::STATUS_IN_PROGRESS,
        );
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestResponseTransfer
     */
    protected function executeQuoteRequestUserValidatorPlugins(
        QuoteRequestTransfer $quoteRequestTransfer
    ): QuoteRequestResponseTransfer {
        $errorMessageTransfers = [];
        foreach ($this->quoteRequestUserValidatorPlugins as $quoteRequestUserValidatorPlugin) {
            $quoteRequestResponseTransfer = $quoteRequestUserValidatorPlugin->validate($quoteRequestTransfer);
            if (!$quoteRequestResponseTransfer->getIsSuccessful()) {
                $errorMessageTransfers[] = $quoteRequestResponseTransfer->getMessages()->getArrayCopy();
            }
        }

        if ($errorMessageTransfers) {
            $errorMessagesTransferCollection = new ArrayObject(array_merge([], ...$errorMessageTransfers));

            return (new QuoteRequestResponseTransfer())
                ->setIsSuccessful(false)
                ->setMessages($errorMessagesTransferCollection);
        }

        return $this->createSuccessfulResponse($quoteRequestTransfer);
    }

    /**
     * @param string $message
     *
     * @return \Generated\Shared\Transfer\QuoteRequestResponseTransfer
     */
    protected function getErrorResponse(string $message): QuoteRequestResponseTransfer
    {
        $messageTransfer = (new MessageTransfer())
            ->setValue($message);

        return (new QuoteRequestResponseTransfer())
            ->setIsSuccessful(false)
            ->addMessage($messageTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestResponseTransfer
     */
    protected function createSuccessfulResponse(QuoteRequestTransfer $quoteRequestTransfer): QuoteRequestResponseTransfer
    {
        return (new QuoteRequestResponseTransfer())
            ->setQuoteRequest($quoteRequestTransfer)
            ->setIsSuccessful(true);
    }
}
