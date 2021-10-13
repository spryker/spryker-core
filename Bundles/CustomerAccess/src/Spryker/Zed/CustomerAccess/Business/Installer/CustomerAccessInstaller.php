<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerAccess\Business\Installer;

use Spryker\Zed\CustomerAccess\Business\CustomerAccess\CustomerAccessCreatorInterface;
use Spryker\Zed\CustomerAccess\Business\CustomerAccess\CustomerAccessReaderInterface;
use Spryker\Zed\CustomerAccess\CustomerAccessConfig;

class CustomerAccessInstaller implements CustomerAccessInstallerInterface
{
    /**
     * @var string
     */
    protected const ALERT_MESSAGE = 'You need to clean up table spy_unauthenticated_customer_access to get %s access config update.';

    /**
     * @var \Spryker\Zed\CustomerAccess\Business\CustomerAccess\CustomerAccessCreatorInterface
     */
    protected $customerAccessCreator;

    /**
     * @var \Spryker\Zed\CustomerAccess\CustomerAccessConfig
     */
    protected $customerAccessConfig;

    /**
     * @var \Spryker\Zed\CustomerAccess\Business\CustomerAccess\CustomerAccessReaderInterface
     */
    protected $customerAccessReader;

    /**
     * @param \Spryker\Zed\CustomerAccess\CustomerAccessConfig $customerAccessConfig
     * @param \Spryker\Zed\CustomerAccess\Business\CustomerAccess\CustomerAccessCreatorInterface $customerAccessCreator
     * @param \Spryker\Zed\CustomerAccess\Business\CustomerAccess\CustomerAccessReaderInterface $customerAccessReader
     */
    public function __construct(
        CustomerAccessConfig $customerAccessConfig,
        CustomerAccessCreatorInterface $customerAccessCreator,
        CustomerAccessReaderInterface $customerAccessReader
    ) {
        $this->customerAccessCreator = $customerAccessCreator;
        $this->customerAccessReader = $customerAccessReader;
        $this->customerAccessConfig = $customerAccessConfig;
    }

    /**
     * @return void
     */
    public function install(): void
    {
        $contentTypeAccess = $this->customerAccessConfig->getContentTypeAccess();
        $contentAccessByType = $this->customerAccessConfig->getContentAccessByType();

        foreach ($this->customerAccessConfig->getContentTypes() as $contentType) {
            if ($this->customerAccessReader->findCustomerAccessByContentType($contentType) !== null) {
                print sprintf(static::ALERT_MESSAGE . PHP_EOL, $contentType);

                continue;
            }

            $isGranted = $contentAccessByType[$contentType] ?? $contentTypeAccess;

            $this->customerAccessCreator->createCustomerAccess($contentType, !$isGranted);
        }
    }
}
