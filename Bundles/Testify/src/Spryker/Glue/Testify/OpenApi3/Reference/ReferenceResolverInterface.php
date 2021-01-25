<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\Testify\OpenApi3\Reference;

use Spryker\Glue\Testify\OpenApi3\SchemaFieldInterface;

interface ReferenceResolverInterface
{
    /**
     * @param string $reference
     *
     * @return \Spryker\Glue\Testify\OpenApi3\SchemaFieldInterface
     */
    public function resolveReference(string $reference): SchemaFieldInterface;
}
