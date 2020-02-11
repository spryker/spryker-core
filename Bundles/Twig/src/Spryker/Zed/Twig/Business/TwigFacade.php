<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Twig\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\Twig\Business\TwigBusinessFactory getFactory()
 */
class TwigFacade extends AbstractFacade implements TwigFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return void
     */
    public function warmUpCache()
    {
        $this->getFactory()->createCacheWarmer()->warmUp();
    }
}
