<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Collector\Business\Exporter\Reader\File;

use Spryker\Zed\Collector\Business\Exporter\Reader\ReaderInterface;

class FileReader implements ReaderInterface
{

    /**
     * @param string $key
     * @param string $type
     *
     * @return bool
     */
    public function read($key, $type = '')
    {
        return false;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'file-reader';
    }


}
