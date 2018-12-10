<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DocumentationGeneratorRestApi\Business\Renderer\Component;

use Generated\Shared\Transfer\SchemaComponentTransfer;

interface SchemaSpecificationComponentInterface extends SpecificationComponentInterface
{
    /**
     * @param \Generated\Shared\Transfer\SchemaComponentTransfer $schemaComponentTransfer
     *
     * @return void
     */
    public function setSchemaComponentTransfer(SchemaComponentTransfer $schemaComponentTransfer): void;
}
