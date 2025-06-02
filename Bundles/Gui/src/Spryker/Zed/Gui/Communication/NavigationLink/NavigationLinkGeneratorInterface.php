<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Gui\Communication\NavigationLink;

use Twig\Environment;

interface NavigationLinkGeneratorInterface
{
    /**
     * @param \Twig\Environment $twig
     *
     * @return string
     */
    public function generateNavigationItems(Environment $twig): string;
}
