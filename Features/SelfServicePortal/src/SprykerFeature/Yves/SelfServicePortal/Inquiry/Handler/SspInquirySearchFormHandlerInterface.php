<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SelfServicePortal\Inquiry\Handler;

use Generated\Shared\Transfer\SspInquiryCriteriaTransfer;
use Symfony\Component\Form\FormInterface;

interface SspInquirySearchFormHandlerInterface
{
    public function handleFormSubmit(FormInterface $sspInquirySearchForm, SspInquiryCriteriaTransfer $sspInquiryCriteriaTransfer): SspInquiryCriteriaTransfer;
}
