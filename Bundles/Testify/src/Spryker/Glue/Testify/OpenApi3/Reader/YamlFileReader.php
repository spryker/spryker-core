<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
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
     * @inheritDoc
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
