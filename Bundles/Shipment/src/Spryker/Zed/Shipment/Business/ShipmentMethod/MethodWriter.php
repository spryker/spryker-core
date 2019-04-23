<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Business\ShipmentMethod;

use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Orm\Zed\Shipment\Persistence\SpyShipmentMethod;
use Spryker\Zed\Shipment\Business\Model\MethodPriceInterface;
use Spryker\Zed\Shipment\Persistence\ShipmentQueryContainerInterface;

class MethodWriter implements MethodWriterInterface
{
    /**
     * @var \Spryker\Zed\Shipment\Persistence\ShipmentQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \Spryker\Zed\Shipment\Business\Model\MethodPriceInterface
     */
    protected $methodPrice;

    /**
     * @param \Spryker\Zed\Shipment\Persistence\ShipmentQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\Shipment\Business\Model\MethodPriceInterface $methodPrice
     */
    public function __construct(
        ShipmentQueryContainerInterface $queryContainer,
        MethodPriceInterface $methodPrice
    ) {
        $this->queryContainer = $queryContainer;
        $this->methodPrice = $methodPrice;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $methodTransfer
     *
     * @return int
     */
    public function create(ShipmentMethodTransfer $methodTransfer): int
    {
        $methodEntity = new SpyShipmentMethod();
        $methodEntity->fromArray($methodTransfer->toArray());
        $methodEntity->save();

        $idShipmentMethod = $methodEntity->getPrimaryKey();
        $methodTransfer->setIdShipmentMethod($idShipmentMethod);
        $this->methodPrice->save($methodTransfer);

        return $idShipmentMethod;
    }

    /**
     * @param int $idShipmentMethod
     *
     * @return bool
     */
    public function delete(int $idShipmentMethod): bool
    {
        $methodQuery = $this->queryContainer->queryMethodByIdMethod($idShipmentMethod);
        $entity = $methodQuery->findOne();

        if ($entity) {
            $entity->delete();
        }

        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $methodTransfer
     *
     * @return int|bool
     */
    public function update(ShipmentMethodTransfer $methodTransfer)
    {
        if ($this->hasMethod($methodTransfer->getIdShipmentMethod())) {
            $methodEntity =
                $this->queryContainer->queryMethodByIdMethod($methodTransfer->getIdShipmentMethod())->findOne();

            $methodEntity->fromArray($methodTransfer->toArray());
            $methodEntity->save();
            $this->methodPrice->save($methodTransfer);

            return $methodEntity->getPrimaryKey();
        }

        return false;
    }

    /**
     * @param int $idShipmentMethod
     *
     * @return bool
     */
    protected function hasMethod(int $idShipmentMethod): bool
    {
        $methodQuery = $this->queryContainer->queryMethodByIdMethod($idShipmentMethod);

        return $methodQuery->count() > 0;
    }
}
