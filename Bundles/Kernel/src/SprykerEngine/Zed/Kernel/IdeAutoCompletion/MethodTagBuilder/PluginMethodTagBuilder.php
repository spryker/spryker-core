<?php

namespace SprykerEngine\Zed\Kernel\IdeAutoCompletion\MethodTagBuilder;

use Symfony\Component\OptionsResolver\OptionsResolver;

class PluginMethodTagBuilder extends AbstractMultiFileMethodTagBuilder
{

    const METHOD_STRING_PATTERN = '@method \{{className}} plugin{{methodName}}()';
    const PATH_PATTERN = 'Communication/Plugin/';

    /**
     * @param OptionsResolver $resolver
     */
    protected function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            self::OPTION_KEY_PATH_PATTERN => self::PATH_PATTERN,
            self::OPTION_KEY_METHOD_STRING_PATTERN => self::METHOD_STRING_PATTERN,
        ]);
    }

    /**
     * @param $bundle
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
     * @param $className
     *
     * @return bool
     */
    protected function ignoreClass($className)
    {
        if (!class_exists($className)) {
            return true;
        }
        $class = new \ReflectionClass($className);

        if (!$class->isInstantiable()) {
            return true;
        }

        return false;
    }

    /**
     * @param $className
     *
     * @return string
     */
    protected function buildMethodNameFromClassName($className)
    {
        $classNameParts = explode('\\', $className);
        $classNameParts = array_splice($classNameParts, 5);

        return implode($classNameParts);
    }
}
