<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cms\Business\Mapping;

use Spryker\Zed\Cms\Dependency\Facade\CmsToGlossaryInterface;

class CmsGlossaryKeyGenerator implements CmsGlossaryKeyGeneratorInterface
{
    public const GENERATED_GLOSSARY_KEY_PREFIX = 'generated.cms';
    public const ID_CMS_PAGE = 'idCmsPage';
    public const UNIQUE_ID = 'uniqueId';

    /**
     * @var \Spryker\Zed\Cms\Dependency\Facade\CmsToGlossaryInterface
     */
    protected $glossaryFacade;

    /**
     * @param \Spryker\Zed\Cms\Dependency\Facade\CmsToGlossaryInterface $glossaryFacade
     */
    public function __construct(CmsToGlossaryInterface $glossaryFacade)
    {
        $this->glossaryFacade = $glossaryFacade;
    }

    /**
     * @param int $idCmsPage
     * @param string $templateName
     * @param string $placeholder
     * @param bool $autoIncrement
     *
     * @return string
     */
    public function generateGlossaryKeyName($idCmsPage, $templateName, $placeholder, $autoIncrement = true)
    {
        $keyName = static::GENERATED_GLOSSARY_KEY_PREFIX . '.';
        $keyName .= str_replace([' ', '.'], '-', $templateName) . '.';
        $keyName .= str_replace([' ', '.'], '-', $placeholder);

        $candidate = sprintf('%s.%s.%d', $keyName, static::ID_CMS_PAGE, $idCmsPage);

        $index = 0;

        while ($this->glossaryFacade->hasKey($candidate) && $autoIncrement === true) {
            $candidate = sprintf('%s.%s.%d.%s.%d', $keyName, static::ID_CMS_PAGE, $idCmsPage, static::UNIQUE_ID, ++$index);
        }

        return $candidate;
    }
}
