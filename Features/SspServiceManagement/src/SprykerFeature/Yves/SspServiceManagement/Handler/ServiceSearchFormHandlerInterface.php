<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SspServiceManagement\Handler;

use Generated\Shared\Transfer\SspServiceCriteriaTransfer;
use Symfony\Component\Form\FormInterface;

interface ServiceSearchFormHandlerInterface
{
    /**
     * @param \Symfony\Component\Form\FormInterface $serviceSearchForm
     * @param \Generated\Shared\Transfer\SspServiceCriteriaTransfer $sspServiceCriteriaTransfer
     * @param string|null $productServiceTypeName
     *
     * @return \Generated\Shared\Transfer\SspServiceCriteriaTransfer
     */
    public function handleServiceSearchFormSubmit(
        FormInterface $serviceSearchForm,
        SspServiceCriteriaTransfer $sspServiceCriteriaTransfer,
        ?string $productServiceTypeName = null
    ): SspServiceCriteriaTransfer;
}
