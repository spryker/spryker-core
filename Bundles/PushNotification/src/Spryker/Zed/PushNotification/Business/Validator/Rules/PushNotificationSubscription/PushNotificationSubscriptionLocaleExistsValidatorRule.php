<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PushNotification\Business\Validator\Rules\PushNotificationSubscription;

use ArrayObject;
use Generated\Shared\Transfer\ErrorCollectionTransfer;
use Generated\Shared\Transfer\LocaleConditionsTransfer;
use Generated\Shared\Transfer\LocaleCriteriaTransfer;
use Spryker\Zed\PushNotification\Business\Creator\ErrorCreatorInterface;
use Spryker\Zed\PushNotification\Business\Extractor\PushNotificationSubscriptionLocaleExtractorInterface;
use Spryker\Zed\PushNotification\Dependency\Facade\PushNotificationToLocaleFacadeInterface;

class PushNotificationSubscriptionLocaleExistsValidatorRule implements PushNotificationSubscriptionValidatorRuleInterface
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_LOCALE_NOT_FOUND = 'push_notification.validation.error.locale_not_found';

    /**
     * @var \Spryker\Zed\PushNotification\Dependency\Facade\PushNotificationToLocaleFacadeInterface
     */
    protected PushNotificationToLocaleFacadeInterface $localeFacade;

    /**
     * @var \Spryker\Zed\PushNotification\Business\Extractor\PushNotificationSubscriptionLocaleExtractorInterface
     */
    protected PushNotificationSubscriptionLocaleExtractorInterface $pushNotificationSubscriptionLocaleExtractor;

    /**
     * @var \Spryker\Zed\PushNotification\Business\Creator\ErrorCreatorInterface
     */
    protected ErrorCreatorInterface $errorCreator;

    /**
     * @param \Spryker\Zed\PushNotification\Dependency\Facade\PushNotificationToLocaleFacadeInterface $localeFacade
     * @param \Spryker\Zed\PushNotification\Business\Extractor\PushNotificationSubscriptionLocaleExtractorInterface $pushNotificationSubscriptionLocaleExtractor
     * @param \Spryker\Zed\PushNotification\Business\Creator\ErrorCreatorInterface $errorCreator
     */
    public function __construct(
        PushNotificationToLocaleFacadeInterface $localeFacade,
        PushNotificationSubscriptionLocaleExtractorInterface $pushNotificationSubscriptionLocaleExtractor,
        ErrorCreatorInterface $errorCreator
    ) {
        $this->localeFacade = $localeFacade;
        $this->errorCreator = $errorCreator;
        $this->pushNotificationSubscriptionLocaleExtractor = $pushNotificationSubscriptionLocaleExtractor;
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\PushNotificationSubscriptionTransfer> $pushNotificationSubscriptionTransfers
     *
     * @return \Generated\Shared\Transfer\ErrorCollectionTransfer
     */
    public function validateCollection(
        ArrayObject $pushNotificationSubscriptionTransfers
    ): ErrorCollectionTransfer {
        $errorCollectionTransfer = new ErrorCollectionTransfer();

        $localesIndexedByLocaleName =
            $this->pushNotificationSubscriptionLocaleExtractor
                ->extractLocaleTransfersIndexedByLocaleName($pushNotificationSubscriptionTransfers);
        if (!$localesIndexedByLocaleName) {
            return $errorCollectionTransfer;
        }
        $localeCriteriaTransfer = (new LocaleCriteriaTransfer())->setLocaleConditions(
            (new LocaleConditionsTransfer())->setLocaleNames(array_keys($localesIndexedByLocaleName)),
        );
        $localeTransfers = $this->localeFacade->getLocaleCollection($localeCriteriaTransfer);
        foreach ($pushNotificationSubscriptionTransfers as $i => $pushNotificationSubscriptionTransfer) {
            $locale = $pushNotificationSubscriptionTransfer->getLocale();
            if (!$locale || array_key_exists($locale->getLocaleNameOrFail(), $localeTransfers)) {
                continue;
            }

            $errorTransfer = $this->errorCreator->createErrorTransfer(
                (string)$i,
                static::GLOSSARY_KEY_VALIDATION_LOCALE_NOT_FOUND,
            );
            $errorCollectionTransfer->addError($errorTransfer);
        }

        return $errorCollectionTransfer;
    }
}
