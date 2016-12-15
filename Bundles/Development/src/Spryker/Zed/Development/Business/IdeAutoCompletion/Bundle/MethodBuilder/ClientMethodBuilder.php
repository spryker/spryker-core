<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\IdeAutoCompletion\Bundle\MethodBuilder;

class ClientMethodBuilder extends AbstractBundleMethodBuilder
{

    /**
     * @return string
     */
    public function getMethodName()
    {
        return 'client';
    }

    /**
     * @param string $bundleDirectory
     *
     * @return string
     */
    protected function getSearchDirectoryGlobPattern($bundleDirectory)
    {
        return sprintf('%s/*/', $bundleDirectory);
    }

}
