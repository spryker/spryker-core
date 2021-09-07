<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDiscontinuedGui\Communication\Controller;

use Generated\Shared\Transfer\ProductDiscontinuedResponseTransfer;
use Generated\Shared\Transfer\ProductDiscontinueRequestTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ProductDiscontinuedGui\Communication\ProductDiscontinuedGuiCommunicationFactory getFactory()
 */
class IndexController extends AbstractController
{
    /**
     * @var string
     */
    public const PARAM_ID_PRODUCT_CONCRETE = 'id-product-concrete';
    /**
     * @var string
     */
    protected const HEADER_REFERER = 'referer';
    /**
     * @var string
     */
    protected const TAB_KEY_DISCONTINUE = '#tab-content-discontinue';

    /**
     * @var string
     */
    protected const MESSAGE_PRODUCT_DISCONTINUED_SUCCESS = 'Product has been marked as discontinued.';
    /**
     * @var string
     */
    protected const MESSAGE_PRODUCT_DISCONTINUED_ERROR = 'Product can not be marked as  discontinued.';
    /**
     * @var string
     */
    protected const MESSAGE_PRODUCT_UNDISCONTINUED_SUCCESS = 'Product has been unmarked as discontinued.';
    /**
     * @var string
     */
    protected const MESSAGE_PRODUCT_UNDISCONTINUED_ERROR = 'Product can not be unmarked as discontinued.';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function discontinueAction(Request $request)
    {
        $idProductConcrete = $this->castId($request->get(static::PARAM_ID_PRODUCT_CONCRETE));

        $productDiscontinuedRequestTransfer = (new ProductDiscontinueRequestTransfer())
            ->setIdProduct($idProductConcrete);

        $productDiscontinuedResponseTransfer = $this->getFactory()
            ->getProductDiscontinuedFacade()
            ->markProductAsDiscontinued($productDiscontinuedRequestTransfer);

        if ($productDiscontinuedResponseTransfer->getIsSuccessful()) {
            $this->addSuccessMessage(static::MESSAGE_PRODUCT_DISCONTINUED_SUCCESS);

            return $this->redirectToReferer($request);
        }
        $this->addErrorMessage(static::MESSAGE_PRODUCT_DISCONTINUED_ERROR);

        return $this->redirectToReferer($request);
    }

    /**
     * @deprecated Use IndexController::restore() instead.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function undiscontinueAction(Request $request)
    {
        $idProductConcrete = $this->castId($request->get(static::PARAM_ID_PRODUCT_CONCRETE));

        $productDiscontinuedRequestTransfer = (new ProductDiscontinueRequestTransfer())
            ->setIdProduct($idProductConcrete);

        $productDiscontinuedResponseTransfer = $this->getFactory()
            ->getProductDiscontinuedFacade()
            ->unmarkProductAsDiscontinued($productDiscontinuedRequestTransfer);

        if ($productDiscontinuedResponseTransfer->getIsSuccessful()) {
            $this->addSuccessMessage(static::MESSAGE_PRODUCT_UNDISCONTINUED_SUCCESS);

            return $this->redirectToReferer($request);
        }

        $this->addErrorMessage(static::MESSAGE_PRODUCT_UNDISCONTINUED_ERROR);
        $this->addErrorMessagesFromResponseTransfer($productDiscontinuedResponseTransfer);

        return $this->redirectToReferer($request);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function restoreAction(Request $request)
    {
        $idProductConcrete = $this->castId($request->get(static::PARAM_ID_PRODUCT_CONCRETE));

        $productDiscontinueRequestTransfer = (new ProductDiscontinueRequestTransfer())
            ->setIdProduct($idProductConcrete);

        $productDiscontinuedResponseTransfer = $this->getFactory()
            ->getProductDiscontinuedFacade()
            ->unmarkProductAsDiscontinued($productDiscontinueRequestTransfer);

        if ($productDiscontinuedResponseTransfer->getIsSuccessful()) {
            $this->addSuccessMessage(static::MESSAGE_PRODUCT_UNDISCONTINUED_SUCCESS);

            return $this->redirectToReferer($request);
        }

        $this->addErrorMessage(static::MESSAGE_PRODUCT_UNDISCONTINUED_ERROR);
        $this->addErrorMessagesFromResponseTransfer($productDiscontinuedResponseTransfer);

        return $this->redirectToReferer($request);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function redirectToReferer(Request $request): RedirectResponse
    {
        return $this->redirectResponse($request->headers->get(static::HEADER_REFERER) . static::TAB_KEY_DISCONTINUE);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductDiscontinuedResponseTransfer $productDiscontinuedResponseTransfer
     *
     * @return void
     */
    protected function addErrorMessagesFromResponseTransfer(ProductDiscontinuedResponseTransfer $productDiscontinuedResponseTransfer)
    {
        foreach ($productDiscontinuedResponseTransfer->getMessages() as $messageTransfer) {
            $this->addErrorMessage($messageTransfer->getValue(), $messageTransfer->getParameters());
        }
    }
}
