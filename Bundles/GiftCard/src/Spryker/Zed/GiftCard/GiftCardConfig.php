<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GiftCard;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class GiftCardConfig extends AbstractBundleConfig
{

    /**
     * @return string
     */
    public function getCodePrefix()
    {
        //TODO read it from config
        return 'GC';
    }

    /**
     * @return array
     */
    public function getGiftCardMethodBlacklist()
    {
        return [];
    }

}
