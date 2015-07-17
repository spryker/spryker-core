<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\Kernel\IdeAutoCompletion\MethodTagBuilder;

use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class AbstractMultiFileMethodTagBuilder implements MethodTagBuilderInterface
{

    const OPTION_KEY_METHOD_STRING_PATTERN = 'method string pattern';
    const OPTION_KEY_PATH_PATTERN = 'path pattern';
    const OPTION_KEY_PROJECT_PATH_PATTERN = 'project path pattern';
    const OPTION_KEY_VENDOR_PATH_PATTERN = 'core path pattern';
    const OPTION_KEY_APPLICATION = 'application';
    const OPTION_KEY_DEPTH = 'depth';
    const OPTION_KEY_CLASS_NAME_PART_LEVEL = 'class name part level';

    const APPLICATION = 'Shared';

    const DEPTH = '< 100';
    const CLASS_NAME_PART_LEVEL = 4;

    const PLACEHOLDER_CLASS_NAME = '{{className}}';
    const PLACEHOLDER_METHOD_NAME = '{{methodName}}';

    /**
     * @var array
     */
    private $options;

    /**
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        $resolver = new OptionsResolver();
        $this->configureOptions($resolver);

        $this->options = $resolver->resolve($options);
    }

    /**
     * @param OptionsResolver $resolver
     */
    protected function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            self::OPTION_KEY_APPLICATION => self::APPLICATION,
            self::OPTION_KEY_PROJECT_PATH_PATTERN => APPLICATION_SOURCE_DIR,
            self::OPTION_KEY_VENDOR_PATH_PATTERN => APPLICATION_VENDOR_DIR . '/spryker/spryker/Bundles/*/src',
            self::OPTION_KEY_DEPTH => self::DEPTH,
            self::OPTION_KEY_CLASS_NAME_PART_LEVEL => self::CLASS_NAME_PART_LEVEL,
        ]);

        $resolver->setRequired([
            self::OPTION_KEY_APPLICATION,
            self::OPTION_KEY_PROJECT_PATH_PATTERN,
            self::OPTION_KEY_VENDOR_PATH_PATTERN,
            self::OPTION_KEY_METHOD_STRING_PATTERN,
            self::OPTION_KEY_PATH_PATTERN,
        ]);

        $resolver->setAllowedTypes(self::OPTION_KEY_APPLICATION, 'string');
        $resolver->setAllowedTypes(self::OPTION_KEY_PROJECT_PATH_PATTERN, 'string');
        $resolver->setAllowedTypes(self::OPTION_KEY_VENDOR_PATH_PATTERN, 'string');
        $resolver->setAllowedTypes(self::OPTION_KEY_METHOD_STRING_PATTERN, 'string');
        $resolver->setAllowedTypes(self::OPTION_KEY_PATH_PATTERN, 'string');
    }

    /**
     * @param string $bundle
     *
     * @return array
     */
    protected function getMethodTags($bundle)
    {
        $vendorTags = $this->buildMethodTagsFrom($bundle, $this->options[self::OPTION_KEY_VENDOR_PATH_PATTERN]);
        $projectTags = $this->buildMethodTagsFrom($bundle, $this->options[self::OPTION_KEY_PROJECT_PATH_PATTERN]);

        return array_merge($vendorTags, $projectTags);
    }

    /**
     * @param string $bundle
     * @param string $dir
     *
     * @return array
     */
    private function buildMethodTagsFrom($bundle, $dir)
    {
        $methodTags = [];

        $pathPattern = rtrim($dir, DIRECTORY_SEPARATOR)
            . '/*/' . $this->options[self::OPTION_KEY_APPLICATION] . '/'
            . $bundle . '/'
            . $this->options[self::OPTION_KEY_PATH_PATTERN]
        ;

        try {
            $finder = new Finder();
            /** @var SplFileInfo $file */
            foreach ($finder->files()->in($pathPattern)->depth($this->options[self::OPTION_KEY_DEPTH]) as $file) {
                $className = $this->buildClassNameFromFile($file);

                if (!$this->ignoreClass($className)) {
                    $methodName = $this->buildMethodNameFromClassName($className);
                    $uniqueClassName = $this->buildUniqueNameFromClassName($className);

                    $methodTags[$uniqueClassName] = $this->buildMethodTag($className, $methodName);
                }
            }
        } catch (\InvalidArgumentException $e) {
        }

        return $methodTags;
    }

    /**
     * @param string $className
     *
     * @return bool
     */
    protected function ignoreClass($className)
    {
        return false;
    }

    /**
     * @param SplFileInfo $file
     *
     * @return string
     */
    protected function buildClassNameFromFile(SplFileInfo $file)
    {
        $className = $this->replaceProjectPath($file->getPathname());
        $className = $this->replaceVendorPath($className);
        $className = str_replace(['/', '.php'], ['\\', ''], ltrim($className, '/'));

        return $className;
    }

    /**
     * @param string $className
     *
     * @return string
     */
    protected function buildMethodNameFromClassName($className)
    {
        $classNameParts = explode('\\', $className);
        $classNameParts = array_splice($classNameParts, $this->options[self::OPTION_KEY_CLASS_NAME_PART_LEVEL]);

        return implode($classNameParts);
    }

    /**
     * @param string $className
     *
     * @return string
     */
    protected function buildUniqueNameFromClassName($className)
    {
        $classNameParts = explode('\\', $className);
        $classNameParts = array_splice($classNameParts, 1);

        return implode('\\', $classNameParts);
    }

    /**
     * @param string $className
     * @param string $methodName
     *
     * @return string
     */
    private function buildMethodTag($className, $methodName)
    {
        $search = [
            self::PLACEHOLDER_CLASS_NAME,
            self::PLACEHOLDER_METHOD_NAME,
        ];
        $replace = [
            $className,
            $methodName,
        ];

        return ' * ' . str_replace($search, $replace, $this->options[self::OPTION_KEY_METHOD_STRING_PATTERN]);
    }

    /**
     * @param string $filePathName
     *
     * @return string
     */
    private function replaceProjectPath($filePathName)
    {
        return $this->replacePath($filePathName, $this->options[self::OPTION_KEY_PROJECT_PATH_PATTERN]);
    }

    /**
     * @param string $filePathName
     *
     * @return string
     */
    private function replaceVendorPath($filePathName)
    {
        return $this->replacePath($filePathName, $this->options[self::OPTION_KEY_VENDOR_PATH_PATTERN]);
    }

    /**
     * @param string $filePathName
     * @param string $path
     *
     * @return string
     */
    private function replacePath($filePathName, $path)
    {
        $basePathPattern = str_replace(DIRECTORY_SEPARATOR, '\/', rtrim($path, '/'));
        $search = '/' . str_replace('*', '(.*?)', $basePathPattern) . '/';
        $replace = '';

        $className = preg_replace($search, $replace, $filePathName);

        return $className;
    }

}
