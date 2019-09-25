<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryImageStorage\Business;

/**
 * @method \Spryker\Zed\CategoryImageStorage\Business\CategoryImageStorageBusinessFactory getFactory()
 */
interface CategoryImageStorageFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array $categoryIds
     *
     * @return void
     */
    public function publishCategoryImages(array $categoryIds);

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array $categoryIds
     *
     * @return void
     */
    public function unpublishCategoryImages(array $categoryIds);
}
