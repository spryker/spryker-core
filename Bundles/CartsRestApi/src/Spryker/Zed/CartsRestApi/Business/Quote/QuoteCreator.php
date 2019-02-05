<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartsRestApi\Business\Quote;

use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\RestQuoteRequestTransfer;
use Spryker\Zed\CartsRestApi\Business\Quote\Mapper\QuoteMapperInterface;
use Spryker\Zed\CartsRestApiExtension\Dependency\Plugin\QuoteCreatorPluginInterface;

class QuoteCreator implements QuoteCreatorInterface
{
    /**
     * @var \Spryker\Zed\CartsRestApiExtension\Dependency\Plugin\QuoteCreatorPluginInterface
     */
    protected $quoteCreatorPlugin;

    /**
     * @var \Spryker\Zed\CartsRestApi\Business\Quote\QuoteReaderInterface
     */
    protected $quoteReader;

    /**
     * @var \Spryker\Zed\CartsRestApi\Business\Quote\Mapper\QuoteMapperInterface
     */
    protected $quoteMapper;

    /**
     * @param \Spryker\Zed\CartsRestApiExtension\Dependency\Plugin\QuoteCreatorPluginInterface $quoteCreatorPlugin
     * @param \Spryker\Zed\CartsRestApi\Business\Quote\QuoteReaderInterface $quoteReader
     * @param \Spryker\Zed\CartsRestApi\Business\Quote\Mapper\QuoteMapperInterface $quoteMapper
     */
    public function __construct(
        QuoteCreatorPluginInterface $quoteCreatorPlugin,
        QuoteReaderInterface $quoteReader,
        QuoteMapperInterface $quoteMapper
    ) {
        $this->quoteCreatorPlugin = $quoteCreatorPlugin;
        $this->quoteReader = $quoteReader;
        $this->quoteMapper = $quoteMapper;
    }

    /**
     * @param \Generated\Shared\Transfer\RestQuoteRequestTransfer $restQuoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function createQuote(RestQuoteRequestTransfer $restQuoteRequestTransfer): QuoteResponseTransfer
    {
        $restQuoteRequestTransfer
            ->requireQuote()
            ->requireCustomerReference();

        $quoteResponseTransfer = $this->quoteCreatorPlugin->createQuote($restQuoteRequestTransfer);

        if (!$quoteResponseTransfer->getIsSuccessful()) {
            return $this->quoteMapper->mapQuoteResponseErrorsToRestCodes(
                $quoteResponseTransfer
            );
        }

        return $quoteResponseTransfer;
    }
}
