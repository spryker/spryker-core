<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductQuantity\Business;

use Spryker\Service\ProductQuantity\ProductQuantityServiceInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductQuantity\Business\Model\Checker\CartChangeTransferNormalizerPreChecker;
use Spryker\Zed\ProductQuantity\Business\Model\Checker\CartChangeTransferNormalizerPreCheckerInterface;
use Spryker\Zed\ProductQuantity\Business\Model\Normalizer\CartChangeTransferQuantityNormalizer;
use Spryker\Zed\ProductQuantity\Business\Model\Normalizer\CartChangeTransferQuantityNormalizerInterface;
use Spryker\Zed\ProductQuantity\Business\Model\ProductQuantityReader;
use Spryker\Zed\ProductQuantity\Business\Model\ProductQuantityReaderInterface;
use Spryker\Zed\ProductQuantity\Business\Model\Validator\ProductQuantityRestrictionValidator;
use Spryker\Zed\ProductQuantity\Business\Model\Validator\ProductQuantityRestrictionValidatorInterface;
use Spryker\Zed\ProductQuantity\ProductQuantityDependencyProvider;

/**
 * @method \Spryker\Zed\ProductQuantity\ProductQuantityConfig getConfig()
 * @method \Spryker\Zed\ProductQuantity\Persistence\ProductQuantityRepositoryInterface getRepository()
 * @method \Spryker\Zed\ProductQuantity\Persistence\ProductQuantityEntityManagerInterface getEntityManager()
 */
class ProductQuantityBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ProductQuantity\Business\Model\Validator\ProductQuantityRestrictionValidatorInterface
     */
    public function createProductQuantityRestrictionValidator(): ProductQuantityRestrictionValidatorInterface
    {
        return new ProductQuantityRestrictionValidator($this->createProductQuantityReader());
    }

    /**
     * @return \Spryker\Zed\ProductQuantity\Business\Model\ProductQuantityReaderInterface
     */
    public function createProductQuantityReader(): ProductQuantityReaderInterface
    {
        return new ProductQuantityReader($this->getRepository());
    }

    /**
     * @return \Spryker\Zed\ProductQuantity\Business\Model\Normalizer\CartChangeTransferQuantityNormalizerInterface
     */
    public function createCartChangeTransferQuantityNormalizer(): CartChangeTransferQuantityNormalizerInterface
    {
        return new CartChangeTransferQuantityNormalizer(
            $this->createProductQuantityReader(),
            $this->getProductQuantityService()
        );
    }

    /**
     * @return \Spryker\Zed\ProductQuantity\Business\Model\Checker\CartChangeTransferNormalizerPreCheckerInterface
     */
    public function createCartChangeTransferNormalizerPreChecker(): CartChangeTransferNormalizerPreCheckerInterface
    {
        return new CartChangeTransferNormalizerPreChecker();
    }

    /**
     * @return \Spryker\Service\ProductQuantity\ProductQuantityServiceInterface
     */
    public function getProductQuantityService(): ProductQuantityServiceInterface
    {
        return $this->getProvidedDependency(ProductQuantityDependencyProvider::SERVICE_PRODUCT_QUANTITY);
    }
}
