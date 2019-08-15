<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CmsSlot;

use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\CmsSlot\CmsSlotFactory getFactory()
 */
class CmsSlotClient extends AbstractClient implements CmsSlotClientInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string[] $fillingKeys
     *
     * @return array
     */
    public function fetchCmsSlotAutoFilled(array $fillingKeys): array
    {
        return $this->getFactory()->createCmsSlotAutoFiller()->fetchCmsSlotAutoFilled($fillingKeys);
    }
}
