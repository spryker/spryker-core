<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PushNotification\Business\Validator;

use ArrayObject;
use Generated\Shared\Transfer\ErrorCollectionTransfer;
use Spryker\Zed\PushNotification\Business\Expander\ErrorCollectionExpanderInterface;

class PushNotificationValidator implements PushNotificationValidatorInterface
{
    /**
     * @var array<\Spryker\Zed\PushNotification\Business\Validator\Rules\PushNotification\PushNotificationValidatorRuleInterface>
     */
    protected array $pushNotificationValidatorRules;

    /**
     * @var array<\Spryker\Zed\PushNotificationExtension\Dependency\Plugin\PushNotificationValidatorPluginInterface>
     */
    protected array $pushNotificationValidatorPlugins;

    /**
     * @var \Spryker\Zed\PushNotification\Business\Expander\ErrorCollectionExpanderInterface
     */
    protected ErrorCollectionExpanderInterface $errorCollectionExpander;

    /**
     * @param array<\Spryker\Zed\PushNotification\Business\Validator\Rules\PushNotification\PushNotificationValidatorRuleInterface> $pushNotificationValidatorRules
     * @param array<\Spryker\Zed\PushNotificationExtension\Dependency\Plugin\PushNotificationValidatorPluginInterface> $pushNotificationValidatorPlugins
     * @param \Spryker\Zed\PushNotification\Business\Expander\ErrorCollectionExpanderInterface $errorCollectionExpander
     */
    public function __construct(
        array $pushNotificationValidatorRules,
        array $pushNotificationValidatorPlugins,
        ErrorCollectionExpanderInterface $errorCollectionExpander
    ) {
        $this->pushNotificationValidatorRules = $pushNotificationValidatorRules;
        $this->pushNotificationValidatorPlugins = $pushNotificationValidatorPlugins;
        $this->errorCollectionExpander = $errorCollectionExpander;
    }

    /**
     * @param \ArrayObject<\Generated\Shared\Transfer\PushNotificationTransfer> $pushNotificationTransfers
     *
     * @return \Generated\Shared\Transfer\ErrorCollectionTransfer
     */
    public function validateCollection(
        ArrayObject $pushNotificationTransfers
    ): ErrorCollectionTransfer {
        $errorCollectionTransfer = new ErrorCollectionTransfer();
        foreach ($this->pushNotificationValidatorRules as $notificationValidatorRule) {
            $ruleErrorCollectionTransfer = $notificationValidatorRule->validateCollection($pushNotificationTransfers);
            $errorCollectionTransfer = $this->errorCollectionExpander->expandErrorCollection(
                $errorCollectionTransfer,
                $ruleErrorCollectionTransfer,
            );
        }

        return $this->executePushNotificationValidatorPlugins(
            $pushNotificationTransfers,
            $errorCollectionTransfer,
        );
    }

    /**
     * @param \ArrayObject<\Generated\Shared\Transfer\PushNotificationTransfer> $pushNotificationTransfers
     * @param \Generated\Shared\Transfer\ErrorCollectionTransfer $errorCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ErrorCollectionTransfer
     */
    protected function executePushNotificationValidatorPlugins(
        ArrayObject $pushNotificationTransfers,
        ErrorCollectionTransfer $errorCollectionTransfer
    ): ErrorCollectionTransfer {
        foreach ($this->pushNotificationValidatorPlugins as $pushNotificationValidatorPlugin) {
            $pluginErrorCollectionTransfer = $pushNotificationValidatorPlugin->validate($pushNotificationTransfers);
            $errorCollectionTransfer = $this->errorCollectionExpander->expandErrorCollection(
                $errorCollectionTransfer,
                $pluginErrorCollectionTransfer,
            );
        }

        return $errorCollectionTransfer;
    }
}
