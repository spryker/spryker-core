<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\QuoteApproval;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\QuoteApproval\Dependency\Client\QuoteApprovalToPermissionClientInterface;
use Spryker\Client\QuoteApproval\Dependency\Client\QuoteApprovalToZedRequestClientInterface;
use Spryker\Client\QuoteApproval\Permission\PermissionLimitCalculator;
use Spryker\Client\QuoteApproval\Permission\PermissionLimitCalculatorInterface;
use Spryker\Client\QuoteApproval\Quote\QuoteStatusChecker;
use Spryker\Client\QuoteApproval\Quote\QuoteStatusCheckerInterface;
use Spryker\Client\QuoteApproval\Zed\QuoteApprovalStub;
use Spryker\Client\QuoteApproval\Zed\QuoteApprovalStubInterface;
use Spryker\Shared\QuoteApproval\QuoteStatus\QuoteStatusCalculator;
use Spryker\Shared\QuoteApproval\QuoteStatus\QuoteStatusCalculatorInterface;

class QuoteApprovalFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Shared\QuoteApproval\QuoteStatus\QuoteStatusCalculatorInterface
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
            $this->getPermissionClient(),
            $this->createQuoteStatusCalculator()
        );
    }

    /**
     * @return \Spryker\Client\QuoteApproval\Permission\PermissionLimitCalculatorInterface
     */
    public function createPermissionLimitCalculator(): PermissionLimitCalculatorInterface
    {
        return new PermissionLimitCalculator($this->getPermissionClient());
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
     * @return \Spryker\Client\QuoteApproval\Dependency\Client\QuoteApprovalToZedRequestClientInterface
     */
    public function getZedRequestClient(): QuoteApprovalToZedRequestClientInterface
    {
        return $this->getProvidedDependency(QuoteApprovalDependencyProvider::CLIENT_ZED_REQUEST);
    }

    /**
     * @return \Spryker\Client\QuoteApproval\Dependency\Client\QuoteApprovalToPermissionClientInterface
     */
    public function getPermissionClient(): QuoteApprovalToPermissionClientInterface
    {
        return $this->getProvidedDependency(QuoteApprovalDependencyProvider::CLIENT_PERMISSION);
    }
}
