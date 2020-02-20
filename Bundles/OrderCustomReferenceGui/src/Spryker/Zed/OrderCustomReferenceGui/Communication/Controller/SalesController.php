<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OrderCustomReferenceGui\Communication\Controller;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\OrderCustomReferenceGui\Communication\OrderCustomReferenceGuiCommunicationFactory getFactory()
 */
class SalesController extends AbstractController
{
    protected const FORM_NAME = 'order_custom_reference_form';

    protected const PARAM_ORDER_CUSTOM_REFERENCE = 'order-custom-reference';
    protected const PARAM_ID_SALES_ORDER = 'id-sales-order';
    protected const PARAM_BACK_URL = 'back-url';

    protected const MESSAGE_ORDER_CUSTOM_REFERENCE_SUCCESSFULLY_CHANGED = 'Order Custom Reference was successfully changed.';
    protected const MESSAGE_ORDER_CUSTOM_REFERENCE_WAS_NOT_CHANGED = 'Order Custom Reference was not changed.';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function saveAction(Request $request): RedirectResponse
    {
        $orderCustomReferenceFacade = $this->getFactory()->getOrderCustomReferenceFacade();
        $formData = $request->get(static::FORM_NAME);

        $formDataIdSalesOrder = $formData[static::PARAM_ID_SALES_ORDER] ?? '';
        $formDataBackUrl = $formData[static::PARAM_BACK_URL] ?? '';

        if (!$formDataIdSalesOrder) {
            $this->addErrorMessage(static::MESSAGE_ORDER_CUSTOM_REFERENCE_WAS_NOT_CHANGED);

            return $this->redirectResponse($formDataBackUrl);
        }

        $quoteTransfer = (new QuoteTransfer())
            ->setOrderCustomReference($formData[static::PARAM_ORDER_CUSTOM_REFERENCE] ?? '');
        $saveOrderTransfer = (new SaveOrderTransfer())->setIdSalesOrder($formDataIdSalesOrder);

        $orderCustomReferenceFacade->saveOrderCustomReference($quoteTransfer, $saveOrderTransfer);

        $this->addSuccessMessage(static::MESSAGE_ORDER_CUSTOM_REFERENCE_SUCCESSFULLY_CHANGED);

        return $this->redirectResponse($formDataBackUrl);
    }
}
