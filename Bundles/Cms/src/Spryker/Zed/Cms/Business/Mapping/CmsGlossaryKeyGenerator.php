<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cms\Business\Mapping;

use Spryker\Zed\Cms\Dependency\Facade\CmsToGlossaryInterface;

class CmsGlossaryKeyGenerator implements CmsGlossaryKeyGeneratorInterface
{

    const GENERATED_GLOSSARY_KEY_PREFIX = 'generated.cms';

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
     * @param string $templateName
     * @param string $placeholder
     * @param bool $autoIncrement
     *
     * @return string
     */
    public function generateGlossaryKeyName($templateName, $placeholder, $autoIncrement = true)
    {
        $keyName = static::GENERATED_GLOSSARY_KEY_PREFIX . '.';
        $keyName .= str_replace([' ', '.'], '-', $templateName) . '.';
        $keyName .= str_replace([' ', '.'], '-', $placeholder);

        $index = 0;

        $candidate = $keyName . $index;

        while ($this->glossaryFacade->hasKey($candidate) && $autoIncrement === true) {
            $candidate = $keyName . ++$index;
        }

        return $candidate;
    }

}
