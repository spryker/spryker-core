<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\Testify\OpenApi3\Primitive;

use Spryker\Glue\Testify\OpenApi3\Collection\AbstractCollection;
use Spryker\Glue\Testify\OpenApi3\Property\PropertyDefinition;

class Enumeration extends AbstractCollection
{
    /**
     * @inheritDoc
     */
    public function getElementDefinition(): PropertyDefinition
    {
        return new PropertyDefinition(Any::class);
    }
}
