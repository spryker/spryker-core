<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipGui\Communication\Controller;

use Generated\Shared\Transfer\MerchantRelationshipConditionsTransfer;
use Generated\Shared\Transfer\MerchantRelationshipCriteriaTransfer;
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
    protected const REQUEST_PARAMETER_ID_MERCHANT_RELATIONSHIP = 'id-merchant-relationship';

    /**
     * @uses \Spryker\Zed\MerchantRelationshipGui\Communication\Controller\ListMerchantRelationshipController::indexAction()
     *
     * @var string
     */
    protected const ROUTE_LIST_MERCHANT_RELATIONSHIP = '/merchant-relationship-gui/list-merchant-relationship';

    /**
     * @uses \Spryker\Zed\MerchantRelationshipGui\Communication\Controller\DeleteMerchantRelationshipController::indexAction()
     *
     * @var string
     */
    protected const ROUTE_DELETE_MERCHANT_RELATIONSHIP = '/merchant-relationship-gui/delete-merchant-relationship';

    /**
     * @var string
     */
    protected const ERROR_MESSAGE_MERCHANT_RELATION_DOES_NOT_EXIST = 'Merchant Relation with ID "%id%" doesn\'t exist.';

    /**
     * @var string
     */
    protected const ERROR_MESSAGE_PARAMETER_ID = '%id%';

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
        $idMerchantRelationship = $this->castId($request->get(static::REQUEST_PARAMETER_ID_MERCHANT_RELATIONSHIP));
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

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array<string, mixed>
     */
    public function confirmAction(Request $request): RedirectResponse|array
    {
        $idMerchantRelationship = $this->castId(
            $request->query->get(static::REQUEST_PARAMETER_ID_MERCHANT_RELATIONSHIP),
        );

        $merchantRelationshipConditionsTransfer = (new MerchantRelationshipConditionsTransfer())
            ->addIdMerchantRelationship($idMerchantRelationship);
        $merchantRelationshipCriteriaTransfer = (new MerchantRelationshipCriteriaTransfer())
            ->setMerchantRelationshipConditions($merchantRelationshipConditionsTransfer);

        /** @var \Generated\Shared\Transfer\MerchantRelationshipCollectionTransfer $merchantRelationshipCollectionTransfer */
        $merchantRelationshipCollectionTransfer = $this->getFactory()->getMerchantRelationshipFacade()->getMerchantRelationshipCollection(
            null,
            $merchantRelationshipCriteriaTransfer,
        );

        $merchantRelationshipTransfers = $merchantRelationshipCollectionTransfer->getMerchantRelationships();

        if (!$merchantRelationshipTransfers->count()) {
            $this->addErrorMessage(static::ERROR_MESSAGE_MERCHANT_RELATION_DOES_NOT_EXIST, [
                static::ERROR_MESSAGE_PARAMETER_ID => $idMerchantRelationship,
            ]);

            return $this->redirectResponse(static::ROUTE_LIST_MERCHANT_RELATIONSHIP);
        }

        return $this->viewResponse([
            'deleteMerchantRelationshipRoute' => static::ROUTE_DELETE_MERCHANT_RELATIONSHIP,
            'merchantRelationshipTransfer' => $merchantRelationshipTransfers->getIterator()->current(),
            'deleteMerchantRelationshipForm' => $this->getFactory()->createDeleteMerchantRelationshipForm()->createView(),
        ]);
    }
}
