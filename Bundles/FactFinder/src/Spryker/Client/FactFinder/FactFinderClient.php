<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\FactFinder;

use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\FactFinder\FactFinderFactory getFactory()
 */
class FactFinderClient extends AbstractClient implements FactFinderClientInterface
{

    /**
     * @api
     *
     * @return \Generated\Shared\Transfer\FfSearchResponseTransfer
     */
    public function search()
    {
        $ffSearchResponseTransfer = $this->getFactory()
            ->createZedFactFinderStub()
            ->search($this->getQuote());

        return $ffSearchResponseTransfer;
    }

    /**
     * Returns the stored quote
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function getQuote()
    {
        return $this->getSession()->getQuote();
    }

    /**
     * @return \Spryker\Client\Cart\Session\QuoteSessionInterface
     */
    protected function getSession()
    {
        return $this->getFactory()->createSession();
    }

}
