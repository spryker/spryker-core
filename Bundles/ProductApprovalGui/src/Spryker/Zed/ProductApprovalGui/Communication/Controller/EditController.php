<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductApprovalGui\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ProductApprovalGui\Communication\ProductApprovalGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductApprovalGui\Persistence\ProductApprovalGuiRepositoryInterface getRepository()
 */
class EditController extends AbstractController
{
    /**
     * @uses \Spryker\Zed\ProductApprovalGui\Communication\Expander\ProductApprovalProductTableActionExpander::PARAM_APPROVAL_STATUS
     *
     * @var string
     */
    protected const PARAM_APPROVAL_STATUS = 'approval-status';

    /**
     * @uses \Spryker\Zed\ProductApprovalGui\Communication\Expander\ProductApprovalProductTableActionExpander::PARAM_ID_PRODUCT_ABSTRACT
     *
     * @var string
     */
    protected const PARAM_ID_PRODUCT_ABSTRACT = 'id-product-abstract';

    /**
     * @var string
     */
    protected const MESSAGE_PRODUCT_ABSTRACT_APPROVAL_STATUS_WAS_NOT_UPDATED = 'The approval status was not updated.';

    /**
     * @uses \Spryker\Shared\ProductApproval\ProductApprovalConfig::STATUS_DRAFT
     *
     * @var string
     */
    protected const STATUS_DRAFT = 'draft';

    /**
     * @see \Spryker\Zed\ProductManagement\Communication\Controller\IndexController::indexAction()
     *
     * @var string
     */
    protected const URL_PRODUCTS = '/product-management';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function updateApprovalStatusAction(Request $request): RedirectResponse
    {
        $idProductAbstract = $this->castId($request->get(static::PARAM_ID_PRODUCT_ABSTRACT));
        $approvalStatus = $request->get(static::PARAM_APPROVAL_STATUS);

        $productAbstractTransfer = $this->getFactory()
            ->getProductFacade()
            ->findProductAbstractById($idProductAbstract);

        if (!$productAbstractTransfer) {
            $this->addErrorMessage('Abstract product was not found.');

            return $this->redirectResponse(static::URL_PRODUCTS);
        }

        $applicableApprovalStatuses = $this->getFactory()
            ->getProductApprovalFacade()
            ->getApplicableApprovalStatuses($productAbstractTransfer->getApprovalStatus() ?: static::STATUS_DRAFT);

        if (!in_array($approvalStatus, $applicableApprovalStatuses, true)) {
            $this->addErrorMessage(static::MESSAGE_PRODUCT_ABSTRACT_APPROVAL_STATUS_WAS_NOT_UPDATED);

            return $this->redirectResponse(static::URL_PRODUCTS);
        }

        $productAbstractTransfer->setApprovalStatus($approvalStatus);

        $idProductAbstract = $this->getFactory()
            ->getProductFacade()
            ->saveProductAbstract($productAbstractTransfer);

        if (!$idProductAbstract) {
            $this->addErrorMessage(static::MESSAGE_PRODUCT_ABSTRACT_APPROVAL_STATUS_WAS_NOT_UPDATED);

            return $this->redirectResponse(static::URL_PRODUCTS);
        }

        $this->addSuccessMessage('The approval status was updated.');

        return $this->redirectResponse(static::URL_PRODUCTS);
    }
}
