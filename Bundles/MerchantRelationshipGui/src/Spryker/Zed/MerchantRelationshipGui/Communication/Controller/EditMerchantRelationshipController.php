<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipGui\Communication\Controller;

use Generated\Shared\Transfer\MerchantRelationshipTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Spryker\Zed\MerchantRelationship\Business\Exception\MerchantRelationshipNotFoundException;
use Spryker\Zed\MerchantRelationshipGui\Communication\Table\MerchantRelationshipTableConstants;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\MerchantRelationshipGui\Communication\MerchantRelationshipGuiCommunicationFactory getFactory()
 */
class EditMerchantRelationshipController extends AbstractController
{
    public const URL_PARAM_REDIRECT_URL = 'redirect-url';

    protected const MESSAGE_MERCHANT_RELATIONSHIP_UPDATE_SUCCESS = 'Merchant relation updated successfully.';
    protected const MESSAGE_MERCHANT_RELATIONSHIP_NOT_FOUND = 'Merchant relation is not found.';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $idMerchantRelationship = $this->castId($request->get(MerchantRelationshipTableConstants::REQUEST_ID_MERCHANT_RELATIONSHIP));

        $dataProvider = $this->getFactory()->createMerchantRelationshipFormDataProvider();
        $merchantRelationshipTransfer = $dataProvider->getData($idMerchantRelationship);
        $idCompany = $this->getCompanyIdFromTransfer($merchantRelationshipTransfer);
        $merchantRelationshipForm = $this->getFactory()
            ->getMerchantRelationshipEditForm(
                $merchantRelationshipTransfer,
                $dataProvider->getOptions($idCompany)
            )
            ->handleRequest($request);

        if ($merchantRelationshipForm->isSubmitted() && $merchantRelationshipForm->isValid()) {
            return $this->updateMerchantRelationship($request, $merchantRelationshipForm);
        }

        return $this->viewResponse([
            'form' => $merchantRelationshipForm->createView(),
            'merchantRelationshipTransfer' => $merchantRelationshipTransfer,
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\Form\FormInterface $merchantRelationshipForm
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function updateMerchantRelationship(Request $request, FormInterface $merchantRelationshipForm)
    {
        $redirectUrl = $request->get(static::URL_PARAM_REDIRECT_URL, MerchantRelationshipTableConstants::URL_MERCHANT_RELATIONSHIP_LIST);
        $merchantRelationshipTransfer = $merchantRelationshipForm->getData();
        try {
            $this->getFactory()
                ->getMerchantRelationshipFacade()
                ->updateMerchantRelationship($merchantRelationshipTransfer);

            $this->addSuccessMessage(static::MESSAGE_MERCHANT_RELATIONSHIP_UPDATE_SUCCESS);
        } catch (MerchantRelationshipNotFoundException $exception) {
            $this->addErrorMessage(static::MESSAGE_MERCHANT_RELATIONSHIP_NOT_FOUND);
        }

        return $this->redirectResponse($redirectUrl);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     *
     * @return int|null
     */
    protected function getCompanyIdFromTransfer(MerchantRelationshipTransfer $merchantRelationshipTransfer): ?int
    {
        if ($merchantRelationshipTransfer->getOwnerCompanyBusinessUnit()) {
            return $merchantRelationshipTransfer->getOwnerCompanyBusinessUnit()->getFkCompany();
        }

        return null;
    }
}
