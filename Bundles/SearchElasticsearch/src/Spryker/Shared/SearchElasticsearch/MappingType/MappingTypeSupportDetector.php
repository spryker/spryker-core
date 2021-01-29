<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\SearchElasticsearch\MappingType;

use Elastica\Index;

class MappingTypeSupportDetector implements MappingTypeSupportDetectorInterface
{
    /**
     * @return bool
     */
    public function isMappingTypeSupported(): bool
    {
        return method_exists(Index::class, 'getType');
    }
}
