<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Dependency\Facade;

use Generated\Shared\Transfer\LocaleTransfer;

class ProductManagementToCategoryBridge implements ProductManagementToCategoryInterface
{
    /**
     * @var \Spryker\Zed\Category\Business\CategoryFacadeInterface
     */
    protected $categoryFacade;

    /**
     * @param \Spryker\Zed\Category\Business\CategoryFacadeInterface $categoryFacade
     */
    public function __construct($categoryFacade)
    {
        $this->categoryFacade = $categoryFacade;
    }

    /**
     * @param int $idProduct
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryTransfer[]
     */
    public function getCategoriesByAbstractProductId(int $idProduct, LocaleTransfer $localeTransfer): array
    {
        return $this->categoryFacade->getCategoriesByAbstractProductId($idProduct, $localeTransfer);
    }
}
