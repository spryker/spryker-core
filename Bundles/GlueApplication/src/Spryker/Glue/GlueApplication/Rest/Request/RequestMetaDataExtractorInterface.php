<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Rest\Request;

use Spryker\Glue\GlueApplication\Rest\Request\Data\Metadata;
use Symfony\Component\HttpFoundation\Request;

/**
 * @deprecated Will be removed without replacement.
 */
interface RequestMetaDataExtractorInterface
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Glue\GlueApplication\Rest\Request\Data\Metadata
     */
    public function extract(Request $request): Metadata;
}
