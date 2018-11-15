<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryImage\Communication\Plugin;

use Generated\Shared\Transfer\CategoryTransfer;
use Spryker\Zed\CategoryExtension\Dependency\Plugin\CategoryCreateAfterPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\CategoryImage\Business\CategoryImageFacadeInterface getFacade()
 */
class CategoryAfterCreatePlugin extends AbstractPlugin implements CategoryCreateAfterPluginInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryTransfer
     */
    public function execute(CategoryTransfer $categoryTransfer): CategoryTransfer
    {
        return $this->getFacade()->createCategoryImageSetCollection($categoryTransfer);
    }
}
