<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SearchElasticsearch\Communication\Plugin\Search;

use Generated\Shared\Transfer\DataMappingContextTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SearchExtension\Dependency\Plugin\SearchDataMapperPluginInterface;

/**
 * @method \Spryker\Zed\SearchElasticsearch\Business\SearchElasticsearchFacadeInterface getFacade()
 * @method \Spryker\Zed\SearchElasticsearch\SearchElasticsearchConfig getConfig()
 * @method \Spryker\Zed\SearchElasticsearch\Communication\SearchElasticsearchCommunicationFactory getFactory()
 */
class ElasticsearchSearchDataMapperPlugin extends AbstractPlugin implements SearchDataMapperPluginInterface
{
    /**
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
     * @api
     *
     * @param string $type
     *
     * @return bool
     */
    public function isApplicable(string $type): bool
    {
        return in_array($type, $this->getConfig()->getApplicableMappingResourceTypes(), true);
    }
}
