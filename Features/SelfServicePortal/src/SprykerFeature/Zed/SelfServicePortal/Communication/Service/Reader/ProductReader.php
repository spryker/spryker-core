<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\Service\Reader;

use ArrayObject;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\Locale\Business\LocaleFacadeInterface;
use Spryker\Zed\Product\Business\ProductFacadeInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProductReader implements ProductReaderInterface
{
    /**
     * @var \Spryker\Zed\Product\Business\ProductFacadeInterface
     */
    protected ProductFacadeInterface $productFacade;

    /**
     * @var \Spryker\Zed\Locale\Business\LocaleFacadeInterface
     */
    protected LocaleFacadeInterface $localeFacade;

    /**
     * @param \Spryker\Zed\Product\Business\ProductFacadeInterface $productFacade
     * @param \Spryker\Zed\Locale\Business\LocaleFacadeInterface $localeFacade
     */
    public function __construct(
        ProductFacadeInterface $productFacade,
        LocaleFacadeInterface $localeFacade
    ) {
        $this->productFacade = $productFacade;
        $this->localeFacade = $localeFacade;
    }

    /**
     * @param int $idProductConcrete
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function getProductConcrete(int $idProductConcrete): ProductConcreteTransfer
    {
        $productConcreteTransfer = $this->productFacade->findProductConcreteById($idProductConcrete);

        if (!$productConcreteTransfer) {
            throw new NotFoundHttpException(sprintf('Product with id %d is not found.', $idProductConcrete));
        }

        /**
         * @var \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
         */
        $productAbstractTransfer = $this->productFacade->findProductAbstractById($productConcreteTransfer->getFkProductAbstractOrFail());

        $productConcreteTransfer->setApprovalStatus($productAbstractTransfer->getApprovalStatusOrFail());

        return $this->filterLocalizedAttributesByCurrentLocale($productConcreteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    protected function filterLocalizedAttributesByCurrentLocale(ProductConcreteTransfer $productConcreteTransfer): ProductConcreteTransfer
    {
        $currentLocaleTransfer = $this->localeFacade->getCurrentLocale();
        $filteredLocalizedAttributes = array_filter(
            $productConcreteTransfer->getLocalizedAttributes()->getArrayCopy(),
            function ($localizedAttribute) use ($currentLocaleTransfer) {
                return $localizedAttribute->getLocale()->getIdLocale() === $currentLocaleTransfer->getIdLocale();
            },
        );

        return $productConcreteTransfer->setLocalizedAttributes(new ArrayObject(array_values($filteredLocalizedAttributes)));
    }
}
