<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipMerchantPortalGui\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Spryker\Zed\MerchantRelationshipMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\MerchantRelationshipGuiTableConfigurationProvider;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @method \Spryker\Zed\MerchantRelationshipMerchantPortalGui\Communication\MerchantRelationshipMerchantPortalGuiCommunicationFactory getFactory()
 */
class DeleteMerchantRelationshipController extends AbstractController
{
    /**
     * @var string
     */
    protected const RESPONSE_NOTIFICATION_MESSAGE_SUCCESS = 'Merchant Relation is deleted';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function indexAction(Request $request): JsonResponse
    {
        $idMerchantRelationship = $this->castId(
            $request->get(MerchantRelationshipGuiTableConfigurationProvider::PARAM_MERCHANT_RELATIONSHIP_ID),
        );
        $merchantRelationshipTransfer = $this->getFactory()
            ->createMerchantRelationshipReader()
            ->findMerchantRelationshipById($idMerchantRelationship);

        if (!$merchantRelationshipTransfer) {
            throw new NotFoundHttpException(sprintf('Merchant relation is not found for ID %d.', $idMerchantRelationship));
        }

        $merchantRelationshipDeleteForm = $this->getFactory()->createMerchantRelationshipDeleteForm();
        $merchantRelationshipDeleteForm->handleRequest($request);

        $responseData = [];
        if ($merchantRelationshipDeleteForm->isSubmitted()) {
            $this->getFactory()
                ->createMerchantRelationshipDeleter()
                ->deleteMerchantRelationship($merchantRelationshipTransfer);

            $responseData = $this->getFactory()
                ->createMerchantRelationshipResponseBuilder()
                ->addSuccessfulResponseDataToResponse(
                    $responseData,
                    static::RESPONSE_NOTIFICATION_MESSAGE_SUCCESS,
                );
        }

        return $this->jsonResponse($responseData);
    }
}
