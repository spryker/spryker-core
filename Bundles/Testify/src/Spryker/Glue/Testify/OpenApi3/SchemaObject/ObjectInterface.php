<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\Testify\OpenApi3\SchemaObject;

use Spryker\Glue\Testify\OpenApi3\SchemaFieldInterface;

interface ObjectInterface extends SchemaFieldInterface
{
    /**
     * @return \Spryker\Glue\Testify\OpenApi3\SchemaObject\ObjectSpecification
     */
    public function getObjectSpecification(): ObjectSpecification;

    /**
     * @return $this
     */
    public function export();
}
