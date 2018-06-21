<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Rest\JsonApi;

use Spryker\Shared\Kernel\Transfer\TransferInterface;

interface RestResourceBuilderInterface
{
    /**
     * @param string $type
     * @param string|null $id
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface|null $attributeTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    public function createRestResource(string $type, ?string $id = null, ?TransferInterface $attributeTransfer = null): RestResourceInterface;

    /**
     * @param int $totalItems
     * @param int $limit
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createRestResponse(int $totalItems = 0, int $limit = 0): RestResponseInterface;
}
