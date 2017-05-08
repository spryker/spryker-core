<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelGui\Communication\Controller;

use Generated\Shared\Transfer\ProductLabelTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Spryker\Zed\ProductLabelGui\Communication\ProductLabelGuiCommunicationFactory;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method ProductLabelGuiCommunicationFactory getFactory()
 */
class SetStatusController extends AbstractController
{

    const PARAM_ID_PRODUCT_LABEL = 'id-product-label';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function activeAction(Request $request)
    {
        $idProductLabel = $this->castId($request->query->get(static::PARAM_ID_PRODUCT_LABEL));

        $productLabelTransfer = $this->getProductLabelById($idProductLabel);
        $productLabelTransfer->setIsActive(true);

        $this->updateProductLabel($productLabelTransfer);

        return $this->redirectResponse('/product-label-gui');
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function inactiveAction(Request $request)
    {
        $idProductLabel = $this->castId($request->query->get(static::PARAM_ID_PRODUCT_LABEL));

        $productLabelTransfer = $this->getProductLabelById($idProductLabel);
        $productLabelTransfer->setIsActive(false);

        $this->updateProductLabel($productLabelTransfer);

        return $this->redirectResponse('/product-label-gui');
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
     * @param \Generated\Shared\Transfer\ProductLabelTransfer $productLabelTransfer
     *
     * @return void
     */
    protected function updateProductLabel(ProductLabelTransfer $productLabelTransfer)
    {
        $this
            ->getFactory()
            ->getProductLabelFacade()
            ->updateLabel($productLabelTransfer);
    }

}
