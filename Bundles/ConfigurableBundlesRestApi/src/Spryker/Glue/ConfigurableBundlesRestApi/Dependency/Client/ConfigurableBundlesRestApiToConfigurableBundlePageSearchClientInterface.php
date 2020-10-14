<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ConfigurableBundlesRestApi\Dependency\Client;

use Generated\Shared\Transfer\ConfigurableBundleTemplatePageSearchRequestTransfer;

interface ConfigurableBundlesRestApiToConfigurableBundlePageSearchClientInterface
{
    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplatePageSearchRequestTransfer $configurableBundleTemplatePageSearchRequestTransfer
     *
     * @return array
     */
    public function searchConfigurableBundleTemplates(
        ConfigurableBundleTemplatePageSearchRequestTransfer $configurableBundleTemplatePageSearchRequestTransfer
    ): array;
}
