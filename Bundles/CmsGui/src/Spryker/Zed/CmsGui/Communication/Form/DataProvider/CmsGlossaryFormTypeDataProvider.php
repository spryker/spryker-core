<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\CmsGlossaryAttributesTransfer;
use Generated\Shared\Transfer\CmsGlossaryTransfer;
use Spryker\Zed\CmsGui\Communication\Form\Glossary\CmsGlossaryFormType;

class CmsGlossaryFormTypeDataProvider
{

    /**
     * @var \Generated\Shared\Transfer\CmsGlossaryTransfer
     */
    protected $cmsGlossaryTransfer;

    /**
     * @param \Generated\Shared\Transfer\CmsGlossaryTransfer $cmsGlossaryTransfer
     */
    public function __construct(CmsGlossaryTransfer $cmsGlossaryTransfer)
    {
        $this->cmsGlossaryTransfer = $cmsGlossaryTransfer;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return [
            'data_class' => CmsGlossaryTransfer::class,
            CmsGlossaryFormType::OPTION_DATA_CLASS_ATTRIBUTES => CmsGlossaryAttributesTransfer::class,
        ];
    }

    /**
     * @return \Generated\Shared\Transfer\CmsGlossaryTransfer
     */
    public function getData()
    {
        return $this->cmsGlossaryTransfer;
    }

}
