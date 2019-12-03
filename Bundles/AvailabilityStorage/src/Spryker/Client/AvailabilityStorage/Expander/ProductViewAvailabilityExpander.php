<?php
/**
 * Created by PhpStorm.
 * User: smarovydlo
 * Date: 12/2/19
 * Time: 3:04 PM
 */

namespace Spryker\AvailabilityStorage\src\Spryker\Client\AvailabilityStorage\Expander;

use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Client\AvailabilityStorage\Storage\AvailabilityStorageReaderInterface;

class ProductViewAvailabilityExpander implements ProductViewAvailabilityExpanderInterface
{
    /**
     * @var \Spryker\Client\AvailabilityStorage\Storage\AvailabilityStorageReaderInterface
     */
    protected $availabilityStorageReader;

    /**
     * @var \Spryker\Client\AvailabilityStorageExtension\Dependency\Plugin\PostProductViewAvailabilityStorageExpandPluginInterface[]
     */
    protected $postProductViewAvailabilityStorageExpandPlugins;

    /**
     * @param \Spryker\Client\AvailabilityStorage\Storage\AvailabilityStorageReaderInterface $availabilityStorageReader
     * @param \Spryker\Client\AvailabilityStorageExtension\Dependency\Plugin\PostProductViewAvailabilityStorageExpandPluginInterface[] $postProductViewAvailabilityStorageExpandPlugins
     */
    public function __construct(
        AvailabilityStorageReaderInterface $availabilityStorageReader,
        array $postProductViewAvailabilityStorageExpandPlugins
    ) {
        $this->availabilityStorageReader = $availabilityStorageReader;
        $this->postProductViewAvailabilityStorageExpandPlugins = $postProductViewAvailabilityStorageExpandPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer
     */
    public function expandProductViewWithAvailability(ProductViewTransfer $productViewTransfer): ProductViewTransfer
    {
        $storageAvailabilityTransfer = $this->availabilityStorageReader
            ->getAvailabilityAbstractAsStorageTransfer($productViewTransfer->getIdProductAbstract());

        if (!$productViewTransfer->getIdProductConcrete()) {
            $productViewTransfer->setAvailable($storageAvailabilityTransfer->getIsAbstractProductAvailable());

            return $productViewTransfer;
        }

        $concreteProductAvailableItems = $storageAvailabilityTransfer->getConcreteProductAvailableItems();
        if (isset($concreteProductAvailableItems[$productViewTransfer->getSku()])) {
            $productViewTransfer->setAvailable($concreteProductAvailableItems[$productViewTransfer->getSku()]);
        }

        $this->executePostProductViewAvailabilityStorageExpandPlugins($productViewTransfer);

        return $productViewTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer
     */
    protected function executePostProductViewAvailabilityStorageExpandPlugins(ProductViewTransfer $productViewTransfer): ProductViewTransfer
    {
        foreach ($this->postProductViewAvailabilityStorageExpandPlugins as $postProductViewAvailabilityStorageExpandPlugin) {
            $productViewTransfer = $postProductViewAvailabilityStorageExpandPlugin->postExpand($productViewTransfer);
        }

        return $productViewTransfer;
    }
}
