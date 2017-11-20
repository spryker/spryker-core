<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\IdeAutoCompletion\Bundle\MethodBuilder;

use Generated\Shared\Transfer\IdeAutoCompletionBundleTransfer;

class ServiceMethodBuilder extends AbstractBundleMethodBuilder
{
    /**
     * @return string
     */
    public function getMethodName()
    {
        return 'service';
    }

    /**
     * @param \Generated\Shared\Transfer\IdeAutoCompletionBundleTransfer $bundleTransfer
     *
     * @return string
     */
    protected function getSearchDirectory(IdeAutoCompletionBundleTransfer $bundleTransfer)
    {
        return sprintf(
            '%s%s/',
            $bundleTransfer->getDirectory(),
            $bundleTransfer->getName()
        );
    }
}
