<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockStorage\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\CmsBlockStorage\Business\CmsBlockStorageBusinessFactory getFactory()
 */
class CmsBlockStorageFacade extends AbstractFacade implements CmsBlockStorageFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array $cmsBlockIds
     *
     * @return void
     */
    public function publish(array $cmsBlockIds): void
    {
        $this->getFactory()->createCmsBlockStorageWriter()->publish($cmsBlockIds);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array $cmsBlockIds
     *
     * @return void
     */
    public function unpublish(array $cmsBlockIds): void
    {
        $this->getFactory()->createCmsBlockStorageWriter()->unpublish($cmsBlockIds);
    }
}
