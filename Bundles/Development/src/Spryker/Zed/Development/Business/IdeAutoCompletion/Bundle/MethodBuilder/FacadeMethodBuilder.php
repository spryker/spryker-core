<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\IdeAutoCompletion\Bundle\MethodBuilder;

class FacadeMethodBuilder extends AbstractBundleMethodBuilder
{

    /**
     * @return string
     */
    protected function getMethodName()
    {
        return 'facade';
    }

    /**
     * @param string $bundleDirectory
     *
     * @return string
     */
    protected function getSearchPathGlobPattern($bundleDirectory)
    {
        return sprintf('%s/*/Business/', $bundleDirectory);
    }

}
