<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\SearchRestApi\Processor\Mapper;

use Generated\Shared\Transfer\RestSearchRequestAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;

interface SearchResourceMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\RestSearchRequestAttributesTransfer $restSearchRequestAttributesTransfer
     *
     * @return string
     */
    public function mapRestSearchRequestAttributesTransferToSearchString(RestSearchRequestAttributesTransfer $restSearchRequestAttributesTransfer): string;

    /**
     * @param \Generated\Shared\Transfer\RestSearchRequestAttributesTransfer $restSearchRequestAttributesTransfer
     *
     * @return array
     */
    public function mapRestSearchRequestAttributesTransferToSearchRequestParameters(RestSearchRequestAttributesTransfer $restSearchRequestAttributesTransfer): array;

    /**
     * @param array $restSearchResponse
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    public function mapSearchResponseAttributesTransferToRestResponse(array $restSearchResponse): RestResourceInterface;
}
