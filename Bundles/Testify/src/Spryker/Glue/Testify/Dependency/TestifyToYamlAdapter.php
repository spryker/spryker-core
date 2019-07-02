<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\Testify\Dependency;

use Spryker\Glue\Testify\OpenApi3\Exception\ParseException;
use Symfony\Component\Yaml\Exception\ParseException as YamlParseException;
use Symfony\Component\Yaml\Yaml;

class TestifyToYamlAdapter implements TestifyToYamlAdapterInterface
{
    /**
     * @param string $filename
     * @param int $flags
     *
     * @throws \Spryker\Glue\Testify\OpenApi3\Exception\ParseException
     *
     * @return array
     */
    public function parseFile(string $filename, int $flags = 0): array
    {
        try {
            return Yaml::parseFile($filename);
        } catch (YamlParseException $exception) {
            throw new ParseException($exception->getMessage());
        }
    }
}
