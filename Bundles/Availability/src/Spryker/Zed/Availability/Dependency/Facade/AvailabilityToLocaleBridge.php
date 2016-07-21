<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Availability\Dependency\Facade;

use Spryker\Zed\Locale\Business\LocaleFacadeInterface;

class AvailabilityToLocaleBridge implements AvailabilityToLocaleInterface
{

    /**
     * @var LocaleFacadeInterface
     */
    protected $localeFacade;

    /**
     * @param LocaleFacadeInterface $localeFacade
     */
    public function __construct($localeFacade)
    {
        $this->localeFacade = $localeFacade;
    }

    /**
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    public function getCurrentLocale()
    {
        return $this->localeFacade->getCurrentLocale();
    }

}
