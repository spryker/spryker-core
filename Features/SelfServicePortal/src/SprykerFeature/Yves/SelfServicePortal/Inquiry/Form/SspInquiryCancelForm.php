<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SelfServicePortal\Inquiry\Form;

use Spryker\Yves\Kernel\Form\AbstractType;

/**
 * @method \SprykerFeature\Yves\SelfServicePortal\SelfServicePortalConfig getConfig()
 */
class SspInquiryCancelForm extends AbstractType
{
    /**
     * @var string
     */
    protected const FORM_NAME = 'sspInquiryCancelForm';

    public function getBlockPrefix(): string
    {
        return static::FORM_NAME;
    }
}
