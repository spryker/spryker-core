<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ServicePointsBackendApi\Processor\Filter;

class GlueResourceFilter implements GlueResourceFilterInterface
{
    /**
     * @param list<\Generated\Shared\Transfer\GlueResourceTransfer> $glueResourceTransfers
     * @param string $resourceType
     *
     * @return list<\Generated\Shared\Transfer\GlueResourceTransfer>
     */
    public function filterGlueResourcesByType(array $glueResourceTransfers, string $resourceType): array
    {
        $filteredByResourceTypeGlueResourceTransfers = [];
        foreach ($glueResourceTransfers as $glueResourceTransfer) {
            if ($glueResourceTransfer->getType() === $resourceType) {
                $filteredByResourceTypeGlueResourceTransfers[] = $glueResourceTransfer;
            }
        }

        return $filteredByResourceTypeGlueResourceTransfers;
    }
}
