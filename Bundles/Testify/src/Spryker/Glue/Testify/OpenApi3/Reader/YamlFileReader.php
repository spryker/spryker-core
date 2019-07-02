<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Glue\Testify\OpenApi3\Reader;

use Spryker\Glue\Testify\Dependency\TestifyToYamlAdapterInterface;
use Spryker\Glue\Testify\OpenApi3\ReaderInterface;

class YamlFileReader implements ReaderInterface
{
    /**
     * @var string
     */
    protected $fileName;

    /**
     * @var \Spryker\Glue\Testify\Dependency\TestifyToYamlAdapterInterface
     */
    protected $yamlAdapter;

    /**
     * @param string $fileName
     * @param \Spryker\Glue\Testify\Dependency\TestifyToYamlAdapterInterface $yamlAdapter
     */
    public function __construct(string $fileName, TestifyToYamlAdapterInterface $yamlAdapter)
    {
        $this->fileName = $fileName;
        $this->yamlAdapter = $yamlAdapter;
    }

    /**
     * @inheritdoc
     */
    public function read()
    {
        return $this->yamlAdapter->parseFile($this->fileName);
    }
}
