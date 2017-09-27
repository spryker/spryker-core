<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilSynchronization;

use Spryker\Service\Kernel\AbstractServiceFactory;
use Spryker\Service\UtilSynchronization\Model\ArrayFilter;
use Spryker\Service\UtilSynchronization\Model\EventEntity;
use Spryker\Service\UtilSynchronization\Model\KeyFilter;

class UtilSynchronizationServiceFactory extends AbstractServiceFactory
{

    /**
     * @return \Spryker\Service\UtilSynchronization\Model\ArrayFilterInterface
     */
    public function createArrayFilter()
    {
        return new ArrayFilter();
    }

    /**
     * @return \Spryker\Service\UtilSynchronization\Model\KeyFilterInterface
     */
    public function createKeyFilter()
    {
        return new KeyFilter();
    }

    /**
     * @return \Spryker\Service\UtilSynchronization\Model\EventEntityInterface
     */
    public function createEventEntity()
    {
        return new EventEntity();
    }

}
