<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ServicePointsBackendApi\Processor\Extractor;

class GlueResourceExtractor implements GlueResourceExtractorInterface
{
    /**
     * @param list<\Generated\Shared\Transfer\GlueResourceTransfer> $glueResourceTransfers
     *
     * @return list<string>
     */
    public function extractUuidsFromGlueResourceTransfers(array $glueResourceTransfers): array
    {
        $uuids = [];
        foreach ($glueResourceTransfers as $glueResourceTransfer) {
            $uuids[] = $glueResourceTransfer->getIdOrFail();
        }

        return $uuids;
    }
}
