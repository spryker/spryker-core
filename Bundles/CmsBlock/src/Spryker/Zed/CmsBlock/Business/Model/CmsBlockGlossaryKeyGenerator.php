<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlock\Business\Model;

use Spryker\Zed\CmsBlock\Dependency\Facade\CmsBlockToGlossaryInterface;

class CmsBlockGlossaryKeyGenerator implements CmsBlockGlossaryKeyGeneratorInterface
{
    public const GENERATED_GLOSSARY_KEY_PREFIX = 'generated.cms.cms-block';
    public const ID_CMS_BLOCK = 'idCmsBlock';
    public const UNIQUE_ID = 'uniqueId';

    /**
     * @var \Spryker\Zed\CmsBlock\Dependency\Facade\CmsBlockToGlossaryInterface
     */
    protected $glossaryFacade;

    /**
     * @param \Spryker\Zed\CmsBlock\Dependency\Facade\CmsBlockToGlossaryInterface $glossaryFacade
     */
    public function __construct(CmsBlockToGlossaryInterface $glossaryFacade)
    {
        $this->glossaryFacade = $glossaryFacade;
    }

    /**
     * @param int $idCmsBlock
     * @param string $templateName
     * @param string $placeholder
     * @param bool $autoIncrement
     *
     * @return string
     */
    public function generateGlossaryKeyName(
        int $idCmsBlock,
        string $templateName,
        string $placeholder,
        bool $autoIncrement = true
    ): string {
        $keyName = static::GENERATED_GLOSSARY_KEY_PREFIX . '.';
        $keyName .= str_replace([' ', '.'], '-', $templateName) . '.';
        $keyName .= str_replace([' ', '.'], '-', $placeholder);

        $index = 0;

        do {
            $candidate = sprintf('%s.%s.%d.%s.%d', $keyName, static::ID_CMS_BLOCK, $idCmsBlock, static::UNIQUE_ID, ++$index);
        } while ($autoIncrement === true && $this->glossaryFacade->hasKey($candidate));

        return $candidate;
    }
}
