<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Helper\ProductValidity;

use DateTime;
use Spryker\Zed\ProductManagement\Dependency\Facade\ProductManagementToProductInterface;
use Spryker\Zed\ProductManagement\ProductManagementConfig;

class ProductValidityActivityChecker implements ProductValidityActivityCheckerInterface
{
    const DEACTIVATION_IN_FUTURE_MESSAGE = 'This product will be deactivated at %s GMT. Check the field "Valid To".';
    const DEACTIVATION_NOW_MESSAGE = <<<'EOD'
        This product will be deactivated shortly because validity overrules activity.
        Check the field "Valid To", it is set in past, to %s.
EOD;

    const ACTIVATION_IN_FUTURE_MESSAGE = 'This product will be activated at %s GMT. Check the field "Valid From".';
    const ACTIVATION_NOW_MESSAGE = <<<'EOD'
        This product will be activated shortly because validity overrules activity.
        Check the field "Valid From", it is set in past, to %s.
EOD;

    /**
     * @var \Spryker\Zed\ProductManagement\ProductManagementConfig
     */
    private $config;

    /**
     * @var \Spryker\Zed\Product\Business\ProductFacadeInterface
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

        $validityToExists = $productTransfer && $productTransfer->getValidTo();

        if (!$validityToExists) {
            return '';
        }

        $validityTo = new DateTime($productTransfer->getValidTo());

        if ($validityTo > (new DateTime())) {
            return sprintf(
                self::DEACTIVATION_IN_FUTURE_MESSAGE,
                $validityTo->format($this->config->getValidityTimeFormat())
            );
        }

        return sprintf(
            self::DEACTIVATION_NOW_MESSAGE,
            $validityTo->format($this->config->getValidityTimeFormat())
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

        $validityFromExists = $productTransfer && $productTransfer->getValidFrom();

        if (!$validityFromExists) {
            return '';
        }

        $validityFrom = new DateTime($productTransfer->getValidFrom());

        if ($validityFrom > (new DateTime())) {
            return sprintf(
                self::ACTIVATION_IN_FUTURE_MESSAGE,
                $validityFrom->format($this->config->getValidityTimeFormat())
            );
        }

        $validityToExists = $productTransfer && $productTransfer->getValidTo();
        $validityTo = new DateTime($productTransfer->getValidTo());
        $isValidityToInPast = $validityToExists && ($validityTo < (new DateTime()));

        if ($isValidityToInPast) {
            return '';
        }

        return sprintf(
            self::ACTIVATION_NOW_MESSAGE,
            $validityFrom->format($this->config->getValidityTimeFormat())
        );
    }
}
