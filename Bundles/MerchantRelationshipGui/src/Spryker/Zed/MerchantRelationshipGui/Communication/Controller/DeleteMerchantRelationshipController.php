<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipGui\Communication\Controller;

use Generated\Shared\Transfer\MerchantRelationshipRequestTransfer;
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
    /**
     * @var string
     */
    public const URL_PARAM_REDIRECT_URL = 'redirect-url';

    /**
     * @var string
     */
    protected const MESSAGE_MERCHANT_RELATIONSHIP_DELETE_SUCCESS = 'Merchant relation deleted successfully.';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request): RedirectResponse
    {
        $idMerchantRelationship = $this->castId($request->get(MerchantRelationshipTableConstants::REQUEST_ID_MERCHANT_RELATIONSHIP));
        $redirectUrl = $request->get(static::URL_PARAM_REDIRECT_URL, MerchantRelationshipTableConstants::URL_MERCHANT_RELATIONSHIP_LIST);

        $form = $this->getFactory()->createDeleteMerchantRelationshipForm()->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            $this->addErrorMessage('CSRF token is not valid');

            return $this->redirectResponse($redirectUrl);
        }

        $merchantRelationshipTransfer = (new MerchantRelationshipTransfer())
            ->setIdMerchantRelationship($idMerchantRelationship);
        $merchantRelationshipRequestTransfer = (new MerchantRelationshipRequestTransfer())->setMerchantRelationship($merchantRelationshipTransfer);

        $this->getFactory()
            ->getMerchantRelationshipFacade()
            ->deleteMerchantRelationship(
                $merchantRelationshipTransfer,
                $merchantRelationshipRequestTransfer,
            );

        $this->addSuccessMessage(static::MESSAGE_MERCHANT_RELATIONSHIP_DELETE_SUCCESS);

        return $this->redirectResponse($redirectUrl);
    }
}
