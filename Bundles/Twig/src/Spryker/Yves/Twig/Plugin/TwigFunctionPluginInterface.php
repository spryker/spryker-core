<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Twig\Plugin;

use Silex\Application;

interface TwigFunctionPluginInterface
{
    /**
     * @param \Silex\Application $application
     *
     * @return array<\Twig\TwigFunction>
     */
    public function getFunctions(Application $application);
}
