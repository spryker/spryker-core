<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlot\Business\Validator;

use Generated\Shared\Transfer\CmsSlotTemplateTransfer;
use Generated\Shared\Transfer\ValidationResponseTransfer;
use Spryker\Zed\CmsSlot\Business\ConstraintsProvider\ConstraintsProviderInterface;
use Spryker\Zed\CmsSlot\Dependency\External\CmsSlotToSymfonyValidatorAdapterInterface;

class CmsSlotTemplateValidator extends AbstractTransferValidator implements CmsSlotTemplateValidatorInterface
{
    /**
     * @var \Spryker\Zed\CmsSlot\Dependency\External\CmsSlotToSymfonyValidatorAdapterInterface
     */
    protected $validatorAdapter;

    /**
     * @var \Spryker\Zed\CmsSlot\Business\ConstraintsProvider\ConstraintsProviderInterface
     */
    protected $constraintsProvider;

    /**
     * @param \Spryker\Zed\CmsSlot\Dependency\External\CmsSlotToSymfonyValidatorAdapterInterface $validatorAdapter
     * @param \Spryker\Zed\CmsSlot\Business\ConstraintsProvider\ConstraintsProviderInterface $constraintsProvider
     */
    public function __construct(
        CmsSlotToSymfonyValidatorAdapterInterface $validatorAdapter,
        ConstraintsProviderInterface $constraintsProvider
    ) {
        $this->validatorAdapter = $validatorAdapter;
        $this->constraintsProvider = $constraintsProvider;
    }

    /**
     * @param \Generated\Shared\Transfer\CmsSlotTemplateTransfer $cmsSlotTemplateTransfer
     *
     * @return \Generated\Shared\Transfer\ValidationResponseTransfer
     */
    public function validateCmsSlotTemplate(CmsSlotTemplateTransfer $cmsSlotTemplateTransfer): ValidationResponseTransfer
    {
        return $this->validate($cmsSlotTemplateTransfer, $this->validatorAdapter, $this->constraintsProvider);
    }
}
