<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilDataReader\Dependency;

class YamlReaderBridge implements YamlReaderInterface
{
    /**
     * @var \Symfony\Component\Yaml\Yaml
     */
    protected $yamlReader;

    /**
     * @param \Symfony\Component\Yaml\Yaml $yamlReader
     */
    public function __construct($yamlReader)
    {
        $this->yamlReader = $yamlReader;
    }

    /**
     * @param string $fileName
     *
     * @return array
     */
    public function parse($fileName)
    {
        return $this->yamlReader->parse($fileName);
    }
}
