<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\Kernel\IdeAutoCompletion\MethodTagBuilder;

use Symfony\Component\OptionsResolver\OptionsResolver;

class GeneratedInterfaceMethodTagBuilder implements MethodTagBuilderInterface
{

    const METHOD_STRING_PATTERN = ' * @method \\Generated\Zed\Ide\{{bundle}} {{methodName}}()';
    const OPTION_METHOD_STRING_PATTERN = 'method string pattern';
    const PLACEHOLDER_BUNDLE = '{{bundle}}';
    const PLACEHOLDER_METHOD_NAME = '{{methodName}}';

    /**
     * @var array
     */
    protected $options;

    /**
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        $resolver = new OptionsResolver();
        $resolver->setDefaults([
            self::OPTION_METHOD_STRING_PATTERN => self::METHOD_STRING_PATTERN,
        ]);
        $resolver->setRequired([
            self::OPTION_METHOD_STRING_PATTERN,
        ]);
        $resolver->setAllowedTypes(self::OPTION_METHOD_STRING_PATTERN, 'string');

        $this->options = $resolver->resolve($options);
    }

    /**
     * @param string $bundle
     * @param array $methodTags
     *
     * @return array
     */
    public function buildMethodTags($bundle, array $methodTags = [])
    {
        $methodTags[] = $this->getMethodTag($bundle);

        return $methodTags;
    }

    /**
     * @param string $bundle
     *
     * @return string
     */
    private function getMethodTag($bundle)
    {
        return str_replace(
            [self::PLACEHOLDER_BUNDLE, self::PLACEHOLDER_METHOD_NAME],
            [$bundle, lcfirst($bundle)],
            $this->options[self::OPTION_METHOD_STRING_PATTERN]
        );
    }

}
