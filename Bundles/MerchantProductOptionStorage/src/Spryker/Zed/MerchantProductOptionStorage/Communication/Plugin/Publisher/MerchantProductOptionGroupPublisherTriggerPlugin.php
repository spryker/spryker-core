<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOptionStorage\Communication\Plugin\Publisher;

use Generated\Shared\Transfer\MerchantProductOptionGroupCriteriaTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Spryker\Shared\MerchantProductOptionStorage\MerchantProductOptionStorageConfig;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PublisherExtension\Dependency\Plugin\PublisherTriggerPluginInterface;

/**
 * @method \Spryker\Zed\MerchantProductOptionStorage\MerchantProductOptionStorageConfig getConfig()
 * @method \Spryker\Zed\MerchantProductOptionStorage\Communication\MerchantProductOptionStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\MerchantProductOptionStorage\Business\MerchantProductOptionStorageFacadeInterface getFacade()
 */
class MerchantProductOptionGroupPublisherTriggerPlugin extends AbstractPlugin implements PublisherTriggerPluginInterface
{
    /**
     * @uses \Orm\Zed\MerchantProductOption\Persistence\Map\SpyMerchantProductOptionGroupTableMap::COL_ID_MERCHANT_PRODUCT_OPTION_GROUP
     *
     * @var string
     */
    protected const COL_ID_MERCHANT_PRODUCT_OPTION_GROUP = 'spy_merchant_product_option_group.id_merchant_product_option_group';

    /**
     * {@inheritDoc}
     * - Retrieves collection of merchant product option groups by offset and limit from Persistence.
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
        $merchantProductOptionGroupCriteriaTransfer = $this->createMerchantProductOptionGroupCriteriaTransfer($offset, $limit);

        return $this->getFactory()->getMerchantProductOptionFacade()
            ->getMerchantProductOptionGroupCollection($merchantProductOptionGroupCriteriaTransfer)
            ->getMerchantProductOptionGroups()->getArrayCopy();
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
        return MerchantProductOptionStorageConfig::MERCHANT_PRODUCT_OPTION_GROUP_RESOURCE_NAME;
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
        return MerchantProductOptionStorageConfig::MERCHANT_PRODUCT_OPTION_GROUP_PUBLISH;
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
        return static::COL_ID_MERCHANT_PRODUCT_OPTION_GROUP;
    }

    /**
     * @param int $offset
     * @param int $limit
     *
     * @return \Generated\Shared\Transfer\MerchantProductOptionGroupCriteriaTransfer
     */
    protected function createMerchantProductOptionGroupCriteriaTransfer(int $offset, int $limit): MerchantProductOptionGroupCriteriaTransfer
    {
        return (new MerchantProductOptionGroupCriteriaTransfer())
            ->setPagination(
                (new PaginationTransfer())->setOffset($offset)->setLimit($limit),
            );
    }
}
