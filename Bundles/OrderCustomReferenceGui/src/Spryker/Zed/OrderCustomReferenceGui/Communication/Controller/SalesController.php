<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OrderCustomReferenceGui\Communication\Controller;

use Generated\Shared\Transfer\SaveOrderTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Spryker\Zed\OrderCustomReferenceGui\Communication\Form\OrderCustomReferenceForm;
use Symfony\Component\Form\FormInterface;
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

    protected const ORDER_CUSTOM_REFERENCE_MAX_LENGTH = 255;

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function saveAction(Request $request): RedirectResponse
    {
        $orderCustomReferenceFacade = $this->getFactory()->getOrderCustomReferenceFacade();

        $orderCustomReferenceForm = $this->getFactory()->getOrderCustomReferenceForm()->handleRequest($request);

        if ($orderCustomReferenceForm->isSubmitted() && $orderCustomReferenceForm->isValid()) {
            $this->addSuccessMessage(static::MESSAGE_ORDER_CUSTOM_REFERENCE_SUCCESSFULLY_CHANGED);
            $orderCustomReferenceFormData = $orderCustomReferenceForm->getData();
            $saveOrderTransfer = (new SaveOrderTransfer())
                ->setIdSalesOrder($orderCustomReferenceFormData[OrderCustomReferenceForm::FIELD_ID_SALES_ORDER])
                ->setOrderCustomReference($orderCustomReferenceFormData[OrderCustomReferenceForm::FIELD_ORDER_CUSTOM_REFERENCE]);

            $orderCustomReferenceFacade->updateOrderCustomReference($saveOrderTransfer);
        }

        $this->addErrors($orderCustomReferenceForm);

        return $this->redirectResponse($orderCustomReferenceForm->getData()[OrderCustomReferenceForm::FIELD_BACK_URL]);
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $orderCustomReferenceForm
     *
     * @return void
     */
    protected function addErrors(FormInterface $orderCustomReferenceForm): void
    {
        foreach ($orderCustomReferenceForm->getErrors(true) as $error) {
            $this->addErrorMessage($error->getMessage());
        }
    }
}
