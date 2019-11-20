<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SearchElasticsearch\Communication\Plugin\Search;

use Generated\Shared\Transfer\DataMappingContextTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SearchExtension\Dependency\Plugin\DataMapperPluginInterface;

/**
 * @method \Spryker\Zed\SearchElasticsearch\Business\SearchElasticsearchFacadeInterface getFacade()
 * @method \Spryker\Zed\SearchElasticsearch\SearchElasticsearchConfig getConfig()
 * @method \Spryker\Zed\SearchElasticsearch\Communication\SearchElasticsearchCommunicationFactory getFactory()
 */
class ElasticsearchDataMapperPlugin extends AbstractPlugin implements DataMapperPluginInterface
{
    /**
     * {@inheritDoc}
     * - Formats raw data, so it can be indexed by Elasticsearch.
     *
     * @api
     *
     * @param array $data
     * @param \Generated\Shared\Transfer\DataMappingContextTransfer $dataMappingContextTransfer
     *
     * @return array
     */
    public function mapRawDataToSearchData(array $data, DataMappingContextTransfer $dataMappingContextTransfer): array
    {
        return $this->getFacade()->mapRawDataToSearchData($data, $dataMappingContextTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DataMappingContextTransfer $dataMappingContextTransfer
     *
     * @return bool
     */
    public function isApplicable(DataMappingContextTransfer $dataMappingContextTransfer): bool
    {
        return in_array($dataMappingContextTransfer->getResourceName(), $this->getConfig()->getApplicableMappingResourceNames(), true);
    }
}
