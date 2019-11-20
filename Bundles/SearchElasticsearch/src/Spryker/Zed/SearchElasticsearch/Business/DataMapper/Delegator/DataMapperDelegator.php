<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SearchElasticsearch\Business\DataMapper\Delegator;

use Generated\Shared\Transfer\DataMappingContextTransfer;
use Spryker\Zed\SearchElasticsearch\Business\Exception\DataMapperNotFoundException;
use Spryker\Zed\SearchElasticsearchExtension\Dependency\Plugin\ResourceDataMapperPluginInterface;

class DataMapperDelegator implements DataMapperDelegatorInterface
{
    /**
     * @var \Spryker\Zed\SearchElasticsearchExtension\Dependency\Plugin\ResourceDataMapperPluginInterface[]
     */
    protected $resourceDataMapperPlugins;

    /**
     * @param \Spryker\Zed\SearchElasticsearchExtension\Dependency\Plugin\ResourceDataMapperPluginInterface[] $resourceDataMapperPlugins
     */
    public function __construct(array $resourceDataMapperPlugins)
    {
        $this->resourceDataMapperPlugins = $resourceDataMapperPlugins;
    }

    /**
     * @param array $data
     * @param \Generated\Shared\Transfer\DataMappingContextTransfer $dataMappingContextTransfer
     *
     * @return array
     */
    public function mapRawDataToSearchData(array $data, DataMappingContextTransfer $dataMappingContextTransfer): array
    {
        $resourceDataMapper = $this->resolveDataMapper($dataMappingContextTransfer);

        return $resourceDataMapper->mapRawDataToSearchData($data, $dataMappingContextTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\DataMappingContextTransfer $dataMappingContextTransfer
     *
     * @throws \Spryker\Zed\SearchElasticsearch\Business\Exception\DataMapperNotFoundException
     *
     * @return \Spryker\Zed\SearchElasticsearchExtension\Dependency\Plugin\ResourceDataMapperPluginInterface
     */
    protected function resolveDataMapper(DataMappingContextTransfer $dataMappingContextTransfer): ResourceDataMapperPluginInterface
    {
        foreach ($this->resourceDataMapperPlugins as $resourceDataMapperPlugin) {
            if ($resourceDataMapperPlugin->isApplicable($dataMappingContextTransfer)) {
                return $resourceDataMapperPlugin;
            }
        }

        throw new DataMapperNotFoundException();
    }
}
