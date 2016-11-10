<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Dependency\Facade;

class OmsToUtilSanitizeBridge implements OmsToUtilSanitizeInterface
{

    /**
     * @var \Spryker\Zed\UtilSanitize\Business\UtilSanitizeFacadeInterface
     */
    protected $utilSanitizeFacade;

    /**
     * @param \Spryker\Zed\UtilSanitize\Business\UtilSanitizeFacadeInterface $utilSanitizeFacade
     */
    public function __construct($utilSanitizeFacade)
    {
        $this->utilSanitizeFacade = $utilSanitizeFacade;
    }

    /**
     * @param string $text
     * @param bool $double
     *
     * @return string
     */
    public function escapeHtml($text, $double = true)
    {
        return $this->utilSanitizeFacade->escapeHtml($text, $double);
    }

}
