<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OrderCustomReferenceGui\Communication\Controller;

use Generated\Shared\Transfer\OrderCustomReferenceResponseTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\OrderCustomReferenceGui\Communication\OrderCustomReferenceGuiCommunicationFactory getFactory()
 */
class SalesController extends AbstractController
{
    /**
     * @var string
     */
    protected const MESSAGE_ORDER_CUSTOM_REFERENCE_SUCCESSFULLY_CHANGED = 'Custom order reference was successfully changed.';

    /**
     * @var string
     */
    protected const PARAM_ORDER_CUSTOM_REFERENCE = 'orderCustomReference';

    /**
     * @var string
     */
    protected const PARAM_ID_SALES_ORDER = 'idSalesOrder';

    /**
     * @var string
     */
    protected const PARAM_BACK_URL = 'backUrl';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function saveAction(Request $request): RedirectResponse
    {
        $orderCustomReference = (string)$request->request->get(static::PARAM_ORDER_CUSTOM_REFERENCE);
        $backUrl = (string)$request->request->get(static::PARAM_BACK_URL);

        $orderTransfer = (new OrderTransfer())
            ->setIdSalesOrder($request->request->getInt(static::PARAM_ID_SALES_ORDER));

        $orderCustomReferenceResponseTransfer = $this->getFactory()
            ->getOrderCustomReferenceFacade()
            ->updateOrderCustomReference($orderCustomReference, $orderTransfer);

        if (!$orderCustomReferenceResponseTransfer->getIsSuccessful()) {
            $this->addErrorMessages($orderCustomReferenceResponseTransfer);

            return $this->redirectResponse($backUrl);
        }

        $this->addSuccessMessage(static::MESSAGE_ORDER_CUSTOM_REFERENCE_SUCCESSFULLY_CHANGED);

        return $this->redirectResponse($backUrl);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderCustomReferenceResponseTransfer $orderCustomReferenceResponseTransfer
     *
     * @return void
     */
    protected function addErrorMessages(OrderCustomReferenceResponseTransfer $orderCustomReferenceResponseTransfer): void
    {
        foreach ($orderCustomReferenceResponseTransfer->getMessages() as $messageTransfer) {
            $this->addErrorMessage($messageTransfer->getValue());
        }
    }
}
