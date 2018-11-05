<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CatalogSearchRestApi\Processor\Mapper;

use Generated\Shared\Transfer\RestCatalogSearchSuggestionsAttributesTransfer;

interface CatalogSearchSuggestionsResourceMapperInterface
{
    /**
     * @param array $restSearchResponse
     * @param string $currency
     *
     * @return \Generated\Shared\Transfer\RestCatalogSearchSuggestionsAttributesTransfer
     */
    public function mapSuggestionsToRestAttributesTransfer(array $restSearchResponse, string $currency): RestCatalogSearchSuggestionsAttributesTransfer;

    /**
     * @return array
     */
    public function getEmptySearchResponse(): array;
}
