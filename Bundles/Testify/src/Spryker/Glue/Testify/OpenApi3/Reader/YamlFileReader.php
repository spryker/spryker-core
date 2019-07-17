<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Glue\Testify\OpenApi3\Reader;

use Spryker\Glue\Testify\OpenApi3\Exception\ParseException;
use Spryker\Glue\Testify\OpenApi3\ReaderInterface;
use Symfony\Component\Yaml\Exception\ParseException as YamlParseException;
use Symfony\Component\Yaml\Yaml;

class YamlFileReader implements ReaderInterface
{
    /**
     * @var string
     */
    protected $fileName;

    /**
     * @param string $fileName
     */
    public function __construct(string $fileName)
    {
        $this->fileName = $fileName;
    }

    /**
     * @inheritdoc
     *
     * @throws \Spryker\Glue\Testify\OpenApi3\Exception\ParseException
     */
    public function read()
    {
        try {
            return Yaml::parseFile($this->fileName);
        } catch (YamlParseException $exception) {
            throw new ParseException($exception->getMessage());
        }
    }
}
