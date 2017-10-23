<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilNetwork\Model;

class Host implements HostInterface
{
    /**
     * @var string
     */
    protected static $hostname;

    /**
     * @return string
     */
    public function getHostname()
    {
        if (!isset(self::$hostname)) {
            self::$hostname = (gethostname()) ?: php_uname('n');
        }

        return self::$hostname;
    }
}
