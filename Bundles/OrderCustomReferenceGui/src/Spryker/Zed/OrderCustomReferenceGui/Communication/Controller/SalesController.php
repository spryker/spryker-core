<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OrderCustomReferenceGui\Communication\Controller;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Spryker\Zed\OrderCustomReferenceGui\Communication\Form\OrderCustomReferenceForm;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\OrderCustomReferenceGui\Communication\OrderCustomReferenceGuiCommunicationFactory getFactory()
 */
class SalesController extends AbstractController
{
    protected const FORM_NAME = 'order_custom_reference_form';

    protected const MESSAGE_ORDER_CUSTOM_REFERENCE_SUCCESSFULLY_CHANGED = 'Order Custom Reference was successfully changed.';
    protected const MESSAGE_ORDER_CUSTOM_REFERENCE_WAS_NOT_CHANGED = 'Order Custom Reference has not been changed.';

    protected const GLOSSARY_KEY_ORDER_CUSTOM_REFERENCE_MESSAGE_INVALID_LENGTH = 'order_custom_reference.validation.error.message_invalid_length';
    protected const ORDER_CUSTOM_REFERENCE_MAX_LENGTH = 255;

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function saveAction(Request $request): RedirectResponse
    {
        $orderCustomReferenceFacade = $this->getFactory()->getOrderCustomReferenceFacade();
        $formData = $request->get(static::FORM_NAME);

        $formDataIdSalesOrder = $formData[OrderCustomReferenceForm::FIELD_ID_SALES_ORDER] ?? '';
        $formDataBackUrl = $formData[OrderCustomReferenceForm::FIELD_BACK_URL] ?? '';

        if (!$formDataIdSalesOrder) {
            $this->addErrorMessage(static::MESSAGE_ORDER_CUSTOM_REFERENCE_WAS_NOT_CHANGED);

            return $this->redirectResponse($formDataBackUrl);
        }

        $quoteTransfer = (new QuoteTransfer())
            ->setOrderCustomReference($formData[OrderCustomReferenceForm::FIELD_ORDER_CUSTOM_REFERENCE] ?? '');
        $saveOrderTransfer = (new SaveOrderTransfer())->setIdSalesOrder($formDataIdSalesOrder);

        if (!$this->isOrderCustomReferenceLengthValid($quoteTransfer->getOrderCustomReference())) {
            $this->addErrorMessage(static::GLOSSARY_KEY_ORDER_CUSTOM_REFERENCE_MESSAGE_INVALID_LENGTH);

            return $this->redirectResponse($formDataBackUrl);
        }

        $orderCustomReferenceFacade->saveOrderCustomReference($quoteTransfer, $saveOrderTransfer);

        $this->addSuccessMessage(static::MESSAGE_ORDER_CUSTOM_REFERENCE_SUCCESSFULLY_CHANGED);

        return $this->redirectResponse($formDataBackUrl);
    }

    /**
     * @param string|null $orderCustomReference
     *
     * @return bool
     */
    protected function isOrderCustomReferenceLengthValid(?string $orderCustomReference): bool
    {
        if (!$orderCustomReference) {
            return true;
        }

        return mb_strlen($orderCustomReference) <= static::ORDER_CUSTOM_REFERENCE_MAX_LENGTH;
    }
}
