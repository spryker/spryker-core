<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ProductBundleStorage\Business;

use Generated\Shared\Transfer\FilterTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ProductBundleStorage\Business\ProductBundleStorageBusinessFactory getFactory()
 * @method \Spryker\Zed\ProductBundleStorage\Persistence\ProductBundleStorageRepositoryInterface getRepository()
 * @method \Spryker\Zed\ProductBundleStorage\Persistence\ProductBundleStorageEntityManagerInterface getEntityManager()
 */
class ProductBundleStorageFacade extends AbstractFacade implements ProductBundleStorageFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     *
     * @return void
     */
    public function writeCollectionByProductBundlePublishEvents(array $eventTransfers): void
    {
        $this->getFactory()
            ->createProductBundleStorageWriter()
            ->writeCollectionByProductBundlePublishEvents($eventTransfers);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     *
     * @return void
     */
    public function writeCollectionByProductBundleEvents(array $eventTransfers): void
    {
        $this->getFactory()
            ->createProductBundleStorageWriter()
            ->writeCollectionByProductBundleEvents($eventTransfers);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     *
     * @return void
     */
    public function writeCollectionByProductEvents(array $eventTransfers): void
    {
        $this->getFactory()
            ->createProductBundleStorageWriter()
            ->writeCollectionByProductEvents($eventTransfers);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param int[] $concreteProductIds
     *
     * @return \Generated\Shared\Transfer\SynchronizationDataTransfer[]
     */
    public function getPaginatedProductBundleStorageDataTransfers(FilterTransfer $filterTransfer, array $concreteProductIds): array
    {
        return $this->getRepository()->getPaginatedProductBundleStorageDataTransfers($filterTransfer, $concreteProductIds);
    }
}
