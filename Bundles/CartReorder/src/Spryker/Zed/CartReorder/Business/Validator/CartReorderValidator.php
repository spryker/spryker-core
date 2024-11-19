<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartReorder\Business\Validator;

use Generated\Shared\Transfer\CartReorderRequestTransfer;
use Generated\Shared\Transfer\CartReorderResponseTransfer;
use Generated\Shared\Transfer\CartReorderTransfer;

class CartReorderValidator implements CartReorderValidatorInterface
{
    /**
     * @var list<\Spryker\Zed\CartReorderExtension\Dependency\Plugin\CartReorderRequestValidatorPluginInterface>
     */
    protected array $cartReorderRequestValidatorPlugins;

    /**
     * @var list<\Spryker\Zed\CartReorderExtension\Dependency\Plugin\CartReorderValidatorPluginInterface>
     */
    protected array $cartReorderValidatorPlugins;

    /**
     * @param list<\Spryker\Zed\CartReorderExtension\Dependency\Plugin\CartReorderRequestValidatorPluginInterface> $cartReorderRequestValidatorPlugins
     * @param list<\Spryker\Zed\CartReorderExtension\Dependency\Plugin\CartReorderValidatorPluginInterface> $cartReorderValidatorPlugins
     */
    public function __construct(array $cartReorderRequestValidatorPlugins, array $cartReorderValidatorPlugins)
    {
        $this->cartReorderRequestValidatorPlugins = $cartReorderRequestValidatorPlugins;
        $this->cartReorderValidatorPlugins = $cartReorderValidatorPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\CartReorderRequestTransfer $cartReorderRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CartReorderResponseTransfer
     */
    public function validateRequest(CartReorderRequestTransfer $cartReorderRequestTransfer): CartReorderResponseTransfer
    {
        return $this->executeCartReorderRequestValidatorPlugins(
            $cartReorderRequestTransfer,
            new CartReorderResponseTransfer(),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\CartReorderTransfer $cartReorderTransfer
     *
     * @return \Generated\Shared\Transfer\CartReorderResponseTransfer
     */
    public function validate(CartReorderTransfer $cartReorderTransfer): CartReorderResponseTransfer
    {
        return $this->executeCartReorderValidatorPlugins($cartReorderTransfer, new CartReorderResponseTransfer());
    }

    /**
     * @param \Generated\Shared\Transfer\CartReorderRequestTransfer $cartReorderRequestTransfer
     * @param \Generated\Shared\Transfer\CartReorderResponseTransfer $cartReorderResponseTransfer
     *
     * @return \Generated\Shared\Transfer\CartReorderResponseTransfer
     */
    protected function executeCartReorderRequestValidatorPlugins(
        CartReorderRequestTransfer $cartReorderRequestTransfer,
        CartReorderResponseTransfer $cartReorderResponseTransfer
    ): CartReorderResponseTransfer {
        foreach ($this->cartReorderRequestValidatorPlugins as $cartReorderRequestValidatorPlugin) {
            $cartReorderResponseTransfer = $cartReorderRequestValidatorPlugin->validate(
                $cartReorderRequestTransfer,
                $cartReorderResponseTransfer,
            );
        }

        return $cartReorderResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CartReorderTransfer $cartReorderTransfer
     * @param \Generated\Shared\Transfer\CartReorderResponseTransfer $cartReorderResponseTransfer
     *
     * @return \Generated\Shared\Transfer\CartReorderResponseTransfer
     */
    protected function executeCartReorderValidatorPlugins(
        CartReorderTransfer $cartReorderTransfer,
        CartReorderResponseTransfer $cartReorderResponseTransfer
    ): CartReorderResponseTransfer {
        foreach ($this->cartReorderValidatorPlugins as $cartReorderValidatorPlugin) {
            $cartReorderResponseTransfer = $cartReorderValidatorPlugin->validate($cartReorderTransfer, $cartReorderResponseTransfer);
        }

        return $cartReorderResponseTransfer;
    }
}
