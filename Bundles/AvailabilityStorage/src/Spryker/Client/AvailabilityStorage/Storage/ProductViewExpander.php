<?php
/**
 * Created by PhpStorm.
 * User: smarovydlo
 * Date: 11/26/19
 * Time: 11:55 AM
 */

namespace Spryker\AvailabilityStorage\src\Spryker\Client\AvailabilityStorage\Storage;

use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Client\AvailabilityStorage\Storage\AvailabilityStorageReaderInterface;

class ProductViewExpander implements ProductViewExpanderInterface
{
    /**
     * @var \Spryker\Client\AvailabilityStorage\Storage\AvailabilityStorageReaderInterface
     */
    protected $availabilityStorageReader;

    public function __construct(AvailabilityStorageReaderInterface $availabilityStorageReader)
    {
        $this->availabilityStorageReader = $availabilityStorageReader;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     * @param array $productData
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer
     */
    public function expandProductViewTransfer(ProductViewTransfer $productViewTransfer, array $productData, string $localeName)
    {
        $storageAvailabilityTransfer = $this->availabilityStorageReader->getAvailabilityAbstractAsStorageTransfer(
            $productViewTransfer->getIdProductAbstract()
        );

        if (!$productViewTransfer->getIdProductConcrete()) {
            $productViewTransfer->setAvailable($storageAvailabilityTransfer->getIsAbstractProductAvailable());

            return $productViewTransfer;
        }

        $concreteProductAvailableItems = $storageAvailabilityTransfer->getConcreteProductAvailableItems();

        if (isset($concreteProductAvailableItems[$productViewTransfer->getSku()])) {
            $productViewTransfer->setAvailable($concreteProductAvailableItems[$productViewTransfer->getSku()]);
        }

        return $productViewTransfer;
    }
}
