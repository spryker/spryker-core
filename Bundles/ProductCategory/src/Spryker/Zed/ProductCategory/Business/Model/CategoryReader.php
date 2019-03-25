<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategory\Business\Model;

use Generated\Shared\Transfer\CategoryCollectionTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Spryker\Zed\ProductCategory\Dependency\Facade\ProductCategoryToCategoryInterface;
use Spryker\Zed\ProductCategory\Persistence\ProductCategoryRepositoryInterface;

class CategoryReader implements CategoryReaderInterface
{
    /**
     * @var \Spryker\Zed\ProductCategory\Persistence\ProductCategoryRepositoryInterface
     */
    protected $categoryRepository;

    /**
     * @var \Spryker\Zed\ProductCategory\Dependency\Facade\ProductCategoryToCategoryInterface
     */
    protected $categoryFacade;

    /**
     * @param \Spryker\Zed\ProductCategory\Persistence\ProductCategoryRepositoryInterface $categoryRepository
     * @param \Spryker\Zed\ProductCategory\Dependency\Facade\ProductCategoryToCategoryInterface $categoryFacade
     */
    public function __construct(
        ProductCategoryRepositoryInterface $categoryRepository,
        ProductCategoryToCategoryInterface $categoryFacade
    ) {
        $this->categoryRepository = $categoryRepository;
        $this->categoryFacade = $categoryFacade;
    }

    /**
     * @param int $idProductAbstract
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryCollectionTransfer
     */
    public function getCategoryTransferCollectionByIdProductAbstract(int $idProductAbstract, LocaleTransfer $localeTransfer): CategoryCollectionTransfer
    {
        return $this->categoryRepository->getCategoryTransferCollectionByIdProductAbstract($idProductAbstract, $localeTransfer->getIdLocale());
    }
}
