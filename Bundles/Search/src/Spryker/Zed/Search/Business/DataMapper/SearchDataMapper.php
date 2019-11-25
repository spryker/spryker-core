<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Search\Business\DataMapper;

use Generated\Shared\Transfer\DataMappingContextTransfer;
use Spryker\Zed\Search\Business\Exception\SearchDataMapperException;
use Spryker\Zed\SearchExtension\Dependency\Plugin\DataMapperPluginInterface;

class SearchDataMapper implements SearchDataMapperInterface
{
    /**
     * @var \Spryker\Zed\SearchExtension\Dependency\Plugin\DataMapperPluginInterface[]
     */
    protected $dataMapperPlugins;

    /**
     * @param \Spryker\Zed\SearchExtension\Dependency\Plugin\DataMapperPluginInterface[] $dataMapperPlugins
     */
    public function __construct(array $dataMapperPlugins)
    {
        $this->dataMapperPlugins = $dataMapperPlugins;
    }

    /**
     * @param array $data
     * @param \Generated\Shared\Transfer\DataMappingContextTransfer $dataMappingContextTransfer
     *
     * @return array
     */
    public function mapRawDataToSearchData(array $data, DataMappingContextTransfer $dataMappingContextTransfer): array
    {
        $dataMapperPlugin = $this->getDataMapperPlugin($dataMappingContextTransfer);

        return $dataMapperPlugin->mapRawDataToSearchData($data, $dataMappingContextTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\DataMappingContextTransfer $dataMappingContextTransfer
     *
     * @throws \Spryker\Zed\Search\Business\Exception\SearchDataMapperException
     *
     * @return \Spryker\Zed\SearchExtension\Dependency\Plugin\DataMapperPluginInterface
     */
    protected function getDataMapperPlugin(DataMappingContextTransfer $dataMappingContextTransfer): DataMapperPluginInterface
    {
        $dataMappingContextTransfer->requireResourceName();

        foreach ($this->dataMapperPlugins as $dataMapperPlugin) {
            if ($dataMapperPlugin->isApplicable($dataMappingContextTransfer)) {
                return $dataMapperPlugin;
            }
        }

        throw new SearchDataMapperException(
            sprintf('No applicable mapper plugin found for name "%s"', $dataMappingContextTransfer->getResourceName())
        );
    }
}
