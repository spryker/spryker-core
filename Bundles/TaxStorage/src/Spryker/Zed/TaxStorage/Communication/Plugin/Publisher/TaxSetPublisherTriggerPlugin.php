<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxStorage\Communication\Plugin\Publisher;

use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\TaxSetCriteriaTransfer;
use Spryker\Shared\TaxStorage\TaxStorageConfig;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PublisherExtension\Dependency\Plugin\PublisherTriggerPluginInterface;

/**
 * @method \Spryker\Zed\TaxStorage\TaxStorageConfig getConfig()
 * @method \Spryker\Zed\TaxStorage\Business\TaxStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\TaxStorage\Communication\TaxStorageCommunicationFactory getFactory()
 */
class TaxSetPublisherTriggerPlugin extends AbstractPlugin implements PublisherTriggerPluginInterface
{
    /**
     * @uses \Orm\Zed\Tax\Persistence\Map\SpyTaxSetTableMap::COL_ID_TAX_SET
     *
     * @var string
     */
    protected const COL_ID_TAX_SET = 'spy_tax_set.id_tax_set';

    /**
     * {@inheritDoc}
     * - Retrieves tax sets by offset and limit from Persistence.
     *
     * @api
     *
     * @param int $offset
     * @param int $limit
     *
     * @return array<\Generated\Shared\Transfer\TaxSetTransfer|\Spryker\Shared\Kernel\Transfer\AbstractTransfer>
     */
    public function getData(int $offset, int $limit): array
    {
        $taxSetCriteriaTransfer = $this->createTaxSetCriteria($offset, $limit);

        return $this->getFactory()
            ->getTaxFacade()
            ->getTaxSetCollection($taxSetCriteriaTransfer)
            ->getTaxSets()
            ->getArrayCopy();
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
        return TaxStorageConfig::TAX_SET_RESOURCE_NAME;
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
        return TaxStorageConfig::TAX_SET_PUBLISH;
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
        return static::COL_ID_TAX_SET;
    }

    /**
     * @param int $offset
     * @param int $limit
     *
     * @return \Generated\Shared\Transfer\TaxSetCriteriaTransfer
     */
    protected function createTaxSetCriteria(int $offset, int $limit): TaxSetCriteriaTransfer
    {
        return (new TaxSetCriteriaTransfer())
            ->setPagination(
                (new PaginationTransfer())
                    ->setOffset($offset)
                    ->setLimit($limit),
            );
    }
}
