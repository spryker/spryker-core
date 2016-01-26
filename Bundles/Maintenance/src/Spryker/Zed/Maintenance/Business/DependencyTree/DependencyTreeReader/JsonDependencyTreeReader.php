<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Maintenance\Business\DependencyTree\DependencyTreeReader;

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
