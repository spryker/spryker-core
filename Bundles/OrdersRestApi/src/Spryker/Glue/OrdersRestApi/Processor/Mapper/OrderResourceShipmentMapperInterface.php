<?php
/**
 * Created by PhpStorm.
 * User: ostrizhnii
 * Date: 9/4/19
 * Time: 11:14 AM
 */

namespace Spryker\Glue\OrdersRestApi\Processor\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\OrderTransfer;

interface OrderResourceShipmentMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\RestOrderShipmentTransfer[]
     */
    public function mapShipmentMethodTransfersToRestOrderShipmentTransfers(OrderTransfer $orderTransfer): ArrayObject;
}
