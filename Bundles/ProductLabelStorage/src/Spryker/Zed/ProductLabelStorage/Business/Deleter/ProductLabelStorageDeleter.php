<?php


namespace Spryker\Zed\ProductLabelStorage\Business\Deleter;


use Spryker\Zed\ProductLabelStorage\Dependency\Facade\ProductLabelStorageToEventBehaviorFacadeInterface;
use Spryker\Zed\ProductLabelStorage\Persistence\ProductAbstractLabelStorage\ProductAbstractLabelStorageEntityManager;
use Spryker\Zed\ProductLabelStorage\Persistence\ProductLabelStorageEntityManagerInterface;

class ProductLabelStorageDeleter implements ProductLabelStorageDeleterInterface
{
    /**
     * @var ProductLabelStorageToEventBehaviorFacadeInterface
     */
    protected $eventBehaviorFacade;
    /**
     * @var ProductLabelStorageEntityManagerInterface
     */
    protected $productLabelStorageEntityManager;

    /**
     * @param ProductLabelStorageToEventBehaviorFacadeInterface $productLabelStorageToEventBehaviorFacade
     * @param ProductLabelStorageEntityManagerInterface $productLabelStorageEntityManager
     */
    public function __construct(
        ProductLabelStorageToEventBehaviorFacadeInterface $productLabelStorageToEventBehaviorFacade,
        ProductLabelStorageEntityManagerInterface $productLabelStorageEntityManager
    )
    {
        $this->eventBehaviorFacade = $productLabelStorageToEventBehaviorFacade;
        $this->productLabelStorageEntityManager = $productLabelStorageEntityManager;
    }

    /**
     * @deprecated
     *
     * @param int[] $productAbstractIds
     *
     * @return void
     */
    public function unpublish(array $productAbstractIds): void
    {
        $this->deleteProductLabelStorageCollectionByProductAbstractEvents($productAbstractIds);
    }

    /**
     * @param array $eventTransfers
     *
     * @return array
     */
    public function deleteProductLabelStorageCollectionByProductAbstractEvents(array $eventTransfers): void
    {
        $productAbstractIds = $this->eventBehaviorFacade->getEventTransferIds($eventTransfers);

        $this->productLabelStorageEntityManager->deleteProductAbstractLabelStoragesByProductAbstractIds($productAbstractIds);
    }
}
