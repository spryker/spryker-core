<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PushNotification\Business\Expander;

use ArrayObject;
use Generated\Shared\Transfer\LocaleConditionsTransfer;
use Generated\Shared\Transfer\LocaleCriteriaTransfer;
use Spryker\Zed\PushNotification\Business\Extractor\PushNotificationSubscriptionLocaleExtractorInterface;
use Spryker\Zed\PushNotification\Dependency\Facade\PushNotificationToLocaleFacadeInterface;

class PushNotificationSubscriptionLocaleExpander implements PushNotificationSubscriptionExpanderInterface
{
    /**
     * @var \Spryker\Zed\PushNotification\Dependency\Facade\PushNotificationToLocaleFacadeInterface
     */
    protected PushNotificationToLocaleFacadeInterface $localeFacade;

    /**
     * @var \Spryker\Zed\PushNotification\Business\Extractor\PushNotificationSubscriptionLocaleExtractorInterface
     */
    protected PushNotificationSubscriptionLocaleExtractorInterface $pushNotificationSubscriptionLocaleExtractor;

    /**
     * @param \Spryker\Zed\PushNotification\Dependency\Facade\PushNotificationToLocaleFacadeInterface $localeFacade
     * @param \Spryker\Zed\PushNotification\Business\Extractor\PushNotificationSubscriptionLocaleExtractorInterface $pushNotificationSubscriptionLocaleExtractor
     */
    public function __construct(
        PushNotificationToLocaleFacadeInterface $localeFacade,
        PushNotificationSubscriptionLocaleExtractorInterface $pushNotificationSubscriptionLocaleExtractor
    ) {
        $this->localeFacade = $localeFacade;
        $this->pushNotificationSubscriptionLocaleExtractor = $pushNotificationSubscriptionLocaleExtractor;
    }

    /**
     * @param \ArrayObject<int,\Generated\Shared\Transfer\PushNotificationSubscriptionTransfer> $pushNotificationSubscriptionTransfers
     *
     * @return \ArrayObject<int,\Generated\Shared\Transfer\PushNotificationSubscriptionTransfer>
     */
    public function expand(ArrayObject $pushNotificationSubscriptionTransfers): ArrayObject
    {
        $localesIndexedByLocaleName = $this->getLocaleTransfersIndexedByLocaleName($pushNotificationSubscriptionTransfers);
        foreach ($pushNotificationSubscriptionTransfers as $pushNotificationSubscriptionTransfer) {
            if (!$pushNotificationSubscriptionTransfer->getLocale()) {
                continue;
            }

            $localeName = $pushNotificationSubscriptionTransfer->getLocaleOrFail()->getLocaleNameOrFail();
            $localeTransfer = $localesIndexedByLocaleName[$localeName];
            $pushNotificationSubscriptionTransfer->getLocaleOrFail()->setIdLocale($localeTransfer->getIdLocale());
        }

        return $pushNotificationSubscriptionTransfers;
    }

    /**
     * @param \ArrayObject<int,\Generated\Shared\Transfer\PushNotificationSubscriptionTransfer> $pushNotificationSubscriptionTransfers
     *
     * @return array<string, \Generated\Shared\Transfer\LocaleTransfer>
     */
    protected function getLocaleTransfersIndexedByLocaleName(ArrayObject $pushNotificationSubscriptionTransfers): array
    {
        $localeTransfersIndexedByLocaleName = $this->pushNotificationSubscriptionLocaleExtractor
            ->extractLocaleTransfersIndexedByLocaleName($pushNotificationSubscriptionTransfers);

        $localeCriteriaTransfer = (new LocaleCriteriaTransfer())
            ->setLocaleConditions(
                (new LocaleConditionsTransfer())->setLocaleNames(array_keys($localeTransfersIndexedByLocaleName)),
            );

        return $this->localeFacade->getLocaleCollection($localeCriteriaTransfer);
    }
}
