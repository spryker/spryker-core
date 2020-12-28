<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductReviewGui\Communication\Controller;

use Generated\Shared\Transfer\ProductReviewTransfer;
use Orm\Zed\ProductReview\Persistence\Map\SpyProductReviewTableMap;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ProductReviewGui\Communication\ProductReviewGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductReviewGui\Persistence\ProductReviewGuiQueryContainerInterface getQueryContainer()
 */
class UpdateController extends AbstractController
{
    public const PARAM_ID = 'id';
    protected const ROUTE_TEMPLATES_LIST = '/product-review-gui';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function approveAction(Request $request): RedirectResponse
    {
        if (($response = $this->checkForm($request)) !== null) {
            return $response;
        }

        $idProductReview = $this->castId($request->query->get(static::PARAM_ID));

        $productReviewTransfer = new ProductReviewTransfer();
        $productReviewTransfer
            ->setIdProductReview($idProductReview)
            ->setStatus(SpyProductReviewTableMap::COL_STATUS_APPROVED);

        $this->getFactory()
            ->getProductReviewFacade()
            ->updateProductReviewStatus($productReviewTransfer);

        $this->addSuccessMessage('Product Review #%d has been approved.', ['%d' => $idProductReview]);

        return $this->redirectResponse(Url::generate(static::ROUTE_TEMPLATES_LIST)->build());
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function rejectAction(Request $request): RedirectResponse
    {
        if (($response = $this->checkForm($request)) !== null) {
            return $response;
        }

        $idProductReview = $this->castId($request->query->get(static::PARAM_ID));

        $productReviewTransfer = new ProductReviewTransfer();
        $productReviewTransfer
            ->setIdProductReview($idProductReview)
            ->setStatus(SpyProductReviewTableMap::COL_STATUS_REJECTED);

        $this->getFactory()
            ->getProductReviewFacade()
            ->updateProductReviewStatus($productReviewTransfer);

        $this->addSuccessMessage('Product Review #%d has been rejected.', ['%d' => $idProductReview]);

        return $this->redirectResponse(Url::generate(static::ROUTE_TEMPLATES_LIST)->build());
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|null
     */
    protected function checkForm(Request $request): ?RedirectResponse
    {
        $form = $this->getFactory()->createStatusProductReviewForm()->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            $this->addErrorMessage('CSRF token is not valid.');

            return $this->redirectResponse(static::ROUTE_TEMPLATES_LIST);
        }

        return null;
    }
}
