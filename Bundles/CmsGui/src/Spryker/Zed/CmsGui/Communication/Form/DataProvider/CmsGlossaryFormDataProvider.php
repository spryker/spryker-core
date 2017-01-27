<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\CmsGlossaryTransfer;
use Spryker\Zed\CmsGui\Dependency\QueryContainer\CmsGuiToCmsQueryContainerInterface;

class CmsGlossaryFormDataProvider
{

    /**
     * @var array|\Generated\Shared\Transfer\LocaleTransfer[]
     */
    protected $availableLocales;

    /**
     * @var \Spryker\Zed\CmsGui\Dependency\QueryContainer\CmsGuiToCmsQueryContainerInterface
     */
    protected $cmsQueryContainer;

    /**
     * @var \Generated\Shared\Transfer\CmsGlossaryTransfer
     */
    protected $cmsGlossaryTransfer;

    /**
     * @param array|\Generated\Shared\Transfer\LocaleTransfer[] $availableLocales
     * @param \Spryker\Zed\CmsGui\Dependency\QueryContainer\CmsGuiToCmsQueryContainerInterface $cmsQueryContainer
     * @param \Generated\Shared\Transfer\CmsGlossaryTransfer $cmsGlossaryTransfer
     */
    public function __construct(
        array $availableLocales,
        CmsGuiToCmsQueryContainerInterface $cmsQueryContainer,
        CmsGlossaryTransfer $cmsGlossaryTransfer

    ) {
        $this->availableLocales = $availableLocales;
        $this->cmsQueryContainer = $cmsQueryContainer;
        $this->cmsGlossaryTransfer = $cmsGlossaryTransfer;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return [
            'data_class' => CmsGlossaryTransfer::class
        ];
    }

    /**
     *
     * @return \Generated\Shared\Transfer\CmsGlossaryTransfer
     */
    public function getData()
    {
        return $this->cmsGlossaryTransfer;
    }


}
