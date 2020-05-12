<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Propel\Dependency\External;

interface PropelToFileSystemAdapterInterface
{
    /**
     * @param string|iterable $dirs
     * @param int $mode
     *
     * @return void
     */
    public function mkdir($dirs, $mode = 0777);

    /**
     * @param string|iterable $files
     *
     * @return bool
     */
    public function exists($files);
}
