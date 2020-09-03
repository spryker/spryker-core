<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\DependencyTree\DependencyTreeReader;

use RuntimeException;

class JsonDependencyTreeReader implements DependencyTreeReaderInterface
{
    /**
     * @var string
     */
    protected $pathToJson;

    /**
     * @param string $pathToJson
     */
    public function __construct($pathToJson)
    {
        $this->pathToJson = $pathToJson;
    }

    /**
     * @throws \RuntimeException
     *
     * @return array
     */
    public function read()
    {
        if (!file_exists($this->pathToJson)) {
            throw new RuntimeException('You need to run "vendor/bin/console dev:dependency:build-tree" before being able to use the dependency tree.');
        }

        return json_decode(file_get_contents($this->pathToJson), true);
    }
}
