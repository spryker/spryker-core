<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Library\Import;

interface ReaderInterface
{

    /**
     * @param mixed $inputData
     *
     * @return \Spryker\Zed\Library\Import\Input
     */
    public function read($inputData);

}
