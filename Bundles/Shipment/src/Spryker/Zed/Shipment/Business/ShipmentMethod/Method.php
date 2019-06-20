<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Business\ShipmentMethod;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentGroupCollectionTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Spryker\Zed\Shipment\Business\Exception\EntityNotFoundException;

class Method implements MethodInterface
{
    /**
     * @var \Spryker\Zed\Shipment\Business\ShipmentMethod\MethodWriterInterface
     */
    protected $methodWriter;

    /**
     * @var \Spryker\Zed\Shipment\Business\ShipmentMethod\MethodReaderInterface
     */
    protected $methodReader;

    /**
     * @param \Spryker\Zed\Shipment\Business\ShipmentMethod\MethodWriterInterface $methodWriter
     * @param \Spryker\Zed\Shipment\Business\ShipmentMethod\MethodReaderInterface $methodReader
     */
    public function __construct(MethodWriterInterface $methodWriter, MethodReaderInterface $methodReader)
    {
        $this->methodWriter = $methodWriter;
        $this->methodReader = $methodReader;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentGroupCollectionTransfer
     */
    public function getAvailableMethodsByShipment(QuoteTransfer $quoteTransfer): ShipmentGroupCollectionTransfer
    {
        if (!$this->methodReader->isMultiShipmentQuote($quoteTransfer)) {
            return $this->methodReader->getAvailableMethodsByShipmentWithoutMultiShipment($quoteTransfer);
        }

        $shipmentGroupCollectionTransfer = $this->methodReader->getShipmentGroupWithAvailableMethods($quoteTransfer);
        $shipmentGroupCollectionTransfer = $this->methodReader->applyFiltersByShipment($shipmentGroupCollectionTransfer, $quoteTransfer);

        return $shipmentGroupCollectionTransfer;
    }

    /**
     * @param int $idMethod
     *
     * @return bool
     */
    public function hasMethod($idMethod)
    {
        return $this->methodReader->hasMethod($idMethod);
    }

    /**
     * @param int $idMethod
     *
     * @throws \Pyz\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodTransfer
     */
    public function getShipmentMethodTransferById($idMethod)
    {
        $shipmentMethodTransfer = $this->methodReader->findShipmentMethodById($idMethod);

        if ($shipmentMethodTransfer === null) {
            throw new EntityNotFoundException(sprintf('Shipment method by id "%d" not found.', $idMethod));
        }

        return $shipmentMethodTransfer;
    }

    /**
     * @param int $idShipmentMethod
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodTransfer|null
     */
    public function findShipmentMethodTransferById($idShipmentMethod)
    {
        return $this->methodReader->findShipmentMethodById($idShipmentMethod);
    }

    /**
     * @return \Generated\Shared\Transfer\ShipmentMethodTransfer[]
     */
    public function getShipmentMethodTransfers()
    {
        return $this->methodReader->getActiveShipmentMethods();
    }

    /**
     * @deprecated Use getAvailableMethodsByShipment() instead.
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodsTransfer
     */
    public function getAvailableMethods(QuoteTransfer $quoteTransfer)
    {
        return $this->methodReader->getAvailableMethods($quoteTransfer);
    }

    /**
     * @param int $idMethod
     *
     * @return bool
     */
    public function deleteMethod($idMethod)
    {
        return $this->methodWriter->delete($idMethod);
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $methodTransfer
     *
     * @return int|bool
     */
    public function updateMethod(ShipmentMethodTransfer $methodTransfer)
    {
        $idShipmentMethod = $methodTransfer->getIdShipmentMethod();
        if ($idShipmentMethod === null || !$this->methodReader->hasMethod($idShipmentMethod)) {
            return false;
        }

        return $this->methodWriter->update($methodTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $methodTransfer
     *
     * @return int
     */
    public function create(ShipmentMethodTransfer $methodTransfer)
    {
        return $this->methodWriter->create($methodTransfer);
    }

    /**
     * @param int $idShipmentMethod
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentMethodTransfer|null
     */
    public function findAvailableMethodById($idShipmentMethod, QuoteTransfer $quoteTransfer)
    {
        return $this->methodReader->findAvailableMethodById($idShipmentMethod, $quoteTransfer);
    }

    /**
     * @param int $idShipmentMethod
     *
     * @return bool
     */
    public function isShipmentMethodActive($idShipmentMethod)
    {
        return $this->methodReader->hasMethod($idShipmentMethod);
    }
}
