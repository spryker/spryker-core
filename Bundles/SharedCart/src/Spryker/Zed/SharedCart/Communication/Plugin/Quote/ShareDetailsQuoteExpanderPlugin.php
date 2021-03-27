<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SharedCart\Communication\Plugin\Quote;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShareCartRequestTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\QuoteExtension\Dependency\Plugin\QuoteExpanderPluginInterface;
use Spryker\Zed\QuoteExtension\Dependency\Plugin\QuotePostExpanderPluginInterface;
use Spryker\Zed\QuoteExtension\Dependency\Plugin\QuotePreExpanderPluginInterface;

/**
 * @method \Spryker\Zed\SharedCart\Business\SharedCartFacadeInterface getFacade()
 * @method \Spryker\Zed\SharedCart\SharedCartConfig getConfig()
 * @method \Spryker\Zed\SharedCart\Communication\SharedCartCommunicationFactory getFactory()
 */
class ShareDetailsQuoteExpanderPlugin extends AbstractPlugin implements QuoteExpanderPluginInterface, QuotePreExpanderPluginInterface, QuotePostExpanderPluginInterface
{
    /**
     * @var int[]
     */
    protected $quoteIds = [];

    /**
     * @var \Generated\Shared\Transfer\ShareDetailTransfer[]
     */
    protected $quoteShareDetailsByIdQuote = [];

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function preExpand(QuoteTransfer $quoteTransfer): void
    {
        $this->quoteIds[] = $quoteTransfer->getIdQuoteOrFail();
    }

    /**
     * {@inheritDoc}
     * - Expands quote transfer with shared details information.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function expand(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        $this->preloadShareDetails();

        $shareDetailTransfers = $this->getShareDetailsByIdQuote($quoteTransfer);

        $quoteTransfer->setShareDetails($shareDetailTransfers);

        return $quoteTransfer;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return void
     */
    public function postExpand(): void
    {
        $this->quoteIds = [];
        $this->quoteShareDetailsByIdQuote = [];
    }

    /**
     * @return void
     */
    protected function preloadShareDetails(): void
    {
        if ($this->quoteShareDetailsByIdQuote !== []) {
            return;
        }

        $shareDetailsRequestTransfer = new ShareCartRequestTransfer();
        $shareDetailsRequestTransfer->setQuoteIds($this->quoteIds);

        $this->quoteShareDetailsByIdQuote = $this->getFacade()->getSharedCartDetails($shareDetailsRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\ShareDetailTransfer[]
     */
    protected function getShareDetailsByIdQuote(QuoteTransfer $quoteTransfer)
    {
        $idQuote = $quoteTransfer->getIdQuote();
        if (!isset($this->quoteShareDetailsByIdQuote[$idQuote])) {
            $this->quoteShareDetailsByIdQuote[$idQuote] = $this->getFacade()->getShareDetailsByIdQuote($quoteTransfer)->getShareDetails();
        }

        return $this->quoteShareDetailsByIdQuote[$idQuote];
    }
}
