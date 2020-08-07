<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductReviewGui\Communication;

use Generated\Shared\Transfer\LocaleTransfer;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\ProductReviewGui\Communication\Form\DeleteProductReviewForm;
use Spryker\Zed\ProductReviewGui\Communication\Table\ProductReviewTable;
use Spryker\Zed\ProductReviewGui\ProductReviewGuiDependencyProvider;
use Symfony\Component\Form\FormInterface;

/**
 * @method \Spryker\Zed\ProductReviewGui\Persistence\ProductReviewGuiQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductReviewGui\ProductReviewGuiConfig getConfig()
 */
class ProductReviewGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Spryker\Zed\ProductReviewGui\Communication\Table\ProductReviewTable
     */
    public function createProductReviewTable(LocaleTransfer $localeTransfer)
    {
        return new ProductReviewTable($this->getQueryContainer(), $localeTransfer, $this->getUtilDateTimeService(), $this->getUtilSanitizeServiceInterface());
    }

    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createDeleteProductReviewForm(): FormInterface
    {
        return $this->getFormFactory()->create(DeleteProductReviewForm::class, [], [
            'fields' => [],
        ]);
    }

    /**
     * @return \Spryker\Zed\ProductReviewGui\Dependency\Service\ProductReviewGuiToUtilDateTimeInterface
     */
    protected function getUtilDateTimeService()
    {
        return $this->getProvidedDependency(ProductReviewGuiDependencyProvider::SERVICE_UTIL_DATE_TIME);
    }

    /**
     * @return \Spryker\Zed\ProductReviewGui\Dependency\Service\ProductReviewGuiToUtilSanitizeInterface
     */
    protected function getUtilSanitizeServiceInterface()
    {
        return $this->getProvidedDependency(ProductReviewGuiDependencyProvider::SERVICE_UTIL_SANITIZE);
    }

    /**
     * @return \Spryker\Zed\ProductReviewGui\Dependency\Facade\ProductReviewGuiToProductReviewInterface
     */
    public function getProductReviewFacade()
    {
        return $this->getProvidedDependency(ProductReviewGuiDependencyProvider::FACADE_PRODUCT_REVIEW);
    }

    /**
     * @return \Spryker\Zed\ProductReviewGui\Dependency\Facade\ProductReviewGuiToLocaleInterface
     */
    public function getLocaleFacade()
    {
        return $this->getProvidedDependency(ProductReviewGuiDependencyProvider::FACADE_LOCALE);
    }
}
