<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Search\Business\Definition\Reader;

use Symfony\Component\Finder\SplFileInfo;

interface IndexDefinitionReaderInterface
{
    /**
     * @param \Symfony\Component\Finder\SplFileInfo $fileInfo
     *
     * @return array
     */
    public function read(SplFileInfo $fileInfo): array;
}
