<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PushNotification\Business\Validator;

use ArrayObject;
use Generated\Shared\Transfer\ErrorCollectionTransfer;
use Spryker\Zed\PushNotification\Business\Expander\ErrorCollectionExpanderInterface;

class PushNotificationProviderValidator implements PushNotificationProviderValidatorInterface
{
    /**
     * @var array<\Spryker\Zed\PushNotification\Business\Validator\Rules\PushNotificationProvider\PushNotificationProviderValidatorRuleInterface>
     */
    protected array $pushNotificationProviderValidatorRules = [];

    /**
     * @var \Spryker\Zed\PushNotification\Business\Expander\ErrorCollectionExpanderInterface
     */
    protected ErrorCollectionExpanderInterface $errorCollectionExpander;

    /**
     * @param array<\Spryker\Zed\PushNotification\Business\Validator\Rules\PushNotificationProvider\PushNotificationProviderValidatorRuleInterface> $pushNotificationProviderValidatorRules
     * @param \Spryker\Zed\PushNotification\Business\Expander\ErrorCollectionExpanderInterface $errorCollectionExpander
     */
    public function __construct(array $pushNotificationProviderValidatorRules, ErrorCollectionExpanderInterface $errorCollectionExpander)
    {
        $this->pushNotificationProviderValidatorRules = $pushNotificationProviderValidatorRules;
        $this->errorCollectionExpander = $errorCollectionExpander;
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\PushNotificationProviderTransfer> $pushNotificationProviderTransfers
     *
     * @return \Generated\Shared\Transfer\ErrorCollectionTransfer
     */
    public function validateCollection(ArrayObject $pushNotificationProviderTransfers): ErrorCollectionTransfer
    {
        $errorCollectionTransfer = new ErrorCollectionTransfer();
        foreach ($this->pushNotificationProviderValidatorRules as $pushNotificationProviderValidatorRule) {
            $ruleErrorCollectionTransfer = $pushNotificationProviderValidatorRule->validateCollection(
                $pushNotificationProviderTransfers,
            );
            $errorCollectionTransfer = $this->errorCollectionExpander->expandErrorCollection(
                $errorCollectionTransfer,
                $ruleErrorCollectionTransfer,
            );
        }

        return $errorCollectionTransfer;
    }
}
