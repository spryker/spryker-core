<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PushNotification\Dependency\Facade;

use Generated\Shared\Transfer\LocaleCriteriaTransfer;

class PushNotificationToLocaleFacadeBridge implements PushNotificationToLocaleFacadeInterface
{
    /**
     * @var \Spryker\Zed\Locale\Business\LocaleFacadeInterface
     */
    protected $localeFacade;

    /**
     * @param \Spryker\Zed\Locale\Business\LocaleFacadeInterface $localeFacade
     */
    public function __construct($localeFacade)
    {
        $this->localeFacade = $localeFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\LocaleCriteriaTransfer|null $localeCriteriaTransfer
     *
     * @return array<string, \Generated\Shared\Transfer\LocaleTransfer>
     */
    public function getLocaleCollection(?LocaleCriteriaTransfer $localeCriteriaTransfer = null): array
    {
        return $this->localeFacade->getLocaleCollection($localeCriteriaTransfer);
    }
}
