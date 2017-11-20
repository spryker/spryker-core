<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Setup\Business\Model;

class System
{
    /**
     * @param string|null $what
     *
     * @return string
     */
    public function getPhpInfo($what = null)
    {
        ob_start();
        if (isset($what)) {
            phpinfo($what);
        } else {
            phpinfo();
        }

        return ob_get_clean();
    }
}
