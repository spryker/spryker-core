<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SearchElasticsearch\Communication\Plugin\Search;

use Generated\Shared\Transfer\DataMappingContextTransfer;
use Spryker\Zed\SearchElasticsearchExtension\Dependency\Plugin\ResourceDataMapperPluginInterface;

/**
 * @method \Spryker\Zed\SearchElasticsearch\Business\SearchElasticsearchFacadeInterface getFacade()
 * @method \Spryker\Zed\SearchElasticsearch\SearchElasticsearchConfig getConfig()
 * @method \Spryker\Zed\SearchElasticsearch\Communication\SearchElasticsearchCommunicationFactory getFactory()
 */
class ProductReviewDataMapperPlugin implements ResourceDataMapperPluginInterface
{
    /**
     * {@inheritDoc}
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
        return $this->getFacade()->mapProductReviewDataToSearchData($data, $dataMappingContextTransfer);
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
        return $dataMappingContextTransfer->getResourceName() === $this->getConfig()->getProductReviewResourceName();
    }
}
