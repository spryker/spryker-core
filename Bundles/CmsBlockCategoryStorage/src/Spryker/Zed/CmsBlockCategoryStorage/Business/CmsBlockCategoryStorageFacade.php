<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockCategoryStorage\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\CmsBlockCategoryStorage\Business\CmsBlockCategoryStorageBusinessFactory getFactory()
 */
class CmsBlockCategoryStorageFacade extends AbstractFacade implements CmsBlockCategoryStorageFacadeInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param array $categoryIds
     *
     * @return void
     */
    public function publish(array $categoryIds)
    {
        $this->getFactory()->createCmsBlockCategoryStorageWriter()->publish($categoryIds);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param array $categoryIds
     *
     * @return void
     */
    public function refreshOrUnpublish(array $categoryIds)
    {
        $this->getFactory()->createCmsBlockCategoryStorageWriter()->refreshOrUnpublish($categoryIds);
    }
}
