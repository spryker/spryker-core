<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Shared\Library\Exception;

abstract class AbstractErrorRendererException extends \Exception
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
