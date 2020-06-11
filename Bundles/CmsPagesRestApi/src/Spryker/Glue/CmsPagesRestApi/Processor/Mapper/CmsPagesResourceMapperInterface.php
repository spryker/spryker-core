<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CmsPagesRestApi\Processor\Mapper;

use Generated\Shared\Transfer\RestCmsPagesAttributesTransfer;

interface CmsPagesResourceMapperInterface
{
    /**
     * @phpstan-param array<string, mixed> $searchResult
     *
     * @param array $searchResult
     * @param \Generated\Shared\Transfer\RestCmsPagesAttributesTransfer $restCmsPagesAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestCmsPagesAttributesTransfer
     */
    public function mapSearchResultToRestAttributesTransfer(
        array $searchResult,
        RestCmsPagesAttributesTransfer $restCmsPagesAttributesTransfer
    ): RestCmsPagesAttributesTransfer;
}
