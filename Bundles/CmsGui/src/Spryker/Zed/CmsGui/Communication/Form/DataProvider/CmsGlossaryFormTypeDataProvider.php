<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\CmsGlossaryAttributesTransfer;
use Generated\Shared\Transfer\CmsGlossaryTransfer;
use Spryker\Zed\CmsGui\Communication\Exception\CmsGlossaryNotFoundException;
use Spryker\Zed\CmsGui\Communication\Expander\CmsGlossaryExpanderInterface;
use Spryker\Zed\CmsGui\Communication\Form\Glossary\CmsGlossaryFormType;
use Spryker\Zed\CmsGui\Dependency\Facade\CmsGuiToCmsInterface;

class CmsGlossaryFormTypeDataProvider
{
    public const TYPE_GLOSSARY_NEW = 'New glossary';
    public const TYPE_GLOSSARY_FIND = 'Find glossary by key';
    public const TYPE_AUTO_GLOSSARY = 'Auto';
    public const TYPE_FULLTEXT_SEARCH = 'Find glossary by value';

    /**
     * @var \Generated\Shared\Transfer\CmsGlossaryTransfer
     */
    protected $cmsGlossaryTransfer;

    /**
     * @var \Spryker\Zed\CmsGui\Dependency\Facade\CmsGuiToCmsInterface
     */
    protected $cmsFacade;

    /**
     * @var \Spryker\Zed\CmsGui\Communication\Expander\CmsGlossaryExpanderInterface
     */
    protected $cmsGlossaryExpander;

    /**
     * @param \Spryker\Zed\CmsGui\Dependency\Facade\CmsGuiToCmsInterface $cmsFacade
     * @param \Spryker\Zed\CmsGui\Communication\Expander\CmsGlossaryExpanderInterface $cmsGlossaryExpander
     */
    public function __construct(CmsGuiToCmsInterface $cmsFacade, CmsGlossaryExpanderInterface $cmsGlossaryExpander)
    {
        $this->cmsFacade = $cmsFacade;
        $this->cmsGlossaryExpander = $cmsGlossaryExpander;
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
     * @param int $idCmsPage
     *
     * @throws \Spryker\Zed\CmsGui\Communication\Exception\CmsGlossaryNotFoundException
     *
     * @return \Generated\Shared\Transfer\CmsGlossaryTransfer
     */
    public function getData($idCmsPage)
    {
        $cmsGlossaryTransfer = $this->cmsFacade->findPageGlossaryAttributes($idCmsPage);
        $cmsGlossaryTransfer = $this->cmsGlossaryExpander->executeAfterFindPlugins($cmsGlossaryTransfer);

        if (!$cmsGlossaryTransfer) {
            throw new CmsGlossaryNotFoundException(
                sprintf(
                    'Glossary attributes for page "%d" is not defined',
                    $idCmsPage
                )
            );
        }

        return $cmsGlossaryTransfer;
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
