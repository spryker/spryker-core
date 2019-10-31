<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfileGui\Communication\Controller;

use Generated\Shared\Transfer\MerchantProfileCriteriaFilterTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\MerchantProfileGui\Communication\MerchantProfileGuiCommunicationFactory getFactory()
 */
class EditController extends AbstractController
{
    protected const URL_PARAM_ID_MERCHANT_PROFILE = 'id-merchant-profile';

    protected const MESSAGE_SUCCESS_DEACTIVATE = 'merchant_profile.deactivated';
    protected const MESSAGE_SUCCESS_ACTIVATE = 'merchant_profile.activated';
    protected const MESSAGE_MERCHANT_PROFILE_NOT_FOUND = 'merchant_profile.not_found';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function activateAction(Request $request): RedirectResponse
    {
        $idMerchantProfile = $this->castId($request->query->get(static::URL_PARAM_ID_MERCHANT_PROFILE));

        $merchantProfileFacade = $this->getFactory()
            ->getMerchantProfileFacade();

        $merchantProfileTransfer = $merchantProfileFacade->findOne(
            (new MerchantProfileCriteriaFilterTransfer())
                ->setIdMerchantProfile($idMerchantProfile)
        );

        if (!$merchantProfileTransfer) {
            $this->addErrorMessage(static::MESSAGE_MERCHANT_PROFILE_NOT_FOUND);

            return $this->redirectResponse($request->headers->get('referer'));
        }

        $merchantProfileTransfer->setIsActive(true);

        $merchantProfileFacade->updateMerchantProfile($merchantProfileTransfer);

        $this->addSuccessMessage(static::MESSAGE_SUCCESS_ACTIVATE);

        return $this->redirectResponse($request->headers->get('referer'));
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deactivateAction(Request $request): RedirectResponse
    {
        $idMerchantProfile = $this->castId($request->query->get(static::URL_PARAM_ID_MERCHANT_PROFILE));

        $merchantProfileFacade = $this->getFactory()
            ->getMerchantProfileFacade();

        $merchantProfileTransfer = $merchantProfileFacade->findOne(
            (new MerchantProfileCriteriaFilterTransfer())
                ->setIdMerchantProfile($idMerchantProfile)
        );

        if (!$merchantProfileTransfer) {
            $this->addErrorMessage(static::MESSAGE_MERCHANT_PROFILE_NOT_FOUND);

            return $this->redirectResponse($request->headers->get('referer'));
        }

        $merchantProfileTransfer->setIsActive(false);

        $merchantProfileFacade->updateMerchantProfile($merchantProfileTransfer);

        $this->addSuccessMessage(static::MESSAGE_SUCCESS_DEACTIVATE);

        return $this->redirectResponse($request->headers->get('referer'));
    }
}
