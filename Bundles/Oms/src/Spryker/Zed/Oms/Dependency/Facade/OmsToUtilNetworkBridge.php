<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Dependency\Facade;

class OmsToUtilNetworkBridge implements OmsToUtilNetworkInterface
{

    /**
     * @var \Spryker\Zed\UtilNetwork\Business\UtilNetworkFacadeInterface
     */
    protected $utilNetworkFacade;

    /**
     * @param \Spryker\Zed\UtilNetwork\Business\UtilNetworkFacadeInterface $utilNetworkFacade
     */
    public function __construct($utilNetworkFacade)
    {
        $this->utilNetworkFacade = $utilNetworkFacade;
    }

    /**
     * @return string
     */
    public function getHostName()
    {
        return $this->utilNetworkFacade->getHostName();
    }

}
