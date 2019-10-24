<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantGui\Communication\Controller;

use Generated\Shared\Transfer\MerchantCriteriaFilterTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\MerchantGui\Communication\MerchantGuiCommunicationFactory getFactory()
 */
class MerchantStatusController extends AbstractController
{
    protected const PARAM_ID_MERCHANT = 'id-merchant';
    protected const PARAM_MERCHANT_STATUS = 'status';

    protected const MESSAGE_ERROR_MERCHANT_WRONG_PARAMETERS = 'merchant_gui.error_wrong_params';
    protected const MESSAGE_SUCCESS_MERCHANT_STATUS_UPDATE = 'merchant_gui.success_merchant_status_update';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request): RedirectResponse
    {
        $idMerchant = $request->query->get(static::PARAM_ID_MERCHANT);
        $newMerchantStatus = $request->query->get(static::PARAM_MERCHANT_STATUS);

        if (!$idMerchant || !$newMerchantStatus) {
            return $this->returnErrorRedirect($request);
        }

        $merchantCriteriaFilterTransfer = new MerchantCriteriaFilterTransfer();
        $merchantCriteriaFilterTransfer->setIdMerchant($idMerchant);
        $merchantTransfer = $this->getFactory()->getMerchantFacade()->findOne($merchantCriteriaFilterTransfer);
        if (!$merchantTransfer) {
            return $this->returnErrorRedirect($request);
        }

        $merchantTransfer->setStatus($newMerchantStatus);

        $merchantResponseTransfer = $this->getFactory()->getMerchantFacade()->updateMerchant($merchantTransfer);

        if (!$merchantResponseTransfer->getIsSuccess()) {
            return $this->returnErrorRedirect($request);
        }

        $this->addSuccessMessage(static::MESSAGE_SUCCESS_MERCHANT_STATUS_UPDATE);

        return $this->redirectResponseExternal($request->headers->get('referer'));
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function returnErrorRedirect(Request $request): RedirectResponse
    {
        $this->addErrorMessage(static::MESSAGE_ERROR_MERCHANT_WRONG_PARAMETERS);

        return $this->redirectResponseExternal($request->headers->get('referer'));
    }
}
