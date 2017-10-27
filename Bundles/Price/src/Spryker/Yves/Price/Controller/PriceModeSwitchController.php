<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Price\Controller;

use Spryker\Yves\Kernel\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Yves\Price\PriceFactory getFactory()
 */
class PriceModeSwitchController extends AbstractController
{
    const URL_PARAM_PRICE_MODE = 'price-mode';
    const URL_PARAM_REFERRER_URL = 'referrer-url';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $priceMode = $request->get(static::URL_PARAM_PRICE_MODE);

        $quoteTransfer = $this->getFactory()->getQuoteClient()->getQuote();
        if (count($quoteTransfer->getItems()) > 0) {
            $this->addErrorMessage("Can't switch price mode when there is items in the cart");
            return $this->createRedirectResponse($request);
        }

        $this->getFactory()
            ->createPriceModeSwitcher()
            ->switchPriceMode($priceMode);

        return $this->createRedirectResponse($request);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function createRedirectResponse(Request $request)
    {
        return $this->redirectResponseExternal(
            urldecode($request->get(static::URL_PARAM_REFERRER_URL))
        );
    }
}
