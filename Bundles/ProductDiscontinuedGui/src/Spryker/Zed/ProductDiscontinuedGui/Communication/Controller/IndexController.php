<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDiscontinuedGui\Communication\Controller;

use Generated\Shared\Transfer\ProductDiscontinuedRequestTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ProductDiscontinuedGui\Communication\ProductDiscontinuedGuiCommunicationFactory getFactory()
 */
class IndexController extends AbstractController
{
    public const PARAM_ID_PRODUCT_CONCRETE = 'id-product-concrete';

    protected const MESSAGE_PRODUCT_DISCONTINUED_SUCCESS = 'Product has been discontinued.';
    protected const MESSAGE_PRODUCT_DISCONTINUED_ERROR = 'Product can not be discontinued.';
    protected const MESSAGE_PRODUCT_UNDISCONTINUED_SUCCESS = 'Product has been undiscontinued.';
    protected const MESSAGE_PRODUCT_UNDISCONTINUED_ERROR = 'Product can not be undiscontinued.';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function discontinueAction(Request $request)
    {
        $idProductConcrete = $this->castId($request->get(static::PARAM_ID_PRODUCT_CONCRETE));

        $productDiscontinuedRequestTransfer = (new ProductDiscontinuedRequestTransfer())
            ->setIdProduct($idProductConcrete);

        $productDiscontinuedResponseTransfer = $this->getFactory()
            ->getProductDiscontinuedFacade()
            ->discontinueProduct($productDiscontinuedRequestTransfer);

        if ($productDiscontinuedResponseTransfer->getIsSuccessful()) {
            $this->addSuccessMessage(static::MESSAGE_PRODUCT_DISCONTINUED_SUCCESS);

            return $this->redirectResponse($request->headers->get('referer'));
        }
        $this->addErrorMessage(static::MESSAGE_PRODUCT_DISCONTINUED_ERROR);

        return $this->redirectResponse($request->headers->get('referer'));
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function undiscontinueAction(Request $request)
    {
        $idProductConcrete = $this->castId($request->get(static::PARAM_ID_PRODUCT_CONCRETE));

        $productDiscontinuedRequestTransfer = (new ProductDiscontinuedRequestTransfer())
            ->setIdProduct($idProductConcrete);

        $productDiscontinuedResponseTransfer = $this->getFactory()
            ->getProductDiscontinuedFacade()
            ->undiscontinueProduct($productDiscontinuedRequestTransfer);

        if ($productDiscontinuedResponseTransfer->getIsSuccessful()) {
            $this->addSuccessMessage(static::MESSAGE_PRODUCT_UNDISCONTINUED_SUCCESS);

            return $this->redirectResponse($request->headers->get('referer'));
        }
        $this->addErrorMessage(static::MESSAGE_PRODUCT_UNDISCONTINUED_ERROR);

        return $this->redirectResponse($request->headers->get('referer'));
    }
}
