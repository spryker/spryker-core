<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OrderCustomReferenceGui\Communication\Controller;

use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\OrderCustomReferenceGui\Communication\OrderCustomReferenceGuiCommunicationFactory getFactory()
 */
class SalesController extends AbstractController
{
    protected const MESSAGE_ORDER_CUSTOM_REFERENCE_SUCCESSFULLY_CHANGED = 'Order Custom Reference was successfully changed.';

    protected const GLOSSARY_KEY_ORDER_CUSTOM_REFERENCE_MESSAGE_INVALID_LENGTH = 'order_custom_reference.validation.error.message_invalid_length';

    protected const PARAM_ORDER_CUSTOM_REFERENCE = 'orderCustomReference';
    protected const PARAM_ID_SALES_ORDER = 'idSalesOrder';
    protected const PARAM_BACK_URL = 'backUrl';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function saveAction(Request $request): RedirectResponse
    {
        $orderCustomReference = $request->request->get(static::PARAM_ORDER_CUSTOM_REFERENCE);
        $backUrl = $request->request->get(static::PARAM_BACK_URL);

        $orderTransfer = (new OrderTransfer())
            ->setIdSalesOrder($request->request->getInt(static::PARAM_ID_SALES_ORDER));

        $this->getFactory()->getOrderCustomReferenceFacade()
            ->updateOrderCustomReference($orderCustomReference, $orderTransfer);

        if ($orderTransfer->getOrderCustomReference() === $orderCustomReference) {
            $this->addSuccessMessage(static::MESSAGE_ORDER_CUSTOM_REFERENCE_SUCCESSFULLY_CHANGED);
        }

        if ($orderTransfer->getOrderCustomReference() !== $orderCustomReference) {
            $this->addErrorMessage(static::GLOSSARY_KEY_ORDER_CUSTOM_REFERENCE_MESSAGE_INVALID_LENGTH);
        }

        return $this->redirectResponse($backUrl);
    }
}
