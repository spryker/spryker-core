<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\RestApiDocumentationGenerator\Dependency\External;

use Symfony\Component\Yaml\Yaml;

class RestApiDocumentationGeneratorToSymfonyYamlAdapter implements RestApiDocumentationGeneratorToYamlDumperInterface
{
    public const DUMP_EMPTY_ARRAY_AS_SEQUENCE = Yaml::DUMP_EMPTY_ARRAY_AS_SEQUENCE;

    /**
     * @param mixed $input
     * @param int $inline
     * @param int $indent
     * @param int $flags
     *
     * @return string
     */
    public function dump($input, int $inline = 2, int $indent = 4, int $flags = 0): string
    {
        return Yaml::dump($input, $inline, $indent, $flags);
    }
}
