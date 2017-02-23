<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Glossary;

use Spryker\Shared\Kernel\KernelConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class GlossaryConfig extends AbstractBundleConfig
{

    /**
     * TODO Document this feature
     *
     * @return array
     */
    public function getGlossaryFilePaths()
    {
        return glob(
            $this->get(KernelConstants::SPRYKER_ROOT) . '/*/src/Spryker/*/*/Resources/glossary.yml'
        );
    }

}
