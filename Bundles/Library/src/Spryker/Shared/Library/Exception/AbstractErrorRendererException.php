<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Library\Exception;

use Exception;

abstract class AbstractErrorRendererException extends Exception
{

    /**
     * @var string
     */
    protected $extra;

    /**
     * @return string
     */
    public function getExtra()
    {
        return $this->extra;
    }

    /**
     * @param string $extra
     *
     * @return void
     */
    public function setExtra($extra)
    {
        $this->extra = $extra;
    }

}
