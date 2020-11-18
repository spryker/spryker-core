<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReclamationGui\Communication\Controller;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ReclamationCreateRequestTransfer;
use Generated\Shared\Transfer\ReclamationTransfer;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Csrf\CsrfToken;

/**
 * @method \Spryker\Zed\SalesReclamationGui\Communication\SalesReclamationGuiCommunicationFactory getFactory()
 */
class CreateController extends AbstractController
{
    public const PARAM_ID_SALES_ORDER = 'id-sales-order';

    protected const PARAM_IDS_SALES_ORDER_ITEMS = 'id-order-item';
    protected const PARAM_ID_RECLAMATION = 'id-reclamation';
    protected const PARAM_TOKEN = '_token';
    protected const PARAM_CLAIM_FORM_TOKEN_ID = 'reclamation_form_token';

    protected const REQUEST_HEADER_REFERER = 'referer';

    protected const VALIDATION_FALLBACK_ERROR_REDIRECT_URL = '/sales-reclamation-gui';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        if ($request->getMethod() === Request::METHOD_POST && !$this->isCsrfTokenValid($request->get(static::PARAM_TOKEN))) {
            $this->addErrorMessage('CSRF token is not valid.');

            return $this->getCsrfValidationErrorRedirectUrl($request);
        }

        $idSalesOrder = $this->castId($request->get(static::PARAM_ID_SALES_ORDER));

        $orderTransfer = $this
            ->getFactory()
            ->getSalesFacade()
            ->getOrderByIdSalesOrder($idSalesOrder);

        $reclamation = $this->getFactory()
            ->getSalesReclamationFacade()
            ->mapOrderTransferToReclamationTransfer($orderTransfer, new ReclamationTransfer());

        $orderItemIds = $request->request->getDigits(static::PARAM_IDS_SALES_ORDER_ITEMS);

        if (!$orderItemIds) {
            return $this->viewResponse([
                'reclamation' => $reclamation,
            ]);
        }

        $reclamationTransfer = $this->createReclamation($orderTransfer, (array)$orderItemIds);

        if ($reclamationTransfer) {
            $this->addSuccessMessage('Reclamation id:%s for order %s successfully created', [
                '%s' => $reclamationTransfer->getIdSalesReclamation(),
                '%d' => $orderTransfer->getOrderReference(),
            ]);

            return $this->redirectResponse(
                Url::generate(
                    '/sales-reclamation-gui/detail',
                    [
                        static::PARAM_ID_RECLAMATION => $reclamationTransfer->getIdSalesReclamation(),
                    ]
                )->build()
            );
        }

        return $this->viewResponse([
            'reclamation' => $reclamation,
        ]);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param array $orderItemIds
     *
     * @return \Generated\Shared\Transfer\ReclamationTransfer|null
     */
    protected function createReclamation(OrderTransfer $orderTransfer, array $orderItemIds): ?ReclamationTransfer
    {
        $reclamationCreateRequestTransfer = new ReclamationCreateRequestTransfer();
        $reclamationCreateRequestTransfer->setOrder($orderTransfer);

        foreach ($orderItemIds as $idOrderItem) {
            $orderItemsTransfer = $this->findOrderItemById($orderTransfer, $idOrderItem);

            if (!$orderItemsTransfer) {
                $this->addErrorMessage('Order item with id %s not belong to order %d', [
                    '%s' => $idOrderItem,
                    '%d' => $orderTransfer->getIdSalesOrder(),
                ]);

                return null;
            }

            $reclamationCreateRequestTransfer->addItem($orderItemsTransfer);
        }

        $reclamationTransfer = $this->getFactory()
            ->getSalesReclamationFacade()
            ->createReclamation($reclamationCreateRequestTransfer);

        if (!$reclamationTransfer->getIdSalesReclamation()) {
            $this->addErrorMessage('Can not create reclamation');
        }

        return $reclamationTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param int $idOrderItem
     *
     * @return \Generated\Shared\Transfer\ItemTransfer|null
     */
    protected function findOrderItemById(OrderTransfer $orderTransfer, int $idOrderItem): ?ItemTransfer
    {
        foreach ($orderTransfer->getItems() as $orderItemTransfer) {
            if ($orderItemTransfer->getIdSalesOrderItem() === $idOrderItem) {
                return $orderItemTransfer;
            }
        }

        return null;
    }

    /**
     * @param string|null $token
     *
     * @return bool
     */
    protected function isCsrfTokenValid(?string $token): bool
    {
        if (!$token) {
            return false;
        }

        $csrfToken = new CsrfToken(static::PARAM_CLAIM_FORM_TOKEN_ID, $token);

        return $this->getFactory()->getCsrfTokenManager()->isTokenValid($csrfToken);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function getCsrfValidationErrorRedirectUrl(Request $request): RedirectResponse
    {
        if ($request->headers->has(static::REQUEST_HEADER_REFERER)) {
            $refererUrl = $request->headers->get(static::REQUEST_HEADER_REFERER);

            return $this->redirectResponseExternal($refererUrl);
        }

        return $this->redirectResponse(static::VALIDATION_FALLBACK_ERROR_REDIRECT_URL);
    }
}
