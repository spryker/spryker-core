<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Payone\Dependency\Facade;

use Spryker\Zed\Oms\Business\OmsFacade;
use Propel\Runtime\Collection\ObjectCollection;

class PayoneToOmsBridge implements PayoneToOmsInterface
{

    /**
     * @var OmsFacade
     */
    protected $omsFacade;

    /**
     * @param OmsFacade $omsFacade
     */
    public function __construct($omsFacade)
    {
        $this->omsFacade = $omsFacade;
    }

    /**
     * @param string $eventId
     * @param ObjectCollection $orderItems
     * @param array $logContext
     * @param array $data
     *
     * @return array
     */
    public function triggerEvent($eventId, ObjectCollection $orderItems, array $logContext, array $data = [])
    {
        return $this->omsFacade->triggerEvent($eventId, $orderItems, $logContext, $data);
    }

}
