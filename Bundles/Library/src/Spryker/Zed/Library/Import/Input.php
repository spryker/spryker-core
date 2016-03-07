<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Library\Import;

class Input
{

    /**
     * @var string
     */
    protected $source;

    /**
     * @var array
     */
    protected $data;

    /**
     * @param string $source
     * @param array $data
     */
    public function __construct($source, $data)
    {
        $this->source = $source;
        $this->data = $data;
    }

    /**
     * @return string
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

}
