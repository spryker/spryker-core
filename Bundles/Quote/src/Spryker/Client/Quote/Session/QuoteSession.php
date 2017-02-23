<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Quote\Session;

use Generated\Shared\Transfer\QuoteTransfer;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class QuoteSession implements QuoteSessionInterface
{

    const QUOTE_SESSION_IDENTIFIER = 'quote session identifier';

    /**
     * @var \Symfony\Component\HttpFoundation\Session\SessionInterface
     */
    protected $session;

    /**
     * @param \Symfony\Component\HttpFoundation\Session\SessionInterface $session
     */
    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function getQuote()
    {
        $quoteTransfer = new QuoteTransfer();

        return $this->session->get(static::QUOTE_SESSION_IDENTIFIER, $quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function setQuote(QuoteTransfer $quoteTransfer)
    {
        $this->session->set(static::QUOTE_SESSION_IDENTIFIER, $quoteTransfer);
    }

    /**
     * @return $this
     */
    public function clearQuote()
    {
        $this->setQuote(new QuoteTransfer());

        return $this;
    }

}
