<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartPermissionConnector\Communication\Plugin;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\PermissionExtension\Dependency\Plugin\ExecutablePermissionPluginInterface;
use Spryker\Zed\CartExtension\Dependency\Plugin\CartTerminationPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @deprecated Due to conflict with customer access module.
 *   Use {@link \Spryker\Zed\CartPermissionConnector\Communication\Plugin\Cart\AlterCartUpToAmountPermissionPlugin} instead.
 *
 * For Zed {@link \Spryker\Zed\Permission\PermissionDependencyProvider::getPermissionPlugins()} and
 *   {@link \Spryker\Zed\Cart\CartDependencyProvider::getTerminationPlugins()} registration.
 *
 * @method \Spryker\Zed\CartPermissionConnector\Communication\CartPermissionConnectorCommunicationFactory getFactory()
 */
class AlterCartUpToAmountPermissionPlugin extends AbstractPlugin implements ExecutablePermissionPluginInterface, CartTerminationPluginInterface
{
    /**
     * @var string
     */
    public const KEY = 'AlterCartUpToAmountPermissionPlugin';

    /**
     * @var string
     */
    protected const FIELD_CENT_AMOUNT = 'cent_amount';

    /**
     * @var array<string>
     */
    protected const SUBSCRIBED_TERMINATION_NAMES = [
        'add',
        'reload',
    ];

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $terminationEventName
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $calculatedQuoteTransfer
     *
     * @return bool
     */
    public function isTerminated(string $terminationEventName, CartChangeTransfer $cartChangeTransfer, QuoteTransfer $calculatedQuoteTransfer): bool
    {
        if (!$this->isSubscribedToTerminationEventName($terminationEventName)) {
            return false;
        }

        if (!$this->hasRequiredFields($cartChangeTransfer)) {
            return true;
        }

        return $this->isTerminatedByPermission($cartChangeTransfer, $calculatedQuoteTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array<string, mixed> $configuration
     * @param array|string|int|null $context Cent amount.
     *
     * @return bool
     */
    public function can(array $configuration, $context = null): bool
    {
        if (!$context) {
            return false;
        }

        if (!isset($configuration[static::FIELD_CENT_AMOUNT])) {
            return false;
        }

        if (!is_array($context) && (int)$configuration[static::FIELD_CENT_AMOUNT] <= (int)$context) {
            return false;
        }

        return true;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return array
     */
    public function getConfigurationSignature(): array
    {
        return [
            static::FIELD_CENT_AMOUNT => ExecutablePermissionPluginInterface::CONFIG_FIELD_TYPE_INT,
        ];
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getKey(): string
    {
        return static::KEY;
    }

    /**
     * @param string $terminationEventName
     *
     * @return bool
     */
    protected function isSubscribedToTerminationEventName(string $terminationEventName)
    {
        return in_array($terminationEventName, static::SUBSCRIBED_TERMINATION_NAMES);
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return bool
     */
    protected function hasRequiredFields(CartChangeTransfer $cartChangeTransfer)
    {
        $customerTransfer = $cartChangeTransfer->getQuote()->getCustomer();

        if (!$customerTransfer) {
            return false;
        }

        $companyUserTransfer = $customerTransfer->getCompanyUserTransfer();

        if (!$companyUserTransfer) {
            return false;
        }

        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $companyUserTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function isTerminatedByPermission(CartChangeTransfer $companyUserTransfer, QuoteTransfer $quoteTransfer)
    {
        $identifier = $companyUserTransfer
            ->getQuote()
            ->getCustomer()
            ->getCompanyUserTransfer()
            ->getIdCompanyUser();

        $isAllowed = $this->getFactory()->getPermissionFacade()->can(
            static::KEY,
            $identifier,
            $quoteTransfer->getTotals()->getGrandTotal(),
        );

        if ($isAllowed) {
            return false;
        }

        $this->getFactory()
            ->getMessengerFacade()
            ->addErrorMessage(
                (new MessageTransfer())
                    ->setValue('global.permission.failed'),
            );

        return true;
    }
}
