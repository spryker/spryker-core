<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\DependencyTree\DependencyTreeReader;

class JsonDependencyTreeReader implements DependencyTreeReaderInterface
{
    /**
     * @var string
     */
    private $pathToJson;

    /**
     * @param string $pathToJson
     */
    public function __construct($pathToJson)
    {
        $this->pathToJson = $pathToJson;
    }

    /**
     * @return array
     */
    public function read()
    {
        return json_decode(file_get_contents($this->pathToJson), true);
    }
}
