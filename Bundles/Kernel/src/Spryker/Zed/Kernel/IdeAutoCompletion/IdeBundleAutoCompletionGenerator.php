<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Kernel\IdeAutoCompletion;

use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @deprecated Will be removed with next major release
 */
class IdeBundleAutoCompletionGenerator extends AbstractIdeAutoCompletionGenerator
{

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    protected function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            self::OPTION_KEY_INTERFACE_NAME => 'BundleAutoCompletion',
        ]);
    }

    /**
     * @return void
     */
    public function create()
    {
        $fileContent = $this->getBaseFile($this->options[self::OPTION_KEY_NAMESPACE]);
        $fileContent .= $this->generateInterfaces();

        $this->saveFileContent($fileContent);
    }

    /**
     * @return string
     */
    private function generateInterfaces()
    {
        $bundles = $this->getBundles();
        $interfaces = '';
        foreach ($bundles as $bundle) {
            $methodTags = $this->getMethodTagsByBundle($bundle);
            if (count($methodTags) > 0) {
                $interface = '/**' . PHP_EOL;
                $interface .= implode(PHP_EOL, $methodTags) . PHP_EOL;
                $interface .= ' */' . PHP_EOL;
                $interface .= 'interface ' . $bundle . PHP_EOL . '{}' . PHP_EOL;

                $interfaces .= $interface . PHP_EOL;
            }
        }

        return $interfaces;
    }

}
