<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerAccessGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\CustomerAccessTransfer;
use Spryker\Zed\CustomerAccessGui\Communication\Form\CustomerAccessForm;
use Spryker\Zed\CustomerAccessGui\Dependency\Facade\CustomerAccessGuiToCustomerAccessFacadeInterface;

class CustomerAccessDataProvider
{
    /**
     * @var \Spryker\Zed\CustomerAccessGui\Dependency\Facade\CustomerAccessGuiToCustomerAccessFacadeInterface
     */
    protected $customerAccessFacade;

    /**
     * @var \Generated\Shared\Transfer\CustomerAccessTransfer
     */
    protected $customerAccessTransfer;

    /**
     * @param \Spryker\Zed\CustomerAccessGui\Dependency\Facade\CustomerAccessGuiToCustomerAccessFacadeInterface $customerAccessFacade
     * @param \Generated\Shared\Transfer\CustomerAccessTransfer|null $customerAccessTransfer
     */
    public function __construct(CustomerAccessGuiToCustomerAccessFacadeInterface $customerAccessFacade, CustomerAccessTransfer $customerAccessTransfer = null)
    {
        $this->customerAccessFacade = $customerAccessFacade;
        $this->customerAccessTransfer = $customerAccessTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\CustomerAccessTransfer
     */
    public function getData()
    {
        return $this->customerAccessTransfer;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return [
            CustomerAccessForm::FIELD_CONTENT_TYPE_ACCESS => $this->customerAccessFacade->findAllContentTypes()->getContentTypeAccess(),
        ];
    }
}
