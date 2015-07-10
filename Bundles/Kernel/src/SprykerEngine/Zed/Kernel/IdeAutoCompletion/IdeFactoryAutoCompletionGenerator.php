<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\Kernel\IdeAutoCompletion;

use Symfony\Component\OptionsResolver\OptionsResolver;

class IdeFactoryAutoCompletionGenerator extends AbstractIdeAutoCompletionGenerator
{

    /**
     * @param OptionsResolver $resolver
     */
    protected function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            self::OPTION_KEY_INTERFACE_NAME => 'FactoryAutoCompletion',
            self::OPTION_KEY_HAS_LAYERS => true,
        ]);
    }

    /**
     * @param null $output
     */
    public function create($output = null)
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
            foreach ($this->methodTagBuilder as $methodTagBuilder) {
                $methodTags = $methodTagBuilder->buildMethodTags($bundle);
                if (count($methodTags) > 0) {
                    $layer = $this->getLayerFromMethodTags($methodTags);
                    $interface = '/**' . PHP_EOL;
                    $interface .= implode(PHP_EOL, $methodTags) . PHP_EOL;
                    $interface .= ' */' . PHP_EOL;
                    $interface .= 'interface ' . $bundle . $layer . PHP_EOL . '{}' . PHP_EOL;

                    $interfaces .= $interface . PHP_EOL;
                }
            }
        }

        return $interfaces;
    }

    /**
     * @param array $methodTags
     *
     * @return string
     */
    private function getLayerFromMethodTags(array $methodTags)
    {
        if (!$this->options[self::OPTION_KEY_HAS_LAYERS]) {
            return '';
        }
        $keys = array_keys($methodTags);
        $firstKey = $keys[0];
        $keyParts = explode('\\', $firstKey);

        return $keyParts[2];
    }

}
