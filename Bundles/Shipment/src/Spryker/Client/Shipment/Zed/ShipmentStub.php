<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Client\Shipment\Zed;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentMethodsTransfer;
use Spryker\Client\ZedRequest\ZedRequestClient;

class ShipmentStub implements ShipmentStubInterface
{

    /**
     * @var ZedRequestClient
     */
    protected $zedStub;

    /**
     * @param ZedRequestClient $zedStub
     */
    public function __construct(ZedRequestClient $zedStub)
    {
        $this->zedStub = $zedStub;
    }

    /**
     * @param QuoteTransfer $quoteTransfer
     *
     * @return ShipmentMethodsTransfer
     */
    public function getAvailableMethods(QuoteTransfer $quoteTransfer)
    {
        return $this->zedStub->call('/shipment/gateway/get-available-methods', $quoteTransfer, null, true);
    }

}
