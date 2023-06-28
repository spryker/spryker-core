<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PushNotification\Business\Validator\Rules\PushNotificationProvider;

use ArrayObject;
use Generated\Shared\Transfer\ErrorCollectionTransfer;
use Generated\Shared\Transfer\PushNotificationProviderCollectionTransfer;
use Generated\Shared\Transfer\PushNotificationProviderConditionsTransfer;
use Generated\Shared\Transfer\PushNotificationProviderCriteriaTransfer;
use Spryker\Zed\PushNotification\Business\Validator\Rules\TerminationAwareValidatorRuleInterface;
use Spryker\Zed\PushNotification\Business\Validator\Util\ErrorAdderInterface;
use Spryker\Zed\PushNotification\Persistence\PushNotificationRepositoryInterface;

class UuidExistencePushNotificationProviderValidatorRule implements PushNotificationProviderValidatorRuleInterface, TerminationAwareValidatorRuleInterface
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_PUSH_NOTIFICATION_PROVIDER_NOT_FOUND = 'push_notification.validation.error.push_notification_provider_not_found';

    /**
     * @var \Spryker\Zed\PushNotification\Persistence\PushNotificationRepositoryInterface
     */
    protected PushNotificationRepositoryInterface $pushNotificationRepository;

    /**
     * @var \Spryker\Zed\PushNotification\Business\Validator\Util\ErrorAdderInterface
     */
    protected ErrorAdderInterface $errorAdder;

    /**
     * @param \Spryker\Zed\PushNotification\Persistence\PushNotificationRepositoryInterface $pushNotificationRepository
     * @param \Spryker\Zed\PushNotification\Business\Validator\Util\ErrorAdderInterface $errorAdder
     */
    public function __construct(
        PushNotificationRepositoryInterface $pushNotificationRepository,
        ErrorAdderInterface $errorAdder
    ) {
        $this->pushNotificationRepository = $pushNotificationRepository;
        $this->errorAdder = $errorAdder;
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\PushNotificationProviderTransfer> $pushNotificationProviderTransfers
     *
     * @return \Generated\Shared\Transfer\ErrorCollectionTransfer
     */
    public function validate(ArrayObject $pushNotificationProviderTransfers): ErrorCollectionTransfer
    {
        $errorCollectionTransfer = new ErrorCollectionTransfer();
        $pushNotificationProviderUuids = $this->extractPushNotificationProviderUuids($pushNotificationProviderTransfers);
        $existingPushNotificationProvidersIndexedByUuid = $this->getExistingPushNotificationProvidersIndexedByUuid($pushNotificationProviderUuids);

        if ($pushNotificationProviderTransfers->count() === count($existingPushNotificationProvidersIndexedByUuid)) {
            return $errorCollectionTransfer;
        }

        foreach ($pushNotificationProviderTransfers as $entityIdentifier => $pushNotificationProviderTransfer) {
            if (!isset($existingPushNotificationProvidersIndexedByUuid[$pushNotificationProviderTransfer->getUuidOrFail()])) {
                $this->errorAdder->addError(
                    $errorCollectionTransfer,
                    $entityIdentifier,
                    static::GLOSSARY_KEY_VALIDATION_PUSH_NOTIFICATION_PROVIDER_NOT_FOUND,
                );
            }
        }

        return $errorCollectionTransfer;
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ErrorTransfer> $initialErrorTransfers
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ErrorTransfer> $postValidationErrorTransfers
     *
     * @return bool
     */
    public function isTerminated(
        ArrayObject $initialErrorTransfers,
        ArrayObject $postValidationErrorTransfers
    ): bool {
        return $postValidationErrorTransfers->count() > $initialErrorTransfers->count();
    }

    /**
     * @param list<string> $pushNotificationProviderUuids
     *
     * @return array<string, \Generated\Shared\Transfer\PushNotificationProviderTransfer>
     */
    protected function getExistingPushNotificationProvidersIndexedByUuid(array $pushNotificationProviderUuids): array
    {
        $pushNotificationProviderCollectionTransfer = $this->getPushNotificationProviderCollection($pushNotificationProviderUuids);
        $pushNotificationProvidersIndexedByUuid = [];

        foreach ($pushNotificationProviderCollectionTransfer->getPushNotificationProviders() as $pushNotificationProviderTransfer) {
            $pushNotificationProvidersIndexedByUuid[$pushNotificationProviderTransfer->getUuidOrFail()] = $pushNotificationProviderTransfer;
        }

        return $pushNotificationProvidersIndexedByUuid;
    }

    /**
     * @param list<string> $pushNotificationProviderUuids
     *
     * @return \Generated\Shared\Transfer\PushNotificationProviderCollectionTransfer
     */
    protected function getPushNotificationProviderCollection(array $pushNotificationProviderUuids): PushNotificationProviderCollectionTransfer
    {
        $pushNotificationProviderConditionsTransfer = (new PushNotificationProviderConditionsTransfer())
            ->setUuids($pushNotificationProviderUuids);

        $pushNotificationProviderCriteriaTransfer = (new PushNotificationProviderCriteriaTransfer())
            ->setPushNotificationProviderConditions($pushNotificationProviderConditionsTransfer);

        return $this->pushNotificationRepository->getPushNotificationProviderCollection($pushNotificationProviderCriteriaTransfer);
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\PushNotificationProviderTransfer> $pushNotificationProviderTransfers
     *
     * @return list<string>
     */
    protected function extractPushNotificationProviderUuids(ArrayObject $pushNotificationProviderTransfers): array
    {
        $pushNotificationProviderUuids = [];

        foreach ($pushNotificationProviderTransfers as $pushNotificationProviderTransfer) {
            $pushNotificationProviderUuids[] = $pushNotificationProviderTransfer->getUuidOrFail();
        }

        return $pushNotificationProviderUuids;
    }
}
