<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\CmsGlossaryAttributesTransfer;
use Generated\Shared\Transfer\CmsGlossaryTransfer;
use Spryker\Zed\CmsGui\Communication\Form\Glossary\CmsGlossaryAttributesFormType;
use Spryker\Zed\CmsGui\Communication\Form\Glossary\CmsGlossaryFormType;

class CmsGlossaryFormTypeDataProvider
{

    const TYPE_GLOSSARY_NEW = 'New glossary';
    const TYPE_GLOSSARY_FIND = 'Find glossary by key';
    const TYPE_AUTO_GLOSSARY = 'Auto';
    const TYPE_FULLTEXT_SEARCH = 'Find glossary by value';

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
            CmsGlossaryAttributesFormType::OPTION_GLOSSARY_KEY_SEARCH_OPTIONS => $this->getGlossaryChoices(),
        ];
    }

    /**
     * @return \Generated\Shared\Transfer\CmsGlossaryTransfer
     */
    public function getData()
    {
        return $this->cmsGlossaryTransfer;
    }

    /**
     * @return array
     */
    protected function getGlossaryChoices()
    {
        return [
            static::TYPE_AUTO_GLOSSARY,
            static::TYPE_GLOSSARY_NEW,
            static::TYPE_GLOSSARY_FIND,
            static::TYPE_FULLTEXT_SEARCH,
        ];
    }

}
