<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\SearchElasticsearch\MappingType;

use Elastica\Type;

class MappingTypeSupportDetector implements MappingTypeSupportDetectorInterface
{
    /**
     * @return bool
     */
    public function isMappingTypesSupported(): bool
    {
        return class_exists(Type::class);
    }
}
