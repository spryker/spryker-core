<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Twig\Business;

/**
 * @method \Spryker\Zed\Twig\Business\TwigBusinessFactory getFactory()
 */
interface TwigFacadeInterface
{
    /**
     * Specification:
     * - Creates twig template path cache file for all applied applications
     *
     * @api
     *
     * @return void
     */
    public function warmUpCache();
}
