<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\QuoteApproval;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\QuoteApproval\Dependency\Client\QuoteApprovalToZedRequestClientInterface;
use Spryker\Client\QuoteApproval\Permission\ContextProvider\PermissionContextProvider;
use Spryker\Client\QuoteApproval\Permission\ContextProvider\PermissionContextProviderInterface;
use Spryker\Client\QuoteApproval\Permission\PermissionLimitCalculator;
use Spryker\Client\QuoteApproval\Permission\PermissionLimitCalculatorInterface;
use Spryker\Client\QuoteApproval\Quote\QuoteStatusCalculator;
use Spryker\Client\QuoteApproval\Quote\QuoteStatusCalculatorInterface;
use Spryker\Client\QuoteApproval\Quote\QuoteStatusChecker;
use Spryker\Client\QuoteApproval\Quote\QuoteStatusCheckerInterface;
use Spryker\Client\QuoteApproval\QuoteApproval\QuoteApprovalCreator;
use Spryker\Client\QuoteApproval\QuoteApproval\QuoteApprovalCreatorInterface;
use Spryker\Client\QuoteApproval\QuoteApproval\QuoteApprovalReader;
use Spryker\Client\QuoteApproval\QuoteApproval\QuoteApprovalReaderInterface;
use Spryker\Client\QuoteApproval\Zed\QuoteApprovalStub;
use Spryker\Client\QuoteApproval\Zed\QuoteApprovalStubInterface;

class QuoteApprovalFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\QuoteApproval\Quote\QuoteStatusCalculatorInterface
     */
    public function createQuoteStatusCalculator(): QuoteStatusCalculatorInterface
    {
        return new QuoteStatusCalculator();
    }

    /**
     * @return \Spryker\Client\QuoteApproval\Quote\QuoteStatusCheckerInterface
     */
    public function createQuoteStatusChecker(): QuoteStatusCheckerInterface
    {
        return new QuoteStatusChecker(
            $this->createQuoteStatusCalculator(),
            $this->createPermissionContextProvider()
        );
    }

    /**
     * @return \Spryker\Client\QuoteApproval\Permission\ContextProvider\PermissionContextProviderInterface
     */
    public function createPermissionContextProvider(): PermissionContextProviderInterface
    {
        return new PermissionContextProvider();
    }

    /**
     * @return \Spryker\Client\QuoteApproval\QuoteApproval\QuoteApprovalReaderInterface
     */
    public function createQuoteApprovalReader(): QuoteApprovalReaderInterface
    {
        return new QuoteApprovalReader();
    }

    /**
     * @return \Spryker\Client\QuoteApproval\Permission\PermissionLimitCalculatorInterface
     */
    public function createPermissionLimitCalculator(): PermissionLimitCalculatorInterface
    {
        return new PermissionLimitCalculator();
    }

    /**
     * @return \Spryker\Client\QuoteApproval\Zed\QuoteApprovalStubInterface
     */
    public function createQuoteApprovalStub(): QuoteApprovalStubInterface
    {
        return new QuoteApprovalStub(
            $this->getZedRequestClient()
        );
    }

    /**
     * @return \Spryker\Client\QuoteApproval\QuoteApproval\QuoteApprovalCreatorInterface
     */
    public function createQuoteApprovalCreator(): QuoteApprovalCreatorInterface
    {
        return new QuoteApprovalCreator(
            $this->createQuoteApprovalStub()
        );
    }

    /**
     * @return \Spryker\Client\QuoteApproval\Dependency\Client\QuoteApprovalToZedRequestClientInterface
     */
    public function getZedRequestClient(): QuoteApprovalToZedRequestClientInterface
    {
        return $this->getProvidedDependency(QuoteApprovalDependencyProvider::CLIENT_ZED_REQUEST);
    }
}
