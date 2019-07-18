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
     * @param \Spryker\Zed\CustomerAccessGui\Dependency\Facade\CustomerAccessGuiToCustomerAccessFacadeInterface $customerAccessFacade
     */
    public function __construct(
        CustomerAccessGuiToCustomerAccessFacadeInterface $customerAccessFacade
    ) {
        $this->customerAccessFacade = $customerAccessFacade;
    }

    /**
     * @return \Generated\Shared\Transfer\CustomerAccessTransfer
     */
    public function getData(): CustomerAccessTransfer
    {
        return $this->customerAccessFacade->getRestrictedContentTypes();
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return [
            'data_class' => CustomerAccessTransfer::class,
            CustomerAccessForm::OPTION_CONTENT_TYPE_ACCESS
                => $this->customerAccessFacade->getAllContentTypes()->getContentTypeAccess(),
        ];
    }
}
