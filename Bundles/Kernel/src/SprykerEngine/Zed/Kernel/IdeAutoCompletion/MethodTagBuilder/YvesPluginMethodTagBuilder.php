<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\Kernel\IdeAutoCompletion\MethodTagBuilder;

use Symfony\Component\OptionsResolver\OptionsResolver;

class YvesPluginMethodTagBuilder extends AbstractMultiFileMethodTagBuilder
{

    const METHOD_STRING_PATTERN = '@method \{{className}} plugin{{methodName}}()';
    const PATH_PATTERN = 'Plugin/';
    const APPLICATION_YVES = 'Yves';
    const METHOD_SUFFIX = 'Plugin';

    /**
     * @param OptionsResolver $resolver
     */
    protected function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            self::OPTION_KEY_APPLICATION => self::APPLICATION_YVES,
            self::OPTION_KEY_PATH_PATTERN => self::PATH_PATTERN,
            self::OPTION_KEY_METHOD_STRING_PATTERN => self::METHOD_STRING_PATTERN,
        ]);
    }

    /**
     * @param string $bundle
     * @param array $methodTags
     *
     * @return array
     */
    public function buildMethodTags($bundle, array $methodTags = [])
    {
        $generatedMethodTags = $this->getMethodTags($bundle);
        if ($generatedMethodTags) {
            $methodTags = $methodTags + $generatedMethodTags;
        }

        return $methodTags;
    }

    /**
     * @param string $className
     *
     * @return string
     */
    protected function buildMethodNameFromClassName($className)
    {
        $classNameParts = explode('\\', $className);
        $classNameParts = array_splice($classNameParts, 4);
        $reversedClassString = strrev(implode($classNameParts));
        $methodSuffixStringLength = strlen(self::METHOD_SUFFIX);

        return strrev(substr($reversedClassString, $methodSuffixStringLength));
    }

}
