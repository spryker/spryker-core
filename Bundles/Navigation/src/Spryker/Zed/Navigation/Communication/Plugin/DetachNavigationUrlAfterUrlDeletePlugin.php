<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Navigation\Communication\Plugin;

use Generated\Shared\Transfer\UrlTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Url\Dependency\Plugin\UrlDeletePluginInterface;

/**
 * @method \Spryker\Zed\Navigation\Business\NavigationFacadeInterface getFacade()
 * @method \Spryker\Zed\Navigation\Communication\NavigationCommunicationFactory getFactory()
 */
class DetachNavigationUrlAfterUrlDeletePlugin extends AbstractPlugin implements UrlDeletePluginInterface
{
    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\UrlTransfer $urlTransfer
     *
     * @return void
     */
    public function delete(UrlTransfer $urlTransfer)
    {
        $this->getFacade()->detachUrlFromNavigationNodes($urlTransfer);
    }
}
