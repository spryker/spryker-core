<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Dependency\Facade;

class OmsToUtilTextBridge implements OmsToUtilTextInterface
{

    /**
     * @var \Spryker\Zed\UtilText\Business\UtilTextFacadeInterface
     */
    protected $utilTextFacade;

    /**
     * @param \Spryker\Zed\UtilText\Business\UtilTextFacadeInterface $utilTextFacade
     */
    public function __construct($utilTextFacade)
    {
        $this->utilTextFacade = $utilTextFacade;
    }

    /**
     * @param int $length
     *
     * @return string
     */
    public function generateRandomString($length = 31)
    {
        return $this->utilTextFacade->generateRandomString($length);
    }

}
