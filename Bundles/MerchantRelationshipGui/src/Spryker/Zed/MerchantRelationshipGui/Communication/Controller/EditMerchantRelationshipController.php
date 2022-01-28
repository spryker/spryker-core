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
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\MerchantRelationshipGui\Communication\MerchantRelationshipGuiCommunicationFactory getFactory()
 */
class EditMerchantRelationshipController extends AbstractController
{
    /**
     * @var string
     */
    public const URL_PARAM_REDIRECT_URL = 'redirect-url';

    /**
     * @var string
     */
    protected const MESSAGE_MERCHANT_RELATIONSHIP_UPDATE_SUCCESS = 'Merchant relation updated successfully.';

    /**
     * @var string
     */
    protected const MESSAGE_MERCHANT_RELATIONSHIP_NOT_FOUND = 'Merchant relation is not found.';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array
     */
    public function indexAction(Request $request)
    {
        $idMerchantRelationship = $this->castId($request->get(MerchantRelationshipTableConstants::REQUEST_ID_MERCHANT_RELATIONSHIP));

        $dataProvider = $this->getFactory()->createMerchantRelationshipFormDataProvider();
        $merchantRelationshipTransfer = $dataProvider->getData($idMerchantRelationship);

        if ($merchantRelationshipTransfer === null) {
            $this->addErrorMessage("Merchant Relationship with id %s doesn't exists.", ['%s' => $idMerchantRelationship]);

            return $this->redirectResponse(MerchantRelationshipTableConstants::URL_MERCHANT_RELATIONSHIP_LIST);
        }

        $idCompany = $this->getIdCompanyFromTransfer($merchantRelationshipTransfer);
        $merchantRelationshipForm = $this->getFactory()
            ->getMerchantRelationshipEditForm(
                $merchantRelationshipTransfer,
                $dataProvider->getOptions(true, $idCompany),
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
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array
     */
    protected function updateMerchantRelationship(Request $request, FormInterface $merchantRelationshipForm)
    {
        $redirectUrl = $request->get(static::URL_PARAM_REDIRECT_URL, MerchantRelationshipTableConstants::URL_MERCHANT_RELATIONSHIP_LIST);
        $merchantRelationshipTransfer = $merchantRelationshipForm->getData();
        $merchantRelationshipRequestTransfer = (new MerchantRelationshipRequestTransfer())->setMerchantRelationship($merchantRelationshipTransfer);

        /** @var \Generated\Shared\Transfer\MerchantRelationshipResponseTransfer $merchantRelationshipResponseTransfer */
        $merchantRelationshipResponseTransfer = $this->getFactory()
            ->getMerchantRelationshipFacade()
            ->updateMerchantRelationship(
                $merchantRelationshipTransfer,
                $merchantRelationshipRequestTransfer,
            );

        if (!$merchantRelationshipResponseTransfer->getIsSuccessfulOrFail()) {
            foreach ($merchantRelationshipResponseTransfer->getErrors() as $merchantRelationshipErrorTransfer) {
                $this->addErrorMessage($merchantRelationshipErrorTransfer->getMessageOrFail());
            }

            return $this->viewResponse([
                'form' => $merchantRelationshipForm->createView(),
            ]);
        }

        $this->addSuccessMessage(static::MESSAGE_MERCHANT_RELATIONSHIP_UPDATE_SUCCESS);

        return $this->redirectResponse($redirectUrl);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationshipTransfer $merchantRelationshipTransfer
     *
     * @return int|null
     */
    protected function getIdCompanyFromTransfer(MerchantRelationshipTransfer $merchantRelationshipTransfer): ?int
    {
        if ($merchantRelationshipTransfer->getOwnerCompanyBusinessUnit()) {
            return $merchantRelationshipTransfer->getOwnerCompanyBusinessUnit()->getFkCompany();
        }

        return null;
    }
}
