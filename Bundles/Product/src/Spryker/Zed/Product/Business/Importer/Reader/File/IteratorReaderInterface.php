<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Business\Importer\Reader\File;

interface IteratorReaderInterface
{

    /**
     * @param \SplFileInfo $file
     *
     * @return \Iterator
     */
    public function getIteratorFromFile(\SplFileInfo $file);

    /**
     * @param \SplFileInfo $file
     *
     * @return array
     */
    public function getArrayFromFile(\SplFileInfo $file);

}
