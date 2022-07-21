<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueBackendApiApplication\Dependency\External;

use Symfony\Component\Yaml\Yaml;

class GlueBackendApiApplicationToYamlAdapter implements GlueBackendApiApplicationToYamlAdapterInterface
{
    /**
     * @param string $filename
     * @param int $flags
     *
     * @return array<mixed>
     */
    public function parseFile(string $filename, int $flags = 0): array
    {
        return Yaml::parseFile($filename, $flags);
    }
}
