<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelGui\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ProductLabelGui\Communication\ProductLabelGuiCommunicationFactory getFactory()
 */
class CreateController extends AbstractController
{

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $productLabelForm = $this->createProductLabelForm();
        $productLabelForm->handleRequest($request);

        if ($this->isFormSubmittedSuccessfully($productLabelForm)) {
            return $this->redirectResponse('/product-label-gui');
        }

        return $this->viewResponse([
            'productLabelForm' => $productLabelForm->createView(),
        ]);
    }

    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    protected function createProductLabelForm()
    {
        $productLabelFormDataProvider = $this
            ->getFactory()
            ->createProductLabelFormDataProvider();

        $productLabelForm = $this
            ->getFactory()
            ->createProductLabelForm(
                $productLabelFormDataProvider->getData(),
                $productLabelFormDataProvider->getOptions()
            );

        return $productLabelForm;
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $productLabelForm
     *
     * @return bool
     */
    protected function isFormSubmittedSuccessfully(FormInterface $productLabelForm)
    {
        if (!$productLabelForm->isValid()) {
            return false;
        }

        /** @var \Generated\Shared\Transfer\ProductLabelTransfer $productLabelTransfer */
        $productLabelTransfer = $productLabelForm->getData();
        $this
            ->getFactory()
            ->getProductLabelFacade()
            ->createLabel($productLabelTransfer);

        $this->addSuccessMessage(sprintf(
            'Product label #%d successfully created.',
            $productLabelTransfer->getIdProductLabel()
        ));

        return true;
    }

}
