<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ConfigurableBundlesRestApi\Dependency\Client;

use Generated\Shared\Transfer\ConfigurableBundleTemplatePageSearchRequestTransfer;

class ConfigurableBundlesRestApiToConfigurableBundlePageSearchClientBridge implements ConfigurableBundlesRestApiToConfigurableBundlePageSearchClientInterface
{
    /**
     * @var \Spryker\Client\ConfigurableBundlePageSearch\ConfigurableBundlePageSearchClientInterface
     */
    protected $configurableBundlePageSearchClient;

    /**
     * @param \Spryker\Client\ConfigurableBundlePageSearch\ConfigurableBundlePageSearchClientInterface $configurableBundlePageSearchClient
     */
    public function __construct($configurableBundlePageSearchClient)
    {
        $this->configurableBundlePageSearchClient = $configurableBundlePageSearchClient;
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplatePageSearchRequestTransfer $configurableBundleTemplatePageSearchRequestTransfer
     *
     * @return array
     */
    public function searchConfigurableBundleTemplates(
        ConfigurableBundleTemplatePageSearchRequestTransfer $configurableBundleTemplatePageSearchRequestTransfer
    ): array {
        return $this->configurableBundlePageSearchClient
            ->searchConfigurableBundleTemplates($configurableBundleTemplatePageSearchRequestTransfer);
    }
}
