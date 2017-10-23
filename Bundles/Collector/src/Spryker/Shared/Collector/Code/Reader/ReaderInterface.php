<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Collector\Code\Reader;

interface ReaderInterface
{
    /**
     * @param string $key
     *
     * @return mixed
     */
    public function read($key);

    /**
     * @return string
     */
    public function getName();
}
