<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteApproval\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\QuoteApproval\Business\Permission\ContextProvider\PermissionContextProvider;
use Spryker\Zed\QuoteApproval\Business\Permission\ContextProvider\PermissionContextProviderInterface;
use Spryker\Zed\QuoteApproval\Business\Quote\QuoteFieldsProvider;
use Spryker\Zed\QuoteApproval\Business\Quote\QuoteFieldsProviderInterface;
use Spryker\Zed\QuoteApproval\Business\Quote\QuoteLocker;
use Spryker\Zed\QuoteApproval\Business\Quote\QuoteLockerInterface;
use Spryker\Zed\QuoteApproval\Business\Quote\QuoteStatusCalculator;
use Spryker\Zed\QuoteApproval\Business\Quote\QuoteStatusCalculatorInterface;
use Spryker\Zed\QuoteApproval\Business\QuoteApproval\QuoteApprovalCreator;
use Spryker\Zed\QuoteApproval\Business\QuoteApproval\QuoteApprovalCreatorInterface;
use Spryker\Zed\QuoteApproval\Business\QuoteApproval\QuoteApprovalMessageBuilder;
use Spryker\Zed\QuoteApproval\Business\QuoteApproval\QuoteApprovalMessageBuilderInterface;
use Spryker\Zed\QuoteApproval\Business\QuoteApproval\QuoteApprovalRemover;
use Spryker\Zed\QuoteApproval\Business\QuoteApproval\QuoteApprovalRemoverInterface;
use Spryker\Zed\QuoteApproval\Business\QuoteApproval\QuoteApprovalRequestValidator;
use Spryker\Zed\QuoteApproval\Business\QuoteApproval\QuoteApprovalRequestValidatorInterface;
use Spryker\Zed\QuoteApproval\Business\QuoteApproval\QuoteApprovalWriter;
use Spryker\Zed\QuoteApproval\Business\QuoteApproval\QuoteApprovalWriterInterface;
use Spryker\Zed\QuoteApproval\Business\QuoteApproval\QuoteApproverListProvider;
use Spryker\Zed\QuoteApproval\Business\QuoteApproval\QuoteApproverListProviderInterface;
use Spryker\Zed\QuoteApproval\Business\Sanitizer\QuoteApprovalSanitizer;
use Spryker\Zed\QuoteApproval\Business\Sanitizer\QuoteApprovalSanitizerInterface;
use Spryker\Zed\QuoteApproval\Dependency\Facade\QuoteApprovalToCompanyRoleFacadeInterface;
use Spryker\Zed\QuoteApproval\Dependency\Facade\QuoteApprovalToCompanyUserFacadeInterface;
use Spryker\Zed\QuoteApproval\Dependency\Facade\QuoteApprovalToCustomerFacadeInterface;
use Spryker\Zed\QuoteApproval\Dependency\Facade\QuoteApprovalToQuoteFacadeInterface;
use Spryker\Zed\QuoteApproval\Dependency\Facade\QuoteApprovalToSharedCartFacadeInterface;
use Spryker\Zed\QuoteApproval\Persistence\QuoteApprovalRepositoryInterface;
use Spryker\Zed\QuoteApproval\QuoteApprovalDependencyProvider;

/**
 * @method \Spryker\Zed\QuoteApproval\QuoteApprovalConfig getConfig()
 * @method \Spryker\Zed\QuoteApproval\Persistence\QuoteApprovalEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\QuoteApproval\Persistence\QuoteApprovalRepositoryInterface getRepository()
 */
class QuoteApprovalBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\QuoteApproval\Business\QuoteApproval\QuoteApprovalCreatorInterface
     */
    public function createQuoteApprovalCreator(): QuoteApprovalCreatorInterface
    {
        return new QuoteApprovalCreator(
            $this->createQuoteLocker(),
            $this->getSharedCartFacade(),
            $this->createQuoteApprovalRequestValidator(),
            $this->getEntityManager()
        );
    }

    /**
     * @return \Spryker\Zed\QuoteApproval\Business\Quote\QuoteLockerInterface
     */
    public function createQuoteLocker(): QuoteLockerInterface
    {
        return new QuoteLocker(
            $this->getQuoteFacade(),
            $this->getQuoteApprovalUnlockPreCheckPlugins()
        );
    }

    /**
     * @return \Spryker\Zed\QuoteApproval\Business\QuoteApproval\QuoteApprovalRequestValidatorInterface
     */
    public function createQuoteApprovalRequestValidator(): QuoteApprovalRequestValidatorInterface
    {
        return new QuoteApprovalRequestValidator(
            $this->getQuoteFacade(),
            $this->createQuoteStatusCalculator(),
            $this->getRepository(),
            $this->getCompanyUserFacade(),
            $this->createPermissionContextProvider()
        );
    }

    /**
     * @return \Spryker\Zed\QuoteApproval\Business\Permission\ContextProvider\PermissionContextProviderInterface
     */
    public function createPermissionContextProvider(): PermissionContextProviderInterface
    {
        return new PermissionContextProvider();
    }

    /**
     * @return \Spryker\Zed\QuoteApproval\Business\QuoteApproval\QuoteApprovalWriterInterface
     */
    public function createQuoteApprovalWriter(): QuoteApprovalWriterInterface
    {
        return new QuoteApprovalWriter(
            $this->createQuoteApprovalRequestValidator(),
            $this->createQuoteApprovalMessageBuilder(),
            $this->getEntityManager(),
            $this->createQuoteLocker()
        );
    }

    /**
     * @return \Spryker\Zed\QuoteApproval\Business\Quote\QuoteStatusCalculatorInterface
     */
    public function createQuoteStatusCalculator(): QuoteStatusCalculatorInterface
    {
        return new QuoteStatusCalculator();
    }

    /**
     * @return \Spryker\Zed\QuoteApproval\Business\QuoteApproval\QuoteApprovalRemoverInterface
     */
    public function createQuoteApprovalRemover(): QuoteApprovalRemoverInterface
    {
        return new QuoteApprovalRemover(
            $this->createQuoteLocker(),
            $this->createQuoteApprovalRequestValidator(),
            $this->getSharedCartFacade(),
            $this->getEntityManager()
        );
    }

    /**
     * @return \Spryker\Zed\QuoteApproval\Business\Quote\QuoteFieldsProviderInterface
     */
    public function createQuoteFieldsProvider(): QuoteFieldsProviderInterface
    {
        return new QuoteFieldsProvider(
            $this->createQuoteStatusCalculator(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\QuoteApproval\Business\Sanitizer\QuoteApprovalSanitizerInterface
     */
    public function createQuoteApprovalSanitizer(): QuoteApprovalSanitizerInterface
    {
        return new QuoteApprovalSanitizer(
            $this->getEntityManager(),
            $this->getRepository()
        );
    }

    /**
     * @return \Spryker\Zed\QuoteApproval\Business\QuoteApproval\QuoteApproverListProviderInterface
     */
    public function createQuoteApproversProvider(): QuoteApproverListProviderInterface
    {
        return new QuoteApproverListProvider(
            $this->getCompanyRoleFacade(),
            $this->getCompanyUserFacade()
        );
    }

    /**
     * @return \Spryker\Zed\QuoteApproval\Business\QuoteApproval\QuoteApprovalMessageBuilderInterface
     */
    public function createQuoteApprovalMessageBuilder(): QuoteApprovalMessageBuilderInterface
    {
        return new QuoteApprovalMessageBuilder(
            $this->getQuoteFacade(),
            $this->getCustomerFacade()
        );
    }

    /**
     * @return \Spryker\Zed\QuoteApproval\Persistence\QuoteApprovalRepositoryInterface
     */
    public function getQuoteApprovalRepository(): QuoteApprovalRepositoryInterface
    {
        return $this->getRepository();
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
     * @return \Spryker\Zed\QuoteApproval\Dependency\Facade\QuoteApprovalToSharedCartFacadeInterface
     */
    public function getSharedCartFacade(): QuoteApprovalToSharedCartFacadeInterface
    {
        return $this->getProvidedDependency(QuoteApprovalDependencyProvider::FACADE_SHARED_CART);
    }

    /**
     * @return \Spryker\Zed\QuoteApproval\Dependency\Facade\QuoteApprovalToCustomerFacadeInterface
     */
    public function getCustomerFacade(): QuoteApprovalToCustomerFacadeInterface
    {
        return $this->getProvidedDependency(QuoteApprovalDependencyProvider::FACADE_CUSTOMER);
    }

    /**
     * @return \Spryker\Zed\QuoteApprovalExtension\Dependency\Plugin\QuoteApprovalUnlockPreCheckPluginInterface[]
     */
    public function getQuoteApprovalUnlockPreCheckPlugins(): array
    {
        return $this->getProvidedDependency(QuoteApprovalDependencyProvider::PLUGINS_QUOTE_APPROVAL_UNLOCK_PRE_CHECK);
    }
}
