<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Client\Cart\Session;

use Generated\Shared\Transfer\QuoteTransfer;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class QuoteSession implements QuoteSessionInterface
{

    const QUOTE_SESSION_IDENTIFIER = 'quote session identifier';
    const QUOTE_SESSION_ITEM_COUNT_IDENTIFIER = 'quote item count session identifier';

    /**
     * @var \Symfony\Component\HttpFoundation\Session\SessionInterface
     */
    private $session;

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

        if ($this->session->has(self::QUOTE_SESSION_IDENTIFIER)) {
            return $this->session->get(self::QUOTE_SESSION_IDENTIFIER, $quoteTransfer);
        }

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Spryker\Client\Cart\Session\QuoteSession
     */
    public function setQuote(QuoteTransfer $quoteTransfer)
    {
        $this->session->set(self::QUOTE_SESSION_IDENTIFIER, $quoteTransfer);

        return $this;
    }

    /**
     * @return int
     */
    public function getItemCount()
    {
        if (!$this->session->has(self::QUOTE_SESSION_ITEM_COUNT_IDENTIFIER)) {
            return 0;
        }

        return $this->session->get(self::QUOTE_SESSION_ITEM_COUNT_IDENTIFIER);
    }

    /**
     * @param $itemCount
     *
     * @return \Spryker\Client\Cart\Session\QuoteSession
     */
    public function setItemCount($itemCount)
    {
        $this->session->set(self::QUOTE_SESSION_ITEM_COUNT_IDENTIFIER, $itemCount);

        return $this;
    }

    /**
     * @return void
     */
    public function clearQuote()
    {
        $this->setItemCount(0)->setQuote(new QuoteTransfer());
    }

}
