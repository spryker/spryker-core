<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Kernel\IdeAutoCompletion;

/**
 * @deprecated Will be removed with next major release
 */
class IdeAutoCompletionGenerator extends AbstractIdeAutoCompletionGenerator
{

    /**
     * @return void
     */
    public function create()
    {
        $fileContent = $this->getBaseFile($this->options[self::OPTION_KEY_NAMESPACE]);

        $methodTags = $this->getMethodTags();
        if (count($methodTags) > 0) {
            $interface = '/**' . PHP_EOL;
            $interface .= implode(PHP_EOL, $methodTags) . PHP_EOL;
            $interface .= ' */' . PHP_EOL;
            $interface .= 'interface ' . $this->options[self::OPTION_KEY_INTERFACE_NAME] . PHP_EOL . '{}' . PHP_EOL;

            $fileContent .= $interface . PHP_EOL;
        }

        $this->saveFileContent($fileContent);
    }

    /**
     * @return array
     */
    private function getMethodTags()
    {
        $bundles = $this->getBundles();
        $methodTags = [];
        foreach ($bundles as $bundle) {
            $methodBundleTag = $this->getMethodTagsByBundle($bundle);
            $methodTags = array_merge($methodTags, $methodBundleTag);
        }

        return $methodTags;
    }

}
