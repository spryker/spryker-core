<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Currency\Plugin;

use Spryker\Client\Kernel\AbstractPlugin;

/**
 * @deprecated Use \Spryker\Client\Currency\CurrencyClient instead.
 *
 * @method \Spryker\Client\Currency\CurrencyFactory getFactory()
 */
class CurrencyPlugin extends AbstractPlugin implements CurrencyPluginInterface
{
    /**
     * @param string $isoCode
     *
     * @return \Generated\Shared\Transfer\CurrencyTransfer
     */
    public function fromIsoCode($isoCode)
    {
        return $this->getFactory()->createCurrencyBuilder()->fromIsoCode($isoCode);
    }

    /**
     * @return \Generated\Shared\Transfer\CurrencyTransfer
     */
    public function getCurrent()
    {
        return $this->getFactory()->createCurrencyBuilder()->getCurrent();
    }
}
