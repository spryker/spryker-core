<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductSearch\Communication\Plugin\Publisher;

use Generated\Shared\Transfer\MerchantProductAbstractCriteriaTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Spryker\Shared\MerchantProductSearch\MerchantProductSearchConfig;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PublisherExtension\Dependency\Plugin\PublisherTriggerPluginInterface;

/**
 * @method \Spryker\Zed\MerchantProductSearch\Business\MerchantProductSearchFacadeInterface getFacade()
 * @method \Spryker\Zed\MerchantProductSearch\Communication\MerchantProductSearchCommunicationFactory getFactory()
 * @method \Spryker\Zed\MerchantProductSearch\MerchantProductSearchConfig getConfig()
 */
class MerchantProductSearchPublisherTriggerPlugin extends AbstractPlugin implements PublisherTriggerPluginInterface
{
    /**
     * @uses \Orm\Zed\MerchantProduct\Persistence\Map\SpyMerchantProductAbstractTableMap::COL_ID_MERCHANT_PRODUCT_ABSTRACT
     *
     * @var string
     */
    protected const COL_ID_MERCHANT_PRODUCT_ABSTRACT = 'spy_merchant_product_abstract.id_merchant_product_abstract';

    /**
     * {@inheritDoc}
     * - Retrieves collection of abstract merchant products by offset and limit from Persistence.
     *
     * @api
     *
     * @param int $offset
     * @param int $limit
     *
     * @return array<\Spryker\Shared\Kernel\Transfer\AbstractTransfer>
     */
    public function getData(int $offset, int $limit): array
    {
        $merchantProductAbstractCriteriaTransfer = $this->createMerchantProductAbstractCriteriaTransfer($offset, $limit);

        return $this->getFactory()
            ->getMerchantProductFacade()
            ->getMerchantProductAbstractCollection($merchantProductAbstractCriteriaTransfer)
            ->getMerchantProductAbstracts()->getArrayCopy();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getResourceName(): string
    {
        return MerchantProductSearchConfig::MERCHANT_PRODUCT_SEARCH_RESOURCE_NAME;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getEventName(): string
    {
        return MerchantProductSearchConfig::MERCHANT_PRODUCT_ABSTRACT_PUBLISH;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string|null
     */
    public function getIdColumnName(): ?string
    {
        return static::COL_ID_MERCHANT_PRODUCT_ABSTRACT;
    }

    /**
     * @param int $offset
     * @param int $limit
     *
     * @return \Generated\Shared\Transfer\MerchantProductAbstractCriteriaTransfer
     */
    protected function createMerchantProductAbstractCriteriaTransfer(int $offset, int $limit): MerchantProductAbstractCriteriaTransfer
    {
        $paginationTransfer = (new PaginationTransfer())->setOffset($offset)->setLimit($limit);

        return (new MerchantProductAbstractCriteriaTransfer())->setPagination($paginationTransfer);
    }
}
