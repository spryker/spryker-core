<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlot\Business\Validator;

use Generated\Shared\Transfer\CmsSlotTransfer;
use Generated\Shared\Transfer\DataObjectValidationResponseTransfer;
use Spryker\Zed\CmsSlot\Business\ConstraintsProvider\ConstraintsProviderInterface;
use Spryker\Zed\CmsSlot\Dependency\External\CmsSlotToValidationAdapterInterface;

class CmsSlotValidator extends AbstractTransferValidator implements CmsSlotValidatorInterface
{
    /**
     * @var \Spryker\Zed\CmsSlot\Dependency\External\CmsSlotToValidationAdapterInterface
     */
    protected $validationAdapter;

    /**
     * @var \Spryker\Zed\CmsSlot\Business\ConstraintsProvider\ConstraintsProviderInterface
     */
    protected $constraintsProvider;

    /**
     * @param \Spryker\Zed\CmsSlot\Dependency\External\CmsSlotToValidationAdapterInterface $validationAdapter
     * @param \Spryker\Zed\CmsSlot\Business\ConstraintsProvider\ConstraintsProviderInterface $constraintsProvider
     */
    public function __construct(
        CmsSlotToValidationAdapterInterface $validationAdapter,
        ConstraintsProviderInterface $constraintsProvider
    ) {
        $this->validationAdapter = $validationAdapter;
        $this->constraintsProvider = $constraintsProvider;
    }

    /**
     * @param \Generated\Shared\Transfer\CmsSlotTransfer $cmsSlotTransfer
     *
     * @return \Generated\Shared\Transfer\DataObjectValidationResponseTransfer
     */
    public function validateCmsSlot(CmsSlotTransfer $cmsSlotTransfer): DataObjectValidationResponseTransfer
    {
        return $this->validate($cmsSlotTransfer, $this->validationAdapter, $this->constraintsProvider);
    }
}
