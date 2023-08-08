<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ServicePointsRestApi\Processor\Expander;

interface ServicePointAddressRelationshipExpanderInterface
{
    /**
     * @param list<\Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface> $restResources
     *
     * @return void
     */
    public function addServicePointAddressesResourceRelationshipsByServicePointUuid(array $restResources): void;
}
