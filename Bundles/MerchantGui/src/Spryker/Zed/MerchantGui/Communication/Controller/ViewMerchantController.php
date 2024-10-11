<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantGui\Communication\Controller;

use Generated\Shared\Transfer\MerchantCriteriaTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Spryker\Zed\MerchantGui\MerchantGuiConfig;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\MerchantGui\Communication\MerchantGuiCommunicationFactory getFactory()
 */
class ViewMerchantController extends AbstractController
{
    /**
     * @var string
     */
    protected const REQUEST_ID_MERCHANT = 'id-merchant';

    /**
     * @var string
     */
    protected const MESSAGE_MERCHANT_NOT_FOUND = 'Merchant with id `%d` doesn\'t exist.';

    /**
     * @var string
     */
    protected const ID_MERCHANT_PLACEHOLDER = '%d';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return mixed
     */
    public function indexAction(Request $request)
    {
        $idMerchant = $this->castId($request->get(
            static::REQUEST_ID_MERCHANT,
        ));

        $merchantCriteriaTransfer = new MerchantCriteriaTransfer();
        $merchantCriteriaTransfer->setIdMerchant($idMerchant);
        $merchantTransfer = $this->getFactory()->getMerchantFacade()->findOne($merchantCriteriaTransfer);

        if ($merchantTransfer === null) {
            $this->addErrorMessage(static::MESSAGE_MERCHANT_NOT_FOUND, [static::ID_MERCHANT_PLACEHOLDER => $idMerchant]);

            return $this->redirectResponse(MerchantGuiConfig::URL_MERCHANT_LIST);
        }

        return $this->viewResponse([
            'idMerchant' => $idMerchant,
            'merchant' => $merchantTransfer,
            'merchantProfile' => $merchantTransfer->getMerchantProfile(),
            'storeRelations' => $merchantTransfer->getStoreRelation() ? $merchantTransfer->getStoreRelation()->getStores() : [],
            'merchantUrlCollection' => $merchantTransfer->getUrlCollection(),
            'merchantStockCollection' => $merchantTransfer->getStocks(),
            'merchantAddressCollection' => $merchantTransfer->getMerchantProfile() ? $merchantTransfer->getMerchantProfile()->getAddressCollection() : [],
            'merchantLocalizedAttributes' => $merchantTransfer->getMerchantProfile() ? $merchantTransfer->getMerchantProfile()->getMerchantProfileLocalizedGlossaryAttributes() : [],
            'toggleActiveForm' => $this->getFactory()->createToggleActiveMerchantForm()->createView(),
            'toggleStatusForm' => $this->getFactory()->createMerchantStatusForm()->createView(),
            'merchantUserTable' => $this->getFactory()->createMerchantViewForm($merchantTransfer)->createView()->vars['tables']['merchantUsersTable'],
        ]);
    }
}
