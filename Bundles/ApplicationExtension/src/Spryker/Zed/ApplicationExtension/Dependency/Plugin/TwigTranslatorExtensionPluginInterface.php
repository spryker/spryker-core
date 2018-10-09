<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ApplicationExtension\Dependency\Plugin;

use Twig_Environment;

interface TwigTranslatorExtensionPluginInterface
{
    /**
     * Specification:
     *  - Add translation extension to twig
     *
     * @api
     *
     * @param \Twig_Environment $twig
     *
     * @return \Twig_Environment
     */
    public function addExtension(Twig_Environment $twig): Twig_Environment;
}
