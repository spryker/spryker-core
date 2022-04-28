<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CmsBlockGui\PageObject;

class CmsBlockGuiCreatePage
{
    /**
     * @var string
     */
    public const URL = '/cms-block-gui/create-block';

    /**
     * @var string
     */
    public const FORM_SUBMIT_BUTTON = 'Save';

    /**
     * @var string
     */
    public const SUCCESS_MESSAGE = 'CMS Block was created successfully.';

    /**
     * @var string
     */
    public const FORM_FIELD_NAME_KEY = 'cms_block[name]';

    /**
     * @var string
     */
    public const FORM_FIELD_NAME_VALUE = 'CMS block name';
}
