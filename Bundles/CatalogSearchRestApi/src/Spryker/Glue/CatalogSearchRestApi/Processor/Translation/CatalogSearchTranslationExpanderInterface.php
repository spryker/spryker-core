<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CatalogSearchRestApi\Processor\Translation;

use Generated\Shared\Transfer\RestCatalogSearchAttributesTransfer;

interface CatalogSearchTranslationExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\RestCatalogSearchAttributesTransfer $restCatalogSearchAttributesTransfer
     * @param string $localName
     *
     * @return \Generated\Shared\Transfer\RestCatalogSearchAttributesTransfer
     */
    public function addTranslations(
        RestCatalogSearchAttributesTransfer $restCatalogSearchAttributesTransfer,
        string $localName
    ): RestCatalogSearchAttributesTransfer;
}
