<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PushNotification\Business\Validator;

use ArrayObject;
use Generated\Shared\Transfer\ErrorCollectionTransfer;
use Spryker\Zed\PushNotification\Business\Expander\ErrorCollectionExpanderInterface;

class PushNotificationSubscriptionValidator implements PushNotificationSubscriptionValidatorInterface
{
    /**
     * @var array<\Spryker\Zed\PushNotification\Business\Validator\Rules\PushNotificationSubscription\PushNotificationSubscriptionValidatorRuleInterface>
     */
    protected array $pushNotificationSubscriptionValidatorRules = [];

    /**
     * @var array<\Spryker\Zed\PushNotificationExtension\Dependency\Plugin\PushNotificationSubscriptionValidatorPluginInterface>
     */
    protected array $pushNotificationSubscriptionValidatorPlugins;

    /**
     * @var \Spryker\Zed\PushNotification\Business\Expander\ErrorCollectionExpanderInterface
     */
    protected ErrorCollectionExpanderInterface $errorCollectionExpander;

    /**
     * @param array<\Spryker\Zed\PushNotification\Business\Validator\Rules\PushNotificationSubscription\PushNotificationSubscriptionValidatorRuleInterface> $pushNotificationSubscriptionValidatorRules
     * @param array<\Spryker\Zed\PushNotificationExtension\Dependency\Plugin\PushNotificationSubscriptionValidatorPluginInterface> $pushNotificationSubscriptionValidatorPlugins
     * @param \Spryker\Zed\PushNotification\Business\Expander\ErrorCollectionExpanderInterface $errorCollectionExpander
     */
    public function __construct(
        array $pushNotificationSubscriptionValidatorRules,
        array $pushNotificationSubscriptionValidatorPlugins,
        ErrorCollectionExpanderInterface $errorCollectionExpander
    ) {
        $this->pushNotificationSubscriptionValidatorRules = $pushNotificationSubscriptionValidatorRules;
        $this->pushNotificationSubscriptionValidatorPlugins = $pushNotificationSubscriptionValidatorPlugins;
        $this->errorCollectionExpander = $errorCollectionExpander;
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
        foreach ($this->pushNotificationSubscriptionValidatorRules as $pushNotificationSubscriptionValidatorRule) {
            $pluginErrorCollectionTransfer = $pushNotificationSubscriptionValidatorRule->validateCollection(
                $pushNotificationSubscriptionTransfers,
            );
            $errorCollectionTransfer = $this->errorCollectionExpander->expandErrorCollection(
                $errorCollectionTransfer,
                $pluginErrorCollectionTransfer,
            );
        }

        return $this->executePushNotificationSubscriptionValidatorPlugins(
            $pushNotificationSubscriptionTransfers,
            $errorCollectionTransfer,
        );
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\PushNotificationSubscriptionTransfer> $pushNotificationSubscriptionTransfers
     * @param \Generated\Shared\Transfer\ErrorCollectionTransfer $errorCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ErrorCollectionTransfer
     */
    protected function executePushNotificationSubscriptionValidatorPlugins(
        ArrayObject $pushNotificationSubscriptionTransfers,
        ErrorCollectionTransfer $errorCollectionTransfer
    ): ErrorCollectionTransfer {
        foreach ($this->pushNotificationSubscriptionValidatorPlugins as $pushNotificationSubscriptionValidatorPlugin) {
            $errorCollectionTransfer = $this->errorCollectionExpander->expandErrorCollection(
                $errorCollectionTransfer,
                $pushNotificationSubscriptionValidatorPlugin->validate($pushNotificationSubscriptionTransfers),
            );
        }

        return $errorCollectionTransfer;
    }
}
