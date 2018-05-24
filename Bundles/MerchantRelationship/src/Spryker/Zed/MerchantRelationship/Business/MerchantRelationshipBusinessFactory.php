<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationship\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\MerchantRelationship\Business\Model\MerchantRelationshipReader;
use Spryker\Zed\MerchantRelationship\Business\Model\MerchantRelationshipReaderInterface;
use Spryker\Zed\MerchantRelationship\Business\Model\MerchantRelationshipWriter;
use Spryker\Zed\MerchantRelationship\Business\Model\MerchantRelationshipWriterInterface;

/**
 * @method \Spryker\Zed\MerchantRelationship\Persistence\MerchantRelationshipRepositoryInterface getRepository()
 * @method \Spryker\Zed\MerchantRelationship\Persistence\MerchantRelationshipEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\MerchantRelationship\MerchantRelationshipConfig getConfig()
 */
class MerchantRelationshipBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\MerchantRelationship\Business\Model\MerchantRelationshipWriterInterface
     */
    public function createMerchantRelationshipWriter(): MerchantRelationshipWriterInterface
    {
        return new MerchantRelationshipWriter(
            $this->getEntityManager(),
            $this->getRepository()
        );
    }

    /**
     * @return \Spryker\Zed\MerchantRelationship\Business\Model\MerchantRelationshipReaderInterface
     */
    public function createMerchantRelationshipReader(): MerchantRelationshipReaderInterface
    {
        return new MerchantRelationshipReader(
            $this->getRepository()
        );
    }
}
