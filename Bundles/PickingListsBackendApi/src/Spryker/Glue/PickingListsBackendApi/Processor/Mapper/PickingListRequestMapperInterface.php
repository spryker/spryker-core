<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PickingListsBackendApi\Processor\Mapper;

use ArrayObject;
use stdClass;

interface PickingListRequestMapperInterface
{
    /**
     * @deprecated Should be removed after infrastructure implementation.
     *
     * @param \stdClass $requestBody
     * @param \ArrayObject<\Generated\Shared\Transfer\GlueResourceTransfer> $glueResourceTransferCollection
     *
     * @return \ArrayObject<\Generated\Shared\Transfer\GlueResourceTransfer>
     */
    public function mapRequestBodyToGlueResourceTransferCollection(
        stdClass $requestBody,
        ArrayObject $glueResourceTransferCollection
    ): ArrayObject;
}
