<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Collector\Business\Exporter\Reader;

interface ReaderInterface
{
    /**
     * @param string $key
     * @param string $type
     *
     * @return mixed
     */
    public function read($key, $type = '');

    /**
     * @return string
     */
    public function getName();
}
