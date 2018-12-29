<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteApproval\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\QuoteApproval\Business\Model\PotentialQuoteApproversListProvider;
use Spryker\Zed\QuoteApproval\Business\Model\PotentialQuoteApproversListProviderInterface;
use Spryker\Zed\QuoteApproval\Business\Model\QuoteApprovalRequestSender;
use Spryker\Zed\QuoteApproval\Business\Model\QuoteApprovalRequestSenderInterface;
use Spryker\Zed\QuoteApproval\Business\Model\QuoteApprovalWriter;
use Spryker\Zed\QuoteApproval\Business\Model\QuoteApprovalWriterInterface;
use Spryker\Zed\QuoteApproval\Dependency\Facade\QuoteApprovalToCartFacadeInterface;
use Spryker\Zed\QuoteApproval\Dependency\Facade\QuoteApprovalToCompanyRoleFacadeInterface;
use Spryker\Zed\QuoteApproval\Dependency\Facade\QuoteApprovalToCompanyUserFacadeInterface;
use Spryker\Zed\QuoteApproval\Dependency\Facade\QuoteApprovalToMessengerFacadeInterface;
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
     * @return \Spryker\Zed\QuoteApproval\Business\Model\QuoteApprovalRequestSenderInterface
     */
    public function createQuoteApprovalRequestSender(): QuoteApprovalRequestSenderInterface
    {
        return new QuoteApprovalRequestSender(
            $this->getCartFacade(),
            $this->getQuoteFacade(),
            $this->getCompanyRoleFacade(),
            $this->getMessengerFacade()
        );
    }

    /**
     * @return \Spryker\Zed\QuoteApproval\Business\Model\PotentialQuoteApproversListProviderInterface
     */
    public function createPotentialQuoteApproversProvider(): PotentialQuoteApproversListProviderInterface
    {
        return new PotentialQuoteApproversListProvider(
            $this->getCompanyRoleFacade(),
            $this->getCompanyUserFacade()
        );
    }

    /**
     * @return \Spryker\Zed\QuoteApproval\Business\Model\QuoteApprovalWriterInterface
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
}
