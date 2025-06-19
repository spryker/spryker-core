<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Customer\Business\ReferenceGenerator;

use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Zed\Customer\CustomerConfig;
use Spryker\Zed\Customer\Dependency\Facade\CustomerToSequenceNumberInterface;
use Spryker\Zed\Customer\Dependency\Facade\CustomerToStoreFacadeInterface;

class CustomerReferenceGenerator implements CustomerReferenceGeneratorInterface
{
    /**
     * @var \Spryker\Zed\Customer\Dependency\Facade\CustomerToSequenceNumberInterface
     */
    protected $sequenceNumberFacade;

    /**
     * @var \Spryker\Zed\Customer\Dependency\Facade\CustomerToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @var \Spryker\Zed\Customer\CustomerConfig
     */
    protected $config;

    /**
     * @param \Spryker\Zed\Customer\Dependency\Facade\CustomerToSequenceNumberInterface $sequenceNumberFacade
     * @param \Spryker\Zed\Customer\Dependency\Facade\CustomerToStoreFacadeInterface $storeFacade
     * @param \Spryker\Zed\Customer\CustomerConfig $config
     */
    public function __construct(
        CustomerToSequenceNumberInterface $sequenceNumberFacade,
        CustomerToStoreFacadeInterface $storeFacade,
        CustomerConfig $config
    ) {
        $this->sequenceNumberFacade = $sequenceNumberFacade;
        $this->storeFacade = $storeFacade;
        $this->config = $config;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return string
     */
    public function generateCustomerReference(CustomerTransfer $customerTransfer)
    {
        $sequenceNumberPrefix = $this->config->getCustomerSequenceNumberPrefix();

        if (!$sequenceNumberPrefix) {
            $sequenceNumberPrefix = $this->storeFacade->getCurrentStore(true)->getNameOrFail();
        }

        return $this->sequenceNumberFacade->generate(
            $this->config->getCustomerReferenceDefaults($sequenceNumberPrefix),
        );
    }
}
