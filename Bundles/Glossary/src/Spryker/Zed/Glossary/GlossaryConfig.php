<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Glossary;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class GlossaryConfig extends AbstractBundleConfig
{

    /**
     * @return array
     */
    public function getGlossaryFilePaths()
    {
        $paths = array_merge(
            glob(APPLICATION_SOURCE_DIR . '/*/*/*/Resources/glossary.yml'),
            glob(APPLICATION_VENDOR_DIR . '/*/*/src/*/*/*/Resources/glossary.yml')
        );

        return $paths;
    }

}
