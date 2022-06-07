<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Cache\Reader;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface;

interface ControllerCacheReaderInterface
{
    /**
     * @param callable(): \Generated\Shared\Transfer\GlueResponseTransfer|array<int, string> $executableResource
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface $resource
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return array<string, mixed>|null
     */
    public function getActionParameters($executableResource, ResourceInterface $resource, GlueRequestTransfer $glueRequestTransfer): ?array;
}
