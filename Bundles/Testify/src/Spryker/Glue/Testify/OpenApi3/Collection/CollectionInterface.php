<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\Testify\OpenApi3\Collection;

use Spryker\Glue\Testify\OpenApi3\Property\PropertyDefinition;
use Spryker\Glue\Testify\OpenApi3\SchemaFieldInterface;

interface CollectionInterface extends SchemaFieldInterface
{
    /**
     * @return \Spryker\Glue\Testify\OpenApi3\Property\PropertyDefinition
     */
    public function getElementDefinition(): PropertyDefinition;

    /**
     * @return $this
     */
    public function export();

    /**
     * @return array
     */
    public function toArray(): array;
}
