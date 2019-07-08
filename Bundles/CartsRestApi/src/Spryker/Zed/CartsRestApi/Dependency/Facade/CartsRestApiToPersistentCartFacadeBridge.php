<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartsRestApi\Dependency\Facade;

use Generated\Shared\Transfer\PersistentCartChangeQuantityTransfer;
use Generated\Shared\Transfer\PersistentCartChangeTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\QuoteUpdateRequestTransfer;

class CartsRestApiToPersistentCartFacadeBridge implements CartsRestApiToPersistentCartFacadeInterface
{
    /**
     * @var \Spryker\Zed\PersistentCart\Business\PersistentCartFacadeInterface
     */
    protected $persistentCartFacade;

    /**
     * @param \Spryker\Zed\PersistentCart\Business\PersistentCartFacadeInterface $persistentCartFacade
     */
    public function __construct($persistentCartFacade)
    {
        $this->persistentCartFacade = $persistentCartFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteUpdateRequestTransfer $quoteUpdateRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function updateQuote(QuoteUpdateRequestTransfer $quoteUpdateRequestTransfer): QuoteResponseTransfer
    {
        return $this->persistentCartFacade->updateQuote($quoteUpdateRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function createQuote(QuoteTransfer $quoteTransfer): QuoteResponseTransfer
    {
        return $this->persistentCartFacade->createQuote($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function deleteQuote(QuoteTransfer $quoteTransfer): QuoteResponseTransfer
    {
        return $this->persistentCartFacade->deleteQuote($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\PersistentCartChangeTransfer $persistentCartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function remove(PersistentCartChangeTransfer $persistentCartChangeTransfer): QuoteResponseTransfer
    {
        return $this->persistentCartFacade->remove($persistentCartChangeTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\PersistentCartChangeQuantityTransfer $persistentCartChangeQuantityTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function changeItemQuantity(PersistentCartChangeQuantityTransfer $persistentCartChangeQuantityTransfer): QuoteResponseTransfer
    {
        return $this->persistentCartFacade->changeItemQuantity($persistentCartChangeQuantityTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function validateQuote(QuoteTransfer $quoteTransfer): QuoteResponseTransfer
    {
        return $this->persistentCartFacade->validateQuote($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\PersistentCartChangeTransfer $persistentCartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function add(PersistentCartChangeTransfer $persistentCartChangeTransfer): QuoteResponseTransfer
    {
        return $this->persistentCartFacade->add($persistentCartChangeTransfer);
    }
}
