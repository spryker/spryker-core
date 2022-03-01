<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Rest;

/**
 * @deprecated Will be removed without replacement.
 */
interface ResourceRelationshipLoaderInterface
{
    /**
     * @param string $resourceName
     *
     * @return array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRelationshipPluginInterface>
     */
    public function load(string $resourceName): array;
}
