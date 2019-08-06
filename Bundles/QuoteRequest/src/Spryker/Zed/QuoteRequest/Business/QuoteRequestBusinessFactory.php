<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteRequest\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\QuoteRequest\Business\Cleaner\QuoteRequestCleaner;
use Spryker\Zed\QuoteRequest\Business\Cleaner\QuoteRequestCleanerInterface;
use Spryker\Zed\QuoteRequest\Business\Reader\QuoteRequestReader;
use Spryker\Zed\QuoteRequest\Business\Reader\QuoteRequestReaderInterface;
use Spryker\Zed\QuoteRequest\Business\ReferenceGenerator\QuoteRequestReferenceGenerator;
use Spryker\Zed\QuoteRequest\Business\ReferenceGenerator\QuoteRequestReferenceGeneratorInterface;
use Spryker\Zed\QuoteRequest\Business\Sanitizer\QuoteRequestVersionSanitizer;
use Spryker\Zed\QuoteRequest\Business\Sanitizer\QuoteRequestVersionSanitizerInterface;
use Spryker\Zed\QuoteRequest\Business\Sender\QuoteRequestSender;
use Spryker\Zed\QuoteRequest\Business\Sender\QuoteRequestSenderInterface;
use Spryker\Zed\QuoteRequest\Business\Sender\QuoteRequestUserSender;
use Spryker\Zed\QuoteRequest\Business\Sender\QuoteRequestUserSenderInterface;
use Spryker\Zed\QuoteRequest\Business\Status\QuoteRequestStatus;
use Spryker\Zed\QuoteRequest\Business\Status\QuoteRequestStatusInterface;
use Spryker\Zed\QuoteRequest\Business\Status\QuoteRequestUserStatus;
use Spryker\Zed\QuoteRequest\Business\Status\QuoteRequestUserStatusInterface;
use Spryker\Zed\QuoteRequest\Business\Validator\QuoteRequestTimeValidator;
use Spryker\Zed\QuoteRequest\Business\Validator\QuoteRequestTimeValidatorInterface;
use Spryker\Zed\QuoteRequest\Business\Writer\QuoteRequestTerminator;
use Spryker\Zed\QuoteRequest\Business\Writer\QuoteRequestTerminatorInterface;
use Spryker\Zed\QuoteRequest\Business\Writer\QuoteRequestUserTerminator;
use Spryker\Zed\QuoteRequest\Business\Writer\QuoteRequestUserTerminatorInterface;
use Spryker\Zed\QuoteRequest\Business\Writer\QuoteRequestUserWriter;
use Spryker\Zed\QuoteRequest\Business\Writer\QuoteRequestUserWriterInterface;
use Spryker\Zed\QuoteRequest\Business\Writer\QuoteRequestWriter;
use Spryker\Zed\QuoteRequest\Business\Writer\QuoteRequestWriterInterface;
use Spryker\Zed\QuoteRequest\Dependency\Facade\QuoteRequestToCalculationFacadeInterface;
use Spryker\Zed\QuoteRequest\Dependency\Facade\QuoteRequestToCartFacadeInterface;
use Spryker\Zed\QuoteRequest\Dependency\Facade\QuoteRequestToCompanyUserFacadeInterface;
use Spryker\Zed\QuoteRequest\QuoteRequestDependencyProvider;

/**
 * @method \Spryker\Zed\QuoteRequest\Persistence\QuoteRequestEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\QuoteRequest\Persistence\QuoteRequestRepositoryInterface getRepository()
 * @method \Spryker\Zed\QuoteRequest\QuoteRequestConfig getConfig()
 */
class QuoteRequestBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\QuoteRequest\Business\Writer\QuoteRequestWriterInterface
     */
    public function createQuoteRequestWriter(): QuoteRequestWriterInterface
    {
        return new QuoteRequestWriter(
            $this->getConfig(),
            $this->getEntityManager(),
            $this->createQuoteRequestReader(),
            $this->createQuoteRequestReferenceGenerator(),
            $this->createQuoteRequestVersionSanitizer(),
            $this->createQuoteRequestStatus()
        );
    }

    /**
     * @return \Spryker\Zed\QuoteRequest\Business\Writer\QuoteRequestUserWriterInterface
     */
    public function createQuoteRequestUserWriter(): QuoteRequestUserWriterInterface
    {
        return new QuoteRequestUserWriter(
            $this->getConfig(),
            $this->getEntityManager(),
            $this->createQuoteRequestReader(),
            $this->createQuoteRequestReferenceGenerator(),
            $this->createQuoteRequestVersionSanitizer(),
            $this->createQuoteRequestUserStatus()
        );
    }

    /**
     * @return \Spryker\Zed\QuoteRequest\Business\Validator\QuoteRequestTimeValidatorInterface
     */
    public function createQuoteRequestTimeValidator(): QuoteRequestTimeValidatorInterface
    {
        return new QuoteRequestTimeValidator(
            $this->createQuoteRequestReader()
        );
    }

    /**
     * @return \Spryker\Zed\QuoteRequest\Business\Sender\QuoteRequestSenderInterface
     */
    public function createQuoteRequestSender(): QuoteRequestSenderInterface
    {
        return new QuoteRequestSender(
            $this->getEntityManager(),
            $this->createQuoteRequestReader()
        );
    }

    /**
     * @return \Spryker\Zed\QuoteRequest\Business\Sender\QuoteRequestUserSenderInterface
     */
    public function createQuoteRequestUserSender(): QuoteRequestUserSenderInterface
    {
        return new QuoteRequestUserSender(
            $this->getEntityManager(),
            $this->createQuoteRequestReader()
        );
    }

    /**
     * @return \Spryker\Zed\QuoteRequest\Business\Writer\QuoteRequestTerminatorInterface
     */
    public function createQuoteRequestTerminator(): QuoteRequestTerminatorInterface
    {
        return new QuoteRequestTerminator(
            $this->getEntityManager(),
            $this->getRepository(),
            $this->createQuoteRequestReader(),
            $this->createQuoteRequestStatus()
        );
    }

    /**
     * @return \Spryker\Zed\QuoteRequest\Business\Writer\QuoteRequestUserTerminatorInterface
     */
    public function createQuoteRequestUserTerminator(): QuoteRequestUserTerminatorInterface
    {
        return new QuoteRequestUserTerminator(
            $this->getEntityManager(),
            $this->createQuoteRequestReader(),
            $this->createQuoteRequestUserStatus()
        );
    }

    /**
     * @return \Spryker\Zed\QuoteRequest\Business\Reader\QuoteRequestReaderInterface
     */
    public function createQuoteRequestReader(): QuoteRequestReaderInterface
    {
        return new QuoteRequestReader(
            $this->getRepository(),
            $this->getCompanyUserFacade()
        );
    }

    /**
     * @return \Spryker\Zed\QuoteRequest\Business\Status\QuoteRequestStatusInterface
     */
    public function createQuoteRequestStatus(): QuoteRequestStatusInterface
    {
        return new QuoteRequestStatus(
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\QuoteRequest\Business\Status\QuoteRequestUserStatusInterface
     */
    public function createQuoteRequestUserStatus(): QuoteRequestUserStatusInterface
    {
        return new QuoteRequestUserStatus(
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\QuoteRequest\Business\ReferenceGenerator\QuoteRequestReferenceGeneratorInterface
     */
    public function createQuoteRequestReferenceGenerator(): QuoteRequestReferenceGeneratorInterface
    {
        return new QuoteRequestReferenceGenerator(
            $this->getConfig(),
            $this->getRepository()
        );
    }

    /**
     * @return \Spryker\Zed\QuoteRequest\Business\Sanitizer\QuoteRequestVersionSanitizerInterface
     */
    public function createQuoteRequestVersionSanitizer(): QuoteRequestVersionSanitizerInterface
    {
        return new QuoteRequestVersionSanitizer(
            $this->getCartFacade(),
            $this->getCalculationFacade()
        );
    }

    /**
     * @return \Spryker\Zed\QuoteRequest\Business\Cleaner\QuoteRequestCleanerInterface
     */
    public function createQuoteRequestCleaner(): QuoteRequestCleanerInterface
    {
        return new QuoteRequestCleaner(
            $this->getRepository(),
            $this->getEntityManager()
        );
    }

    /**
     * @return \Spryker\Zed\QuoteRequest\Dependency\Facade\QuoteRequestToCompanyUserFacadeInterface
     */
    public function getCompanyUserFacade(): QuoteRequestToCompanyUserFacadeInterface
    {
        return $this->getProvidedDependency(QuoteRequestDependencyProvider::FACADE_COMPANY_USER);
    }

    /**
     * @return \Spryker\Zed\QuoteRequest\Dependency\Facade\QuoteRequestToCalculationFacadeInterface
     */
    public function getCalculationFacade(): QuoteRequestToCalculationFacadeInterface
    {
        return $this->getProvidedDependency(QuoteRequestDependencyProvider::FACADE_CALCULATION);
    }

    /**
     * @return \Spryker\Zed\QuoteRequest\Dependency\Facade\QuoteRequestToCartFacadeInterface
     */
    public function getCartFacade(): QuoteRequestToCartFacadeInterface
    {
        return $this->getProvidedDependency(QuoteRequestDependencyProvider::FACADE_CART);
    }
}
