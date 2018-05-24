<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipGui\Communication\Controller;

use Generated\Shared\Transfer\MerchantRelationshipTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Spryker\Zed\MerchantRelationshipGui\Communication\Table\MerchantRelationshipTableConstants;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\MerchantRelationshipGui\Communication\MerchantRelationshipGuiCommunicationFactory getFactory()
 */
class DeleteMerchantRelationshipController extends AbstractController
{
    public const URL_PARAM_REDIRECT_URL = 'redirect-url';

    protected const MESSAGE_MERCHANT_RELATIONSHIP_DELETE_SUCCESS = 'Merchant relationship has been deleted.';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request): RedirectResponse
    {
        $idMerchantRelationship = $this->castId($request->get(MerchantRelationshipTableConstants::REQUEST_ID_MERCHANT_RELATIONSHIP));
        $redirectUrl = $request->get(static::URL_PARAM_REDIRECT_URL, MerchantRelationshipTableConstants::URL_MERCHANT_RELATIONSHIP_LIST);

        $merchantRelationshipTransfer = (new MerchantRelationshipTransfer())
            ->setIdMerchantRelationship($idMerchantRelationship);
        $this->getFactory()
            ->getMerchantRelationshipFacade()
            ->deleteMerchantRelationship($merchantRelationshipTransfer);

        $this->addSuccessMessage(static::MESSAGE_MERCHANT_RELATIONSHIP_DELETE_SUCCESS);

        return $this->redirectResponse($redirectUrl);
    }
}
