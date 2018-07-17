<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\SearchRestApi\Processor\Mapper;

use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;

interface SuggestionsResourceMapperInterface
{
    /**
     * @param array $restSearchResponse
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    public function mapSuggestionsResponseAttributesTransferToRestResponse(array $restSearchResponse): RestResourceInterface;

    /**
     * @return array
     */
    public function getSearchResponseDefaultStructure(): array;
}
