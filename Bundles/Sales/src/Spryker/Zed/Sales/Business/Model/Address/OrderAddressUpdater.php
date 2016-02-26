<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Sales\Business\Model\Order;

use Generated\Shared\Transfer\AddressTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrderAddress;
use Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface;

class OrderAddressUpdater implements OrderAddressUpdaterInterface
{
    /**
     * @var \Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface SalesQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @param \Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface $queryContainer
     */
    public function __construct(SalesQueryContainerInterface $queryContainer)
    {
        $this->queryContainer = $queryContainer;
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     * @param int $idAddress
     *
     * @return boolean
     */
    public function update(AddressTransfer $addressTransfer, $idAddress)
    {
        $addressEntity = $this->queryContainer
            ->querySalesOrderAddressById($idAddress)
            ->findOne();

        if (empty($addressEntity)) {
            return false;
        }

        $this->hydrateAddressTransferFromEntity($addressTransfer, $addressEntity);

        $addressEntity->save();

        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderAddress $addressEntity
     *
     * @return void
     */
    protected function hydrateAddressTransferFromEntity(
        AddressTransfer $addressTransfer,
        SpySalesOrderAddress $addressEntity
    ) {
        $addressTransfer->fromArray($addressEntity->toArray(), true);
    }
}
