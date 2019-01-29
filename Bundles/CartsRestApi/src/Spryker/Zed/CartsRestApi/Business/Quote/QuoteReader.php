<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartsRestApi\Business\Quote;

use Generated\Shared\Transfer\QuoteCollectionResponseTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RestQuoteCollectionRequestTransfer;
use Spryker\Zed\CartsRestApi\Dependency\Facade\CartsRestApiToQuoteFacadeInterface;
use Spryker\Zed\CartsRestApiExtension\Dependency\Plugin\QuoteCollectionReaderPluginInterface;

class QuoteReader implements QuoteReaderInterface
{
    /**
     * @var \Spryker\Zed\CartsRestApi\Dependency\Facade\CartsRestApiToQuoteFacadeInterface
     */
    protected $quoteFacade;

    /**
     * @var \Spryker\Zed\CartsRestApiExtension\Dependency\Plugin\QuoteCollectionReaderPluginInterface
     */
    protected $quoteCollectionReaderPlugin;

    /**
     * @param \Spryker\Zed\CartsRestApi\Dependency\Facade\CartsRestApiToQuoteFacadeInterface $quoteFacade
     * @param \Spryker\Zed\CartsRestApiExtension\Dependency\Plugin\QuoteCollectionReaderPluginInterface $quoteCollectionReaderPlugin
     */
    public function __construct(
        CartsRestApiToQuoteFacadeInterface $quoteFacade,
        QuoteCollectionReaderPluginInterface $quoteCollectionReaderPlugin
    ) {
        $this->quoteFacade = $quoteFacade;
        $this->quoteCollectionReaderPlugin = $quoteCollectionReaderPlugin;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function findQuoteByUuid(QuoteTransfer $quoteTransfer): QuoteResponseTransfer
    {
        $quoteTransfer->requireUuid();

        return $this->quoteFacade->findQuoteByUuid($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\RestQuoteCollectionRequestTransfer $restQuoteCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteCollectionResponseTransfer
     */
    public function getCustomerQuoteCollection(
        RestQuoteCollectionRequestTransfer $restQuoteCollectionRequestTransfer
    ): QuoteCollectionResponseTransfer {
        return $this->quoteCollectionReaderPlugin->getQuoteCollection($restQuoteCollectionRequestTransfer);
    }
}
