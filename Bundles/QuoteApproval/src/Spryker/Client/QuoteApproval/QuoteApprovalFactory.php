<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\QuoteApproval;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\QuoteApproval\Dependency\Client\QuoteApprovalToQuoteClientInterface;
use Spryker\Client\QuoteApproval\Dependency\Client\QuoteApprovalToZedRequestClientInterface;
use Spryker\Client\QuoteApproval\StatusCalculator\QuoteApprovalStatusCalculator;
use Spryker\Client\QuoteApproval\StatusCalculator\QuoteApprovalStatusCalculatorInterface;
use Spryker\Client\QuoteApproval\Zed\QuoteApprovalStub;
use Spryker\Client\QuoteApproval\Zed\QuoteApprovalStubInterface;

class QuoteApprovalFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\QuoteApproval\StatusCalculator\QuoteApprovalStatusCalculatorInterface
     */
    public function createQuoteApprovalStatusCalculator(): QuoteApprovalStatusCalculatorInterface
    {
        return new QuoteApprovalStatusCalculator();
    }

    /**
     * @return \Spryker\Client\QuoteApproval\Zed\QuoteApprovalStubInterface
     */
    public function createZedStub(): QuoteApprovalStubInterface
    {
        return new QuoteApprovalStub(
            $this->getZedRequestClient()
        );
    }

    /**
     * @return \Spryker\Client\QuoteApproval\Dependency\Client\QuoteApprovalToZedRequestClientInterface
     */
    protected function getZedRequestClient(): QuoteApprovalToZedRequestClientInterface
    {
        return $this->getProvidedDependency(QuoteApprovalDependencyProvider::CLIENT_ZED_REQUEST);
    }

    /**
     * @return \Spryker\Client\QuoteApproval\Dependency\Client\QuoteApprovalToQuoteClientInterface
     */
    public function getQuoteClient(): QuoteApprovalToQuoteClientInterface
    {
        return $this->getProvidedDependency(QuoteApprovalDependencyProvider::CLIENT_QUOTE);
    }
}
