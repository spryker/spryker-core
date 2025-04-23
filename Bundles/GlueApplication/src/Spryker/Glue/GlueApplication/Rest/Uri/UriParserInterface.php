<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Rest\Uri;

use Symfony\Component\HttpFoundation\Request;

interface UriParserInterface
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|null
     */
    public function parse(Request $request): ?array;
}
