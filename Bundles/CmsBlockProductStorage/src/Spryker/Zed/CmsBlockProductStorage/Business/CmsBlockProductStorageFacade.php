<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockProductStorage\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\CmsBlockProductStorage\Business\CmsBlockProductStorageBusinessFactory getFactory()
 */
class CmsBlockProductStorageFacade extends AbstractFacade implements CmsBlockProductStorageFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array $productAbstractIds
     *
     * @return void
     */
    public function publish(array $productAbstractIds)
    {
        $this->getFactory()->createCmsBlockProductStorageWriter()->publish($productAbstractIds);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array $productAbstractIds
     *
     * @return void
     */
    public function refreshOrUnpublish(array $productAbstractIds)
    {
        $this->getFactory()->createCmsBlockProductStorageWriter()->refreshOrUnpublish($productAbstractIds);
    }
}
