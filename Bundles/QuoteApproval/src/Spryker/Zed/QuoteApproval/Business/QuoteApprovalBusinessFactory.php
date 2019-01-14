<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteApproval\Business;

use Spryker\Shared\QuoteApproval\StatusCalculator\QuoteApprovalStatusCalculator;
use Spryker\Shared\QuoteApproval\StatusCalculator\QuoteApprovalStatusCalculatorInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\QuoteApproval\Business\QuoteApprovalReader\QuoteApprovalReader;
use Spryker\Zed\QuoteApproval\Business\QuoteApprovalReader\QuoteApprovalReaderInterface;
use Spryker\Zed\QuoteApproval\Business\QuoteApprovalRequest\QuoteApprovalRequestCanceller;
use Spryker\Zed\QuoteApproval\Business\QuoteApprovalRequest\QuoteApprovalRequestCancellerInterface;
use Spryker\Zed\QuoteApproval\Business\QuoteApprovalRequest\QuoteApprovalRequestSender;
use Spryker\Zed\QuoteApproval\Business\QuoteApprovalRequest\QuoteApprovalRequestSenderInterface;
use Spryker\Zed\QuoteApproval\Business\QuoteApprovalRequest\QuoteApprovalRequestValidator;
use Spryker\Zed\QuoteApproval\Business\QuoteApprovalRequest\QuoteApprovalRequestValidatorInterface;
use Spryker\Zed\QuoteApproval\Business\QuoteApprovalWriter\QuoteApprovalWriter;
use Spryker\Zed\QuoteApproval\Business\QuoteApprovalWriter\QuoteApprovalWriterInterface;
use Spryker\Zed\QuoteApproval\Business\QuoteApproversProvider\PotentialQuoteApproversListProvider;
use Spryker\Zed\QuoteApproval\Business\QuoteApproversProvider\PotentialQuoteApproversListProviderInterface;
use Spryker\Zed\QuoteApproval\Dependency\Facade\QuoteApprovalToCartFacadeInterface;
use Spryker\Zed\QuoteApproval\Dependency\Facade\QuoteApprovalToCompanyRoleFacadeInterface;
use Spryker\Zed\QuoteApproval\Dependency\Facade\QuoteApprovalToCompanyUserFacadeInterface;
use Spryker\Zed\QuoteApproval\Dependency\Facade\QuoteApprovalToMessengerFacadeInterface;
use Spryker\Zed\QuoteApproval\Dependency\Facade\QuoteApprovalToPermissionFacadeInterface;
use Spryker\Zed\QuoteApproval\Dependency\Facade\QuoteApprovalToQuoteFacadeInterface;
use Spryker\Zed\QuoteApproval\QuoteApprovalDependencyProvider;

/**
 * @method \Spryker\Zed\QuoteApproval\QuoteApprovalConfig getConfig()
 * @method \Spryker\Zed\QuoteApproval\Persistence\QuoteApprovalEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\QuoteApproval\Persistence\QuoteApprovalRepositoryInterface getRepository()
 */
class QuoteApprovalBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\QuoteApproval\Business\QuoteApprovalRequest\QuoteApprovalRequestSenderInterface
     */
    public function createQuoteApprovalRequestSender(): QuoteApprovalRequestSenderInterface
    {
        return new QuoteApprovalRequestSender(
            $this->getCartFacade(),
            $this->getQuoteFacade(),
            $this->getMessengerFacade(),
            $this->getCompanyUserFacade(),
            $this->createQuoteApprovalRequestValidator()
        );
    }

    /**
     * @return \Spryker\Zed\QuoteApproval\Business\QuoteApprovalRequest\QuoteApprovalRequestValidatorInterface
     */
    public function createQuoteApprovalRequestValidator(): QuoteApprovalRequestValidatorInterface
    {
        return new QuoteApprovalRequestValidator(
            $this->getPermissionFacade(),
            $this->createQuoteApprovalStatusCalculator()
        );
    }

    /**
     * @return \Spryker\Shared\QuoteApproval\StatusCalculator\QuoteApprovalStatusCalculatorInterface
     */
    public function createQuoteApprovalStatusCalculator(): QuoteApprovalStatusCalculatorInterface
    {
        return new QuoteApprovalStatusCalculator();
    }

    /**
     * @return \Spryker\Zed\QuoteApproval\Business\QuoteApprovalRequest\QuoteApprovalRequestCancellerInterface
     */
    public function createQuoteApprovalRequestCanceller(): QuoteApprovalRequestCancellerInterface
    {
        return new QuoteApprovalRequestCanceller(
            $this->getCartFacade(),
            $this->getQuoteFacade(),
            $this->getMessengerFacade()
        );
    }

    /**
     * @return \Spryker\Zed\QuoteApproval\Business\QuoteApproversProvider\PotentialQuoteApproversListProviderInterface
     */
    public function createPotentialQuoteApproversProvider(): PotentialQuoteApproversListProviderInterface
    {
        return new PotentialQuoteApproversListProvider(
            $this->getCompanyRoleFacade(),
            $this->getCompanyUserFacade(),
            $this->getPermissionFacade()
        );
    }

    /**
     * @return \Spryker\Zed\QuoteApproval\Business\QuoteApprovalReader\QuoteApprovalReaderInterface
     */
    public function createQuoteApprovalReader(): QuoteApprovalReaderInterface
    {
        return new QuoteApprovalReader(
            $this->getRepository()
        );
    }

    /**
     * @return \Spryker\Zed\QuoteApproval\Business\QuoteApprovalWriter\QuoteApprovalWriterInterface
     */
    public function createQuoteApprovalWriter(): QuoteApprovalWriterInterface
    {
        return new QuoteApprovalWriter(
            $this->getEntityManager(),
            $this->getRepository()
        );
    }

    /**
     * @return \Spryker\Zed\QuoteApproval\Dependency\Facade\QuoteApprovalToCartFacadeInterface
     */
    protected function getCartFacade(): QuoteApprovalToCartFacadeInterface
    {
        return $this->getProvidedDependency(QuoteApprovalDependencyProvider::FACADE_CART);
    }

    /**
     * @return \Spryker\Zed\QuoteApproval\Dependency\Facade\QuoteApprovalToQuoteFacadeInterface
     */
    protected function getQuoteFacade(): QuoteApprovalToQuoteFacadeInterface
    {
        return $this->getProvidedDependency(QuoteApprovalDependencyProvider::FACADE_QUOTE);
    }

    /**
     * @return \Spryker\Zed\QuoteApproval\Dependency\Facade\QuoteApprovalToCompanyUserFacadeInterface
     */
    public function getCompanyUserFacade(): QuoteApprovalToCompanyUserFacadeInterface
    {
        return $this->getProvidedDependency(QuoteApprovalDependencyProvider::FACADE_COMPANY_USER);
    }

    /**
     * @return \Spryker\Zed\QuoteApproval\Dependency\Facade\QuoteApprovalToCompanyRoleFacadeInterface
     */
    public function getCompanyRoleFacade(): QuoteApprovalToCompanyRoleFacadeInterface
    {
        return $this->getProvidedDependency(QuoteApprovalDependencyProvider::FACADE_COMPANY_ROLE);
    }

    /**
     * @return \Spryker\Zed\QuoteApproval\Dependency\Facade\QuoteApprovalToMessengerFacadeInterface
     */
    public function getMessengerFacade(): QuoteApprovalToMessengerFacadeInterface
    {
        return $this->getProvidedDependency(QuoteApprovalDependencyProvider::FACADE_MESSENGER);
    }

    /**
     * @return \Spryker\Zed\QuoteApproval\Dependency\Facade\QuoteApprovalToPermissionFacadeInterface
     */
    public function getPermissionFacade(): QuoteApprovalToPermissionFacadeInterface
    {
        return $this->getProvidedDependency(QuoteApprovalDependencyProvider::FACADE_PERMISSION);
    }
}
