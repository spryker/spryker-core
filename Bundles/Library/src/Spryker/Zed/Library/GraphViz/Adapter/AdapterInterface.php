<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Library\GraphViz\Adapter;

use Spryker\Zed\Library\GraphViz\GraphVizInterface;

interface AdapterInterface extends GraphVizInterface
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
