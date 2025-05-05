<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderAmendment\Communication\Plugin\QuoteRequest;

use Generated\Shared\Transfer\QuoteRequestResponseTransfer;
use Generated\Shared\Transfer\QuoteRequestTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\QuoteRequestExtension\Dependency\Plugin\QuoteRequestValidatorPluginInterface;

/**
 * @method \Spryker\Zed\SalesOrderAmendment\SalesOrderAmendmentConfig getConfig()
 * @method \Spryker\Zed\SalesOrderAmendment\Business\SalesOrderAmendmentBusinessFactory getBusinessFactory()
 * @method \Spryker\Zed\SalesOrderAmendment\Business\SalesOrderAmendmentFacadeInterface getFacade()
 */
class OrderAmendmentQuoteRequestValidatorPlugin extends AbstractPlugin implements QuoteRequestValidatorPluginInterface
{
    /**
     * {@inheritDoc}
     * - Prevents create/update of quote request with quote in order amendment process.
     * - Expects `QuoteRequestTransfer.latestVersion` and `QuoteRequestTransfer.latestVersion.quote` to be set.
     * - Returns "QuoteRequestResponseTransfer.isSuccessful=true" if quote is in amendment process.
     * - Returns "QuoteRequestResponseTransfer.isSuccessful=false" and adds an error message otherwise.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestResponseTransfer
     */
    public function validate(QuoteRequestTransfer $quoteRequestTransfer): QuoteRequestResponseTransfer
    {
        return $this->getBusinessFactory()->createQuoteRequestValidator()->validate($quoteRequestTransfer);
    }
}
