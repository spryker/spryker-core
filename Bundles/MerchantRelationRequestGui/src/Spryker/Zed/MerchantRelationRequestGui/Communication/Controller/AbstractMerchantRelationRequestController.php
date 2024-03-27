<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationRequestGui\Communication\Controller;

use Generated\Shared\Transfer\MerchantRelationRequestCollectionResponseTransfer;
use Generated\Shared\Transfer\MerchantRelationRequestTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Spryker\Zed\MerchantRelationRequestGui\Communication\Table\MerchantRelationRequestListTable;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\MerchantRelationRequestGui\Communication\MerchantRelationRequestGuiCommunicationFactory getFactory()
 */
abstract class AbstractMerchantRelationRequestController extends AbstractController
{
    /**
     * @var string
     */
    protected const ERROR_MESSAGE_MERCHANT_RELATION_REQUEST_DOES_NOT_EXIST = 'Merchant Relation Request with ID "%id%" doesn\'t exist.';

    /**
     * @var string
     */
    protected const ERROR_MESSAGE_PARAMETER_ID = '%id%';

    /**
     * @uses \Spryker\Zed\MerchantRelationRequestGui\Communication\Controller\ListController::indexAction()
     *
     * @var string
     */
    protected const URL_MERCHANT_RELATION_REQUEST_LIST = '/merchant-relation-request-gui/list';

    /**
     * @uses \Spryker\Zed\MerchantRelationRequestGui\Communication\Controller\ListController::indexAction()
     *
     * @var string
     */
    protected const URL_MERCHANT_RELATIONSHIP_REQUEST_EDIT = '/merchant-relation-request-gui/edit?%s=%s';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\MerchantRelationRequestTransfer|null
     */
    protected function findMerchantRelationRequestByRequest(Request $request): ?MerchantRelationRequestTransfer
    {
        $idMerchantRelationRequest = $this->castId($request->get(MerchantRelationRequestListTable::PARAM_ID_MERCHANT_RELATION_REQUEST));
        $merchantRelationRequestTransfer = $this->getFactory()
            ->createMerchantRelationRequestReader()
            ->findMerchantRelationRequestByIdMerchantRelationRequest($idMerchantRelationRequest);

        if ($merchantRelationRequestTransfer === null) {
            $this->addErrorMessage(
                static::ERROR_MESSAGE_MERCHANT_RELATION_REQUEST_DOES_NOT_EXIST,
                [
                    static::ERROR_MESSAGE_PARAMETER_ID => $idMerchantRelationRequest,
                ],
            );
        }

        return $merchantRelationRequestTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationRequestTransfer $merchantRelationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationRequestCollectionResponseTransfer
     */
    protected function updateMerchantRelationRequest(
        MerchantRelationRequestTransfer $merchantRelationRequestTransfer
    ): MerchantRelationRequestCollectionResponseTransfer {
        $merchantRelationRequestCollectionResponseTransfer = $this->getFactory()
            ->createMerchantRelationRequestUpdater()
            ->updateMerchantRelationRequest($merchantRelationRequestTransfer);

        if ($merchantRelationRequestCollectionResponseTransfer->getErrors()->count()) {
            foreach ($merchantRelationRequestCollectionResponseTransfer->getErrors() as $errorTransfer) {
                $this->addErrorMessage($errorTransfer->getMessageOrFail());
            }
        }

        return $merchantRelationRequestCollectionResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationRequestTransfer $merchantRelationRequestTransfer
     *
     * @return string
     */
    protected function getEditPageUrl(MerchantRelationRequestTransfer $merchantRelationRequestTransfer): string
    {
        return sprintf(
            static::URL_MERCHANT_RELATIONSHIP_REQUEST_EDIT,
            MerchantRelationRequestListTable::PARAM_ID_MERCHANT_RELATION_REQUEST,
            $merchantRelationRequestTransfer->getIdMerchantRelationRequestOrFail(),
        );
    }
}
