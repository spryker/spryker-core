<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\IdeAutoCompletion\Bundle\MethodBuilder;

use Generated\Shared\Transfer\IdeAutoCompletionBundleTransfer;

interface BundleMethodBuilderInterface
{
    /**
     * @param \Generated\Shared\Transfer\IdeAutoCompletionBundleTransfer $bundleTransfer
     *
     * @return null|\Generated\Shared\Transfer\IdeAutoCompletionBundleMethodTransfer
     */
    public function getMethod(IdeAutoCompletionBundleTransfer $bundleTransfer);

    /**
     * @return string
     */
    public function getMethodName();
}
