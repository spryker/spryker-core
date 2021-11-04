<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ConfigurableBundlePageSearch;

use Generated\Shared\Transfer\ConfigurableBundleTemplatePageSearchRequestTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\ConfigurableBundlePageSearch\ConfigurableBundlePageSearchFactory getFactory()
 */
class ConfigurableBundlePageSearchClient extends AbstractClient implements ConfigurableBundlePageSearchClientInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplatePageSearchRequestTransfer $configurableBundleTemplatePageSearchRequestTransfer
     *
     * @return array
     */
    public function searchConfigurableBundleTemplates(
        ConfigurableBundleTemplatePageSearchRequestTransfer $configurableBundleTemplatePageSearchRequestTransfer
    ): array {
        $requestParameters = $configurableBundleTemplatePageSearchRequestTransfer->getRequestParameters();

        $searchQuery = $this->getFactory()->createConfigurableBundleTemplateSearchQuery($configurableBundleTemplatePageSearchRequestTransfer);
        $searchQuery = $this->getFactory()
            ->getSearchClient()
            ->expandQuery(
                $searchQuery,
                $this->getFactory()->getConfigurableBundleTemplatePageSearchQueryExpanderPlugins(),
                $requestParameters,
            );

        $resultFormatters = $this->getFactory()->getConfigurableBundleTemplateResultFormatterPlugins();

        return $this->getFactory()
            ->getSearchClient()
            ->search($searchQuery, $resultFormatters, $requestParameters);
    }
}
