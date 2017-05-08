<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelGui\Communication\Controller;

use Generated\Shared\Transfer\ProductLabelTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Spryker\Zed\ProductLabelGui\Communication\ProductLabelGuiCommunicationFactory;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method ProductLabelGuiCommunicationFactory getFactory()
 */
class EditController extends AbstractController
{

    const PARAM_ID_PRODUCT_LABEL = 'id-product-label';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $idProductLabel = $this->castId($request->query->get(static::PARAM_ID_PRODUCT_LABEL));
        $productLabelTransfer = $this->getProductLabelById($idProductLabel);

        $productLabelForm = $this->createProductLabelForm($productLabelTransfer);
        $productLabelForm->handleRequest($request);

        if ($this->isFormSubmittedSuccessfully($productLabelForm)) {
            return $this->redirectResponse('/product-label-gui');
        }

        return $this->viewResponse([
            'productLabelTransfer' => $productLabelTransfer,
            'productLabelForm' => $productLabelForm->createView(),
        ]);
    }

    /**
     * @param int $idProductLabel
     *
     * @return \Generated\Shared\Transfer\ProductLabelTransfer
     */
    protected function getProductLabelById($idProductLabel)
    {
        return $this
            ->getFactory()
            ->getProductLabelFacade()
            ->readLabel($idProductLabel);
    }

    /**
     * @param ProductLabelTransfer $productLabelTransfer
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    protected function createProductLabelForm(ProductLabelTransfer $productLabelTransfer)
    {
        $productLabelFormDataProvider = $this
            ->getFactory()
            ->createProductLabelFormDataProvider();

        $productLabelForm = $this
            ->getFactory()
            ->createProductLabelForm(
                $productLabelFormDataProvider->getData($productLabelTransfer->getIdProductLabel()),
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
            ->updateLabel($productLabelTransfer);

        $this->addSuccessMessage(sprintf(
            'Product label #%d successfully updated.',
            $productLabelTransfer->getIdProductLabel()
        ));

        return true;
    }

}
