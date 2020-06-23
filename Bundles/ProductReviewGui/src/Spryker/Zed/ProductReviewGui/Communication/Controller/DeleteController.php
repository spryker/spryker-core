<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductReviewGui\Communication\Controller;

use Generated\Shared\Transfer\ProductReviewTransfer;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ProductReviewGui\Communication\ProductReviewGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductReviewGui\Persistence\ProductReviewGuiQueryContainerInterface getQueryContainer()
 */
class DeleteController extends AbstractController
{
    public const PARAM_ID = 'id';

    protected const MESSAGE_PRODUCT_REVIEW_DELETED_SUCCESSFULLY = 'Product Review #%id% deleted successfully.';
    protected const MESSAGE_PARAM_ID = '%id%';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $idProductReview = $this->castId($request->query->get(static::PARAM_ID));

        $productSetTransfer = new ProductReviewTransfer();
        $productSetTransfer->setIdProductReview($idProductReview);

        $this->getFactory()
            ->getProductReviewFacade()
            ->deleteProductReview($productSetTransfer);

        $this->addSuccessMessage(static::MESSAGE_PRODUCT_REVIEW_DELETED_SUCCESSFULLY, [
            static::MESSAGE_PARAM_ID => $productSetTransfer->getIdProductReview(),
        ]);

        return $this->redirectResponse(
            Url::generate('/product-review-gui')->build()
        );
    }
}
