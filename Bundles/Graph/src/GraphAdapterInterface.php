<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Tool\Graph;

interface GraphAdapterInterface extends GraphInterface
{

    /**
     * @param string $name
     * @param array $attributes
     * @param bool $directed
     * @param bool $strict
     *
     * @return void
     */
    public function create($name, array $attributes = [], $directed = true, $strict = true);

}
