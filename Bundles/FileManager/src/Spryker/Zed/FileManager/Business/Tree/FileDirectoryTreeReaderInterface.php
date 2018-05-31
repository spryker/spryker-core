<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManager\Business\Tree;

use Generated\Shared\Transfer\LocaleTransfer;

interface FileDirectoryTreeReaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\LocaleTransfer|null $localeTransfer
     *
     * @return \Generated\Shared\Transfer\FileDirectoryTreeTransfer
     */
    public function findFileDirectoryTree(?LocaleTransfer $localeTransfer = null);
}
