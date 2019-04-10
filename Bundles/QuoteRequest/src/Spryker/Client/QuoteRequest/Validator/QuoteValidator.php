<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\QuoteRequest\Validator;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\Kernel\PermissionAwareTrait;
use Spryker\Client\QuoteRequest\Dependency\Client\QuoteRequestToCompanyUserClientInterface;

class QuoteValidator implements QuoteValidatorInterface
{
    use PermissionAwareTrait;

    /**
     * @var \Spryker\Client\QuoteRequest\Dependency\Client\QuoteRequestToCompanyUserClientInterface
     */
    protected $companyUserClient;

    /**
     * @var \Spryker\Client\QuoteRequestExtension\Dependency\Plugin\QuoteRequestCreatePreCheckPluginInterface[]
     */
    protected $quoteRequestCreatePreCheckPlugins;

    /**
     * @param \Spryker\Client\QuoteRequest\Dependency\Client\QuoteRequestToCompanyUserClientInterface $companyUserClient
     * @param array $quoteRequestCreatePreCheckPlugins
     */
    public function __construct(QuoteRequestToCompanyUserClientInterface $companyUserClient, array $quoteRequestCreatePreCheckPlugins)
    {
        $this->companyUserClient = $companyUserClient;
        $this->quoteRequestCreatePreCheckPlugins = $quoteRequestCreatePreCheckPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function isQuoteApplicableForQuoteRequest(QuoteTransfer $quoteTransfer): bool
    {
        if (!$this->companyUserClient->findCompanyUser()) {
            return false;
        }

        if ($quoteTransfer->getQuoteRequestVersionReference() || $quoteTransfer->getQuoteRequestReference()) {
            return false;
        }

        if (!$this->canWriteQuote($quoteTransfer)) {
            return false;
        }

        return $this->executeQuoteRequestCreatePreCheckPlugins($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function canWriteQuote(QuoteTransfer $quoteTransfer): bool
    {
        return $quoteTransfer->getCustomerReference() === $quoteTransfer->getCustomer()->getCustomerReference()
            || $this->can('WriteSharedCartPermissionPlugin', $quoteTransfer->getIdQuote());
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function executeQuoteRequestCreatePreCheckPlugins(QuoteTransfer $quoteTransfer): bool
    {
        foreach ($this->quoteRequestCreatePreCheckPlugins as $quoteRequestCreatePreCheckPlugin) {
            if (!$quoteRequestCreatePreCheckPlugin->check($quoteTransfer)) {
                return false;
            }
        }

        return true;
    }
}
