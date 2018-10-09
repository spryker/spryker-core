<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelGui\Communication\Controller;

use Generated\Shared\Transfer\ProductLabelTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ProductLabelGui\Communication\ProductLabelGuiCommunicationFactory getFactory()
 */
class SetStatusController extends AbstractController
{
    public const PARAM_ID_PRODUCT_LABEL = 'id-product-label';
    public const PARAM_REDIRECT_URL = 'redirect-url';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function activeAction(Request $request)
    {
        $idProductLabel = $this->castId($request->query->get(static::PARAM_ID_PRODUCT_LABEL));

        $productLabelTransfer = $this->findProductLabelById($idProductLabel);
        $productLabelTransfer->setIsActive(true);

        $this->updateProductLabel($productLabelTransfer);

        $this->addSuccessMessage(sprintf(
            'Product label #%d successfully activated.',
            $productLabelTransfer->getIdProductLabel()
        ));

        return $this->redirectResponse($this->getRedirectUrl($request));
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return string
     */
    protected function getRedirectUrl(Request $request)
    {
        if ($request->query->get(static::PARAM_REDIRECT_URL)) {
            return $request->query->get(static::PARAM_REDIRECT_URL);
        }

        return '/product-label-gui';
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function inactiveAction(Request $request)
    {
        $idProductLabel = $this->castId($request->query->get(static::PARAM_ID_PRODUCT_LABEL));

        $productLabelTransfer = $this->findProductLabelById($idProductLabel);
        $productLabelTransfer->setIsActive(false);

        $this->updateProductLabel($productLabelTransfer);

        $this->addSuccessMessage(sprintf(
            'Product label #%d successfully deactivated.',
            $productLabelTransfer->getIdProductLabel()
        ));

        return $this->redirectResponse($this->getRedirectUrl($request));
    }

    /**
     * @param int $idProductLabel
     *
     * @return \Generated\Shared\Transfer\ProductLabelTransfer|null
     */
    protected function findProductLabelById($idProductLabel)
    {
        return $this
            ->getFactory()
            ->getProductLabelFacade()
            ->findLabelById($idProductLabel);
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
