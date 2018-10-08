<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductQuantity;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\ProductQuantity\Dependency\Client\ProductQuantityToGlossaryStorageClientInterface;
use Spryker\Client\ProductQuantity\Dependency\Client\ProductQuantityToLocaleClientInterface;
use Spryker\Client\ProductQuantity\ProductQuantityRestrictions\ProductQuantityRestrictionsValidator;
use Spryker\Client\ProductQuantity\ProductQuantityRestrictions\ProductQuantityRestrictionsValidatorInterface;

class ProductQuantityFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\ProductQuantity\ProductQuantityRestrictions\ProductQuantityRestrictionsValidatorInterface
     */
    public function createProductQuantityRestrictionsValidator(): ProductQuantityRestrictionsValidatorInterface
    {
        return new ProductQuantityRestrictionsValidator(
            $this->getGlossaryStorageClient(),
            $this->getLocaleClient()
        );
    }

    /**
     * @return \Spryker\Client\ProductQuantity\Dependency\Client\ProductQuantityToGlossaryStorageClientInterface
     */
    public function getGlossaryStorageClient(): ProductQuantityToGlossaryStorageClientInterface
    {
        return $this->getProvidedDependency(ProductQuantityDependencyProvider::CLIENT_GLOSSARY_STORAGE);
    }

    /**
     * @return \Spryker\Client\ProductQuantity\Dependency\Client\ProductQuantityToLocaleClientInterface
     */
    public function getLocaleClient(): ProductQuantityToLocaleClientInterface
    {
        return $this->getProvidedDependency(ProductQuantityDependencyProvider::CLIENT_LOCALE);
    }
}
