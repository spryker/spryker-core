<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Yves\Kernel\Controller;

use SprykerEngine\Shared\Kernel\Communication\RouteNameResolverInterface;

class RouteNameResolver implements RouteNameResolverInterface
{

    /**
     * @var string
     */
    private $path;

    /**
     * @param string $path
     */
    public function __construct($path)
    {
        $this->path = $path;
    }

    /**
     * @return string
     */
    public function resolve()
    {
        return $this->path;
    }

}
