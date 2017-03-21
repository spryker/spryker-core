<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Twig;

use Spryker\Shared\Kernel\KernelConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class TwigConfig extends AbstractBundleConfig
{

    /**
     * @return string
     */
    public function getBundlesDirectory()
    {
        return $this->get(KernelConstants::SPRYKER_ROOT);
    }

}
