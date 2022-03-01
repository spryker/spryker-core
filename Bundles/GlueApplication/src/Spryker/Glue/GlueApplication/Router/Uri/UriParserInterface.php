<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Router\Uri;

interface UriParserInterface
{
    /**
     * @param string $path
     *
     * @return array|null
     */
    public function parse(string $path): ?array;
}
