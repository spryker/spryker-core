<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\SspModel\Mapper;

use Generated\Shared\Transfer\SspModelTransfer;
use Symfony\Component\Form\FormInterface;

interface SspModelFormDataToTransferMapperInterface
{
    /**
     * @param \Symfony\Component\Form\FormInterface $form
     * @param \Generated\Shared\Transfer\SspModelTransfer $sspModelTransfer
     *
     * @return \Generated\Shared\Transfer\SspModelTransfer
     */
    public function mapFormDataToSspModelTransfer(FormInterface $form, SspModelTransfer $sspModelTransfer): SspModelTransfer;
}
