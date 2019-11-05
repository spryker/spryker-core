<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Search\Business\DataMapper;

use Exception;
use Generated\Shared\Transfer\DataMappingContextTransfer;

class SearchDataMapper implements SearchDataMapperInterface
{
    /**
     * @var array
     */
    private $dataMapperPlugins;

    /**
     * @param \Spryker\Zed\SearchExtension\Dependency\Plugin\SearchDataMapperPluginInterface[] $dataMapperPlugins
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
        $dataMapperPlugin = $this->getDataMapperPluginByDataResourceType($dataMappingContextTransfer);

        return $dataMapperPlugin->mapRawDataToSearchData($data, $dataMappingContextTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\DataMappingContextTransfer $dataMappingContextTransfer
     *
     * @throws \Exception
     *
     * @return mixed|\Spryker\Zed\SearchExtension\Dependency\Plugin\SearchDataMapperPluginInterface
     */
    protected function getDataMapperPluginByDataResourceType(DataMappingContextTransfer $dataMappingContextTransfer)
    {
        $dataMappingContextTransfer->requireMapperName();

        foreach ($this->dataMapperPlugins as $dataMapperPlugin) {
            if ($dataMapperPlugin->isApplicable($dataMappingContextTransfer->getMapperName())) {
                return $dataMapperPlugin;
            }
        }

        throw new Exception(
            sprintf('No applicable mapper plugin found for name "%s"', $dataMappingContextTransfer->getMapperName())
        );
    }
}
