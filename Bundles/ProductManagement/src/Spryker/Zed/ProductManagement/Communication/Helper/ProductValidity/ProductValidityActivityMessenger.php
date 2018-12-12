<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Helper\ProductValidity;

use DateTime;
use Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToProductInterface;
use Spryker\Zed\ProductManagement\ProductManagementConfig;

class ProductValidityActivityMessenger implements ProductValidityActivityMessengerInterface
{
    public const DEACTIVATION_IN_FUTURE_MESSAGE = 'This product will be deactivated at %s GMT. Check the field "Valid To".';
    public const DEACTIVATION_NOW_MESSAGE = <<<'EOD'
        This product will be deactivated shortly because validity overrules activity.
        Check the field "Valid To", it is set in past, to %s GMT.
EOD;

    public const ACTIVATION_IN_FUTURE_MESSAGE = 'This product will be activated at %s GMT. Check the field "Valid From".';
    public const ACTIVATION_NOW_MESSAGE = <<<'EOD'
        This product will be activated shortly because validity overrules activity.
        Check the field "Valid From", it is set in past, to %s GMT.
EOD;

    /**
     * @var \Spryker\Zed\ProductManagement\ProductManagementConfig
     */
    private $config;

    /**
     * @var \Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToProductInterface
     */
    private $productFacade;

    /**
     * @param \Spryker\Zed\ProductManagement\ProductManagementConfig $config
     * @param \Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToProductInterface $productFacade
     */
    public function __construct(
        ProductManagementConfig $config,
        ProductManagementToProductInterface $productFacade
    ) {
        $this->config = $config;
        $this->productFacade = $productFacade;
    }

    /**
     * @param int $idProductConcrete
     *
     * @return string
     */
    public function getActivationMessage($idProductConcrete): string
    {
        $productTransfer = $this->productFacade
            ->findProductConcreteById($idProductConcrete);

        $validToExists = $productTransfer && $productTransfer->getValidTo();

        if (!$validToExists) {
            return '';
        }

        $validTo = new DateTime($productTransfer->getValidTo());

        if ($validTo > (new DateTime())) {
            return sprintf(
                static::DEACTIVATION_IN_FUTURE_MESSAGE,
                $validTo->format($this->config->getValidityTimeFormat())
            );
        }

        return sprintf(
            static::DEACTIVATION_NOW_MESSAGE,
            $validTo->format($this->config->getValidityTimeFormat())
        );
    }

    /**
     * @param int $idProductConcrete
     *
     * @return string
     */
    public function getDeactivationMessage($idProductConcrete): string
    {
        $productTransfer = $this->productFacade
            ->findProductConcreteById($idProductConcrete);

        $validFromExists = $productTransfer && $productTransfer->getValidFrom();

        if (!$validFromExists) {
            return '';
        }

        $validFrom = new DateTime($productTransfer->getValidFrom());

        if ($validFrom > (new DateTime())) {
            return sprintf(
                static::ACTIVATION_IN_FUTURE_MESSAGE,
                $validFrom->format($this->config->getValidityTimeFormat())
            );
        }

        $validToExists = $productTransfer && $productTransfer->getValidTo();
        $validTo = new DateTime($productTransfer->getValidTo());
        $isValidToInPast = $validToExists && ($validTo < (new DateTime()));

        if ($isValidToInPast) {
            return '';
        }

        return sprintf(
            static::ACTIVATION_NOW_MESSAGE,
            $validFrom->format($this->config->getValidityTimeFormat())
        );
    }
}
