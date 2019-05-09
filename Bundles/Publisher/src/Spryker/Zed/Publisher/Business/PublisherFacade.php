<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Publisher\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\Publisher\Business\PublisherBusinessFactory getFactory()
 */
class PublisherFacade extends AbstractFacade implements PublisherFacadeInterface
{
    /**
     * @api
     *
     * @return string[]
     */
    public function getPublisherPlugins(): array
    {
        return $this->getFactory()
            ->createPublisherPluginMerger()
            ->getPublisherPlugins();
    }
}
