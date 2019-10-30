<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ConfigurableBundlePageSearch;

use Generated\Shared\Transfer\ConfigurableBundleTemplatePageSearchRequestTransfer;

interface ConfigurableBundlePageSearchClientInterface
{
    /**
     * Specification:
     * - Filters records using ConfigurableBundleTemplatePageSearchRequestTransfer.
     * - Returns search results.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplatePageSearchRequestTransfer $configurableBundleTemplatePageSearchRequestTransfer
     *
     * @return array
     */
    public function searchConfigurableBundleTemplates(ConfigurableBundleTemplatePageSearchRequestTransfer $configurableBundleTemplatePageSearchRequestTransfer): array;
}
