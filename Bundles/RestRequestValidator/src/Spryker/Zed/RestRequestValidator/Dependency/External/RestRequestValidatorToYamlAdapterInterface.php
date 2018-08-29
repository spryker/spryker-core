<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\RestRequestValidator\Dependency\External;

interface RestRequestValidatorToYamlAdapterInterface
{
    /**
     * @param array $input
     * @param int $inline
     * @param int $indent
     * @param int $flags
     *
     * @return string
     */
    public function dump(array $input, $inline = 2, $indent = 4, $flags = 0): string;

    /**
     * @param string $filename
     * @param int $flags
     *
     * @return array
     */
    public function parseFile(string $filename, $flags = 0): array;
}
