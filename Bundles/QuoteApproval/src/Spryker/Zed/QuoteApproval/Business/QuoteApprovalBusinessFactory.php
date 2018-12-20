<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteApproval\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\QuoteApproval\Business\QuoteApproval\QuoteApprovalMessageBuilder;
use Spryker\Zed\QuoteApproval\Business\QuoteApproval\QuoteApprovalMessageBuilderInterface;
use Spryker\Zed\QuoteApproval\Business\QuoteApproval\QuoteApprovalRemover;
use Spryker\Zed\QuoteApproval\Business\QuoteApproval\QuoteApprovalRemoverInterface;
use Spryker\Zed\QuoteApproval\Business\QuoteApproval\QuoteApprovalValidator;
use Spryker\Zed\QuoteApproval\Business\QuoteApproval\QuoteApprovalValidatorInterface;
use Spryker\Zed\QuoteApproval\Business\QuoteApproval\QuoteApprovalWriter;
use Spryker\Zed\QuoteApproval\Business\QuoteApproval\QuoteApprovalWriterInterface;
use Spryker\Zed\QuoteApproval\Dependency\Facade\QuoteApprovalToCustomerFacadeInterface;
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
     * @return \Spryker\Zed\QuoteApproval\Business\QuoteApproval\QuoteApprovalWriterInterface
     */
    public function createQuoteApprovalWriter(): QuoteApprovalWriterInterface
    {
        return new QuoteApprovalWriter(
            $this->createQuoteApprovalValidator(),
            $this->createQuoteApprovalMessageBuilder(),
            $this->getEntityManager(),
            $this->getRepository()
        );
    }

    /**
     * @return \Spryker\Zed\QuoteApproval\Business\QuoteApproval\QuoteApprovalRemoverInterface
     */
    public function createQuoteApprovalRemover(): QuoteApprovalRemoverInterface
    {
        return new QuoteApprovalRemover(
            $this->createQuoteApprovalValidator(),
            $this->createQuoteApprovalMessageBuilder(),
            $this->getEntityManager(),
            $this->getRepository()
        );
    }

    /**
     * @return \Spryker\Zed\QuoteApproval\Business\QuoteApproval\QuoteApprovalValidatorInterface
     */
    public function createQuoteApprovalValidator(): QuoteApprovalValidatorInterface
    {
        return new QuoteApprovalValidator(
            $this->getQuoteFacade()
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
     * @return \Spryker\Zed\QuoteApproval\Dependency\Facade\QuoteApprovalToQuoteFacadeInterface
     */
    public function getQuoteFacade(): QuoteApprovalToQuoteFacadeInterface
    {
        return $this->getProvidedDependency(QuoteApprovalDependencyProvider::FACADE_QUOTE);
    }

    /**
     * @return \Spryker\Zed\QuoteApproval\Dependency\Facade\QuoteApprovalToCustomerFacadeInterface
     */
    public function getCustomerFacade(): QuoteApprovalToCustomerFacadeInterface
    {
        return $this->getProvidedDependency(QuoteApprovalDependencyProvider::FACADE_CUSTOMER);
    }
}
