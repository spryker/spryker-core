<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductQuantity\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ProductQuantity\Business\Model\Normalizer\ProductQuantityRestrictionNormalizer;
use Spryker\Zed\ProductQuantity\Business\Model\Normalizer\ProductQuantityRestrictionNormalizerInterface;
use Spryker\Zed\ProductQuantity\Business\Model\ProductQuantityReader;
use Spryker\Zed\ProductQuantity\Business\Model\ProductQuantityReaderInterface;
use Spryker\Zed\ProductQuantity\Business\Model\Rounder\ProductQuantityRounder;
use Spryker\Zed\ProductQuantity\Business\Model\Rounder\ProductQuantityRounderInterface;
use Spryker\Zed\ProductQuantity\Business\Model\Validator\ProductQuantityRestrictionValidator;
use Spryker\Zed\ProductQuantity\Business\Model\Validator\ProductQuantityRestrictionValidatorInterface;

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
     * @return \Spryker\Zed\ProductQuantity\Business\Model\Normalizer\ProductQuantityRestrictionNormalizerInterface
     */
    public function createProductQuantityRestrictionNormalizer(): ProductQuantityRestrictionNormalizerInterface
    {
        return new ProductQuantityRestrictionNormalizer(
            $this->createProductQuantityReader(),
            $this->createProductQuantityRounder()
        );
    }

    /**
     * @return \Spryker\Zed\ProductQuantity\Business\Model\Rounder\ProductQuantityRounderInterface
     */
    public function createProductQuantityRounder(): ProductQuantityRounderInterface
    {
        return new ProductQuantityRounder();
    }
}
