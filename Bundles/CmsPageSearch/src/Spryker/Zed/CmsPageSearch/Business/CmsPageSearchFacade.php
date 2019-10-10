<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsPageSearch\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\CmsPageSearch\Business\CmsPageSearchBusinessFactory getFactory()
 */
class CmsPageSearchFacade extends AbstractFacade implements CmsPageSearchFacadeInterface
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
        $this->getFactory()->createCmsPageSearchWriter()->publish($cmsPageIds);
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
        $this->getFactory()->createCmsPageSearchWriter()->unpublish($cmsPageIds);
    }
}
