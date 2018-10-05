<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductReview\Communication\Controller\Mapper;

use Generated\Shared\Transfer\ProductReviewRequestTransfer;
use Generated\Shared\Transfer\ProductReviewTransfer;
use Spryker\Zed\ProductReview\Dependency\Facade\ProductReviewToLocaleInterface;

class CustomerReviewSubmitMapper implements CustomerReviewSubmitMapperInterface
{
    /**
     * @var \Spryker\Zed\ProductReview\Dependency\Facade\ProductReviewToLocaleInterface
     */
    protected $localeFacade;

    /**
     * @param \Spryker\Zed\ProductReview\Dependency\Facade\ProductReviewToLocaleInterface $localeFacade
     */
    public function __construct(ProductReviewToLocaleInterface $localeFacade)
    {
        $this->localeFacade = $localeFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductReviewRequestTransfer $productReviewRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ProductReviewTransfer
     */
    public function mapRequestTransfer(ProductReviewRequestTransfer $productReviewRequestTransfer)
    {
        $this->assertProductReviewRequestTransfer($productReviewRequestTransfer);

        $productReviewTransfer = new ProductReviewTransfer();

        $productReviewTransfer
            ->fromArray($productReviewRequestTransfer->modifiedToArray(), true)
            ->setFkProductAbstract($productReviewRequestTransfer->getIdProductAbstract())
            ->setFkLocale($this->getIdLocale($productReviewRequestTransfer));

        return $productReviewTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductReviewRequestTransfer $productReviewRequestTransfer
     *
     * @return int
     */
    protected function getIdLocale(ProductReviewRequestTransfer $productReviewRequestTransfer)
    {
        return $this->localeFacade->getLocale($productReviewRequestTransfer->getLocaleName())->getIdLocale();
    }

    /**
     * @param \Generated\Shared\Transfer\ProductReviewRequestTransfer $productReviewRequestTransfer
     *
     * @return void
     */
    protected function assertProductReviewRequestTransfer(ProductReviewRequestTransfer $productReviewRequestTransfer)
    {
        $productReviewRequestTransfer
            ->requireIdProductAbstract()
            ->requireLocaleName();
    }
}
