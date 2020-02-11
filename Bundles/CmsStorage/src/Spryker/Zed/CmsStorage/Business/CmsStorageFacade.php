<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsStorage\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\CmsStorage\Business\CmsStorageBusinessFactory getFactory()
 */
class CmsStorageFacade extends AbstractFacade implements CmsStorageFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array $cmsPageIds
     *
     * @return void
     */
    public function publish(array $cmsPageIds)
    {
        $this->getFactory()->createCmsStorageWriter()->publish($cmsPageIds);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array $cmsPageIds
     *
     * @return void
     */
    public function unpublish(array $cmsPageIds)
    {
        $this->getFactory()->createCmsStorageWriter()->unpublish($cmsPageIds);
    }
}
