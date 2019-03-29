<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteRequest\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\QuoteRequest\Business\QuoteRequest\QuoteRequestChecker;
use Spryker\Zed\QuoteRequest\Business\QuoteRequest\QuoteRequestCheckerInterface;
use Spryker\Zed\QuoteRequest\Business\QuoteRequest\QuoteRequestCleaner;
use Spryker\Zed\QuoteRequest\Business\QuoteRequest\QuoteRequestCleanerInterface;
use Spryker\Zed\QuoteRequest\Business\QuoteRequest\QuoteRequestReferenceGenerator;
use Spryker\Zed\QuoteRequest\Business\QuoteRequest\QuoteRequestReferenceGeneratorInterface;
use Spryker\Zed\QuoteRequest\Business\QuoteRequest\QuoteRequestWriter;
use Spryker\Zed\QuoteRequest\Business\QuoteRequest\QuoteRequestWriterInterface;
use Spryker\Zed\QuoteRequest\Business\UserQuoteRequest\UserQuoteRequestWriter;
use Spryker\Zed\QuoteRequest\Business\UserQuoteRequest\UserQuoteRequestWriterInterface;
use Spryker\Zed\QuoteRequest\Dependency\Facade\QuoteRequestToCalculationInterface;
use Spryker\Zed\QuoteRequest\Dependency\Facade\QuoteRequestToCartInterface;
use Spryker\Zed\QuoteRequest\Dependency\Facade\QuoteRequestToCompanyUserInterface;
use Spryker\Zed\QuoteRequest\Dependency\Facade\QuoteRequestToSequenceNumberInterface;
use Spryker\Zed\QuoteRequest\QuoteRequestDependencyProvider;

/**
 * @method \Spryker\Zed\QuoteRequest\Persistence\QuoteRequestEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\QuoteRequest\Persistence\QuoteRequestRepositoryInterface getRepository()
 * @method \Spryker\Zed\QuoteRequest\QuoteRequestConfig getConfig()
 */
class QuoteRequestBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\QuoteRequest\Business\QuoteRequest\QuoteRequestWriterInterface
     */
    public function createQuoteRequestWriter(): QuoteRequestWriterInterface
    {
        return new QuoteRequestWriter(
            $this->getConfig(),
            $this->getEntityManager(),
            $this->getRepository(),
            $this->createQuoteRequestReferenceGenerator(),
            $this->getCompanyUserFacade(),
            $this->getCalculationFacade(),
            $this->getCartFacade()
        );
    }

    /**
     * @return \Spryker\Zed\QuoteRequest\Business\UserQuoteRequest\UserQuoteRequestWriterInterface
     */
    public function createUserQuoteRequestWriter(): UserQuoteRequestWriterInterface
    {
        return new UserQuoteRequestWriter(
            $this->getConfig(),
            $this->getEntityManager(),
            $this->getRepository(),
            $this->createQuoteRequestReferenceGenerator(),
            $this->getCompanyUserFacade(),
            $this->getCartFacade()
        );
    }

    /**
     * @return \Spryker\Zed\QuoteRequest\Business\QuoteRequest\QuoteRequestCheckerInterface
     */
    public function createQuoteRequestChecker(): QuoteRequestCheckerInterface
    {
        return new QuoteRequestChecker(
            $this->getRepository()
        );
    }

    /**
     * @return \Spryker\Zed\QuoteRequest\Business\QuoteRequest\QuoteRequestCleanerInterface
     */
    public function createQuoteRequestCleaner(): QuoteRequestCleanerInterface
    {
        return new QuoteRequestCleaner(
            $this->getEntityManager(),
            $this->getRepository()
        );
    }

    /**
     * @return \Spryker\Zed\QuoteRequest\Business\QuoteRequest\QuoteRequestReferenceGeneratorInterface
     */
    public function createQuoteRequestReferenceGenerator(): QuoteRequestReferenceGeneratorInterface
    {
        return new QuoteRequestReferenceGenerator(
            $this->getSequenceNumberFacade(),
            $this->getConfig()->getQuoteRequestReferenceDefaults()
        );
    }

    /**
     * @return \Spryker\Zed\QuoteRequest\Dependency\Facade\QuoteRequestToSequenceNumberInterface
     */
    public function getSequenceNumberFacade(): QuoteRequestToSequenceNumberInterface
    {
        return $this->getProvidedDependency(QuoteRequestDependencyProvider::FACADE_SEQUENCE_NUMBER);
    }

    /**
     * @return \Spryker\Zed\QuoteRequest\Dependency\Facade\QuoteRequestToCompanyUserInterface
     */
    public function getCompanyUserFacade(): QuoteRequestToCompanyUserInterface
    {
        return $this->getProvidedDependency(QuoteRequestDependencyProvider::FACADE_COMPANY_USER);
    }

    /**
     * @return \Spryker\Zed\QuoteRequest\Dependency\Facade\QuoteRequestToCalculationInterface
     */
    public function getCalculationFacade(): QuoteRequestToCalculationInterface
    {
        return $this->getProvidedDependency(QuoteRequestDependencyProvider::FACADE_CALCULATION);
    }

    /**
     * @return \Spryker\Zed\QuoteRequest\Dependency\Facade\QuoteRequestToCartInterface
     */
    public function getCartFacade(): QuoteRequestToCartInterface
    {
        return $this->getProvidedDependency(QuoteRequestDependencyProvider::FACADE_CART);
    }
}
