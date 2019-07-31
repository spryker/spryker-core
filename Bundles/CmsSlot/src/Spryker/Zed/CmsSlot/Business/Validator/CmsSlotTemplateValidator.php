<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlot\Business\Validator;

use Generated\Shared\Transfer\CmsSlotTemplateTransfer;
use Generated\Shared\Transfer\DataObjectValidationResponseTransfer;
use Spryker\Zed\CmsSlot\Business\ConstraintsProvider\ConstraintsProviderInterface;
use Spryker\Zed\CmsSlot\Dependency\External\CmsSlotToValidationAdapterInterface;

class CmsSlotTemplateValidator extends AbstractTransferValidator implements CmsSlotTemplateValidatorInterface
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
     * @param \Generated\Shared\Transfer\CmsSlotTemplateTransfer $cmsSlotTemplateTransfer
     *
     * @return \Generated\Shared\Transfer\DataObjectValidationResponseTransfer
     */
    public function validateCmsSlotTemplate(CmsSlotTemplateTransfer $cmsSlotTemplateTransfer): DataObjectValidationResponseTransfer
    {
        return $this->validate($cmsSlotTemplateTransfer, $this->validationAdapter, $this->constraintsProvider);
    }
}
