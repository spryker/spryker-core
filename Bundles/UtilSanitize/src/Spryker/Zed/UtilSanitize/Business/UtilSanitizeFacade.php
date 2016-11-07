<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\UtilSanitize\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\UtilSanitize\Business\UtilSanitizeBusinessFactory getFactory()
 */
class UtilSanitizeFacade extends AbstractFacade implements UtilSanitizeFacadeInterface
{

    /**
     *
     * Specification:
     *  - Escape html
     *
     * @api
     *
     * @param string $text
     * @param bool $double
     * @param null $charset
     *
     * @return string
     */
    public function escapeHtml($text, $double = true, $charset = null)
    {
        return $this->getFactory()
           ->createHtml()
           ->escape($text, $double, $charset);
    }

}
