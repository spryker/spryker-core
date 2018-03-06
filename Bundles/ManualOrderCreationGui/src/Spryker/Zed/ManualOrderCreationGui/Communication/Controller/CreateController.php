<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ManualOrderCreationGui\Communication\Controller;

use Generated\Shared\Transfer\ManualOrderEntryTransfer;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Spryker\Zed\Sales\SalesConfig;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ManualOrderCreationGui\Communication\ManualOrderCreationGuiCommunicationFactory getFactory()
 */
class CreateController extends AbstractController
{
    const ERROR_MESSAGE_INVALID_DATA_PROVIDED = 'Invalid data provided.';
    const MESSAGE_SUCCESSFUL_ORDER_CREATED = 'Order was created successfully.';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array
     */
    public function createAction(Request $request)
    {
        $checkoutFormDataProvider = $this->getFactory()
            ->createCheckoutFormDataProvider();

        $checkoutForm = $this->getFactory()
            ->getCheckoutForm($checkoutFormDataProvider)
            ->handleRequest($request);

        if ($checkoutForm->isSubmitted()) {
            if ($checkoutForm->isValid()) {
                // @todo @Artem Manage subform activation

                $manualOrderEntryTransfer = $this->createOrder($checkoutForm);

                // @todo @Artem redirect to order details
                if (0 && !empty($manualOrderEntryTransfer)) {
                    $redirectUrl = $this->createSuccessRedirectUrl($manualOrderEntryTransfer);
                    return $this->redirectResponse($redirectUrl);
                }
            } else {
                $this->addErrorMessage(static::ERROR_MESSAGE_INVALID_DATA_PROVIDED);
            }
        }

        return $this->viewResponse([
            'checkoutForm' => $checkoutForm->createView(),
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $checkoutForm
     *
     * @return \Generated\Shared\Transfer\ManualOrderEntryTransfer|null
     */
    protected function createOrder(FormInterface $checkoutForm)
    {
        $manualOrderEntryTransfer = $checkoutForm->getData();

        try {
            // @todo @Artem create Order here
            $manualOrderEntryTransfer->setIdOrder(1);

            $this->addSuccessMessage(static::MESSAGE_SUCCESSFUL_ORDER_CREATED);
        } catch (\Exception $exception) {
            $this->addErrorMessage(static::ERROR_MESSAGE_INVALID_DATA_PROVIDED);
        }

        return $manualOrderEntryTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ManualOrderEntryTransfer $manualOrderEntryTransfer
     *
     * @return string
     */
    protected function createSuccessRedirectUrl(ManualOrderEntryTransfer $manualOrderEntryTransfer)
    {
        return Url::generate(
            '/sales/detail',
            [SalesConfig::PARAM_ID_SALES_ORDER => $manualOrderEntryTransfer->getIdOrder()]
        )
            ->build();
    }

}
