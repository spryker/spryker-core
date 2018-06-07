<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantGui\Communication\Controller;

use Generated\Shared\Transfer\MerchantTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Spryker\Zed\MerchantGui\Communication\Table\MerchantTableConstants;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\MerchantGui\Communication\MerchantGuiCommunicationFactory getFactory()
 */
class DeleteMerchantController extends AbstractController
{
    public const URL_PARAM_REDIRECT_URL = 'redirect-url';

    protected const MESSAGE_MERCHANT_DELETE_SUCCESS = 'Merchant deleted successfully.';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request): RedirectResponse
    {
        $idMerchant = $this->castId($request->get(MerchantTableConstants::REQUEST_ID_MERCHANT));
        $redirectUrl = $request->get(static::URL_PARAM_REDIRECT_URL, MerchantTableConstants::URL_MERCHANT_LIST);

        $merchantTransfer = (new MerchantTransfer())
            ->setIdMerchant($idMerchant);
        $this->getFactory()
            ->getMerchantFacade()
            ->deleteMerchant($merchantTransfer);

        $this->addSuccessMessage(static::MESSAGE_MERCHANT_DELETE_SUCCESS);

        return $this->redirectResponse($redirectUrl);
    }
}
