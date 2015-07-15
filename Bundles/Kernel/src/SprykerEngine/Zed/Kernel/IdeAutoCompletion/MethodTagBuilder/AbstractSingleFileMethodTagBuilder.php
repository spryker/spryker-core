<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\Kernel\IdeAutoCompletion\MethodTagBuilder;

use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class AbstractSingleFileMethodTagBuilder implements MethodTagBuilderInterface
{

    const OPTION_KEY_METHOD_STRING_PATTERN = 'method string pattern';
    const OPTION_KEY_PATH_PATTERN = 'path pattern';
    const OPTION_KEY_FILE_NAME_SUFFIX = 'file name suffix';
    const OPTION_KEY_PROJECT_PATH_PATTERN = 'project path pattern';
    const OPTION_KEY_VENDOR_PATH_PATTERN = 'core path pattern';
    const OPTION_KEY_APPLICATION = 'application';
    const OPTION_KEY_NAMESPACE_PATTERN = 'namespace pattern';

    const APPLICATION = 'Zed';

    const PLACEHOLDER_CLASS_NAME = '{{className}}';
    const NAMESPACE_PATTERN = '*';

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
            self::OPTION_KEY_NAMESPACE_PATTERN => self::NAMESPACE_PATTERN,
            self::OPTION_KEY_PROJECT_PATH_PATTERN => APPLICATION_SOURCE_DIR,
            self::OPTION_KEY_VENDOR_PATH_PATTERN => APPLICATION_VENDOR_DIR . '/spryker/spryker/Bundles/*/src',
        ]);

        $resolver->setRequired([
            self::OPTION_KEY_METHOD_STRING_PATTERN,
            self::OPTION_KEY_PATH_PATTERN,
            self::OPTION_KEY_FILE_NAME_SUFFIX,
            self::OPTION_KEY_PROJECT_PATH_PATTERN,
            self::OPTION_KEY_VENDOR_PATH_PATTERN,
            self::OPTION_KEY_APPLICATION,
        ]);

        $resolver->setAllowedTypes(self::OPTION_KEY_METHOD_STRING_PATTERN, 'string');
        $resolver->setAllowedTypes(self::OPTION_KEY_PATH_PATTERN, 'string');
        $resolver->setAllowedTypes(self::OPTION_KEY_FILE_NAME_SUFFIX, 'string');
        $resolver->setAllowedTypes(self::OPTION_KEY_PROJECT_PATH_PATTERN, 'string');
        $resolver->setAllowedTypes(self::OPTION_KEY_VENDOR_PATH_PATTERN, 'string');
        $resolver->setAllowedTypes(self::OPTION_KEY_APPLICATION, 'string');
    }

    /**
     * @param string $bundle
     *
     * @return string
     */
    protected function getMethodTag($bundle)
    {
        $name = $bundle . $this->options[self::OPTION_KEY_FILE_NAME_SUFFIX];
        $pattern = $this->options[self::OPTION_KEY_NAMESPACE_PATTERN] . '/'
            . $this->options[self::OPTION_KEY_APPLICATION] . '/'
            . $bundle . '/'
            . $this->options[self::OPTION_KEY_PATH_PATTERN];

        $file = $this->findByNameAndPattern($bundle, $name, $pattern);

        if ($file instanceof SplFileInfo) {
            return $this->buildMethodTagFromFile(
                $file,
                $this->options[self::OPTION_KEY_METHOD_STRING_PATTERN]
            );
        }

        return false;
    }

    /**
     * @param string $bundle
     * @param string $name
     * @param string $pattern
     *
     * @return bool|SplFileInfo
     */
    protected function findByNameAndPattern($bundle, $name, $pattern)
    {
        $pathPattern = rtrim($this->options[self::OPTION_KEY_PROJECT_PATH_PATTERN], DIRECTORY_SEPARATOR)
            . DIRECTORY_SEPARATOR
            . $pattern;

        $file = $this->getFileIn($name, $pathPattern);

        if (!($file instanceof SplFileInfo)) {
            $pathPattern = rtrim($this->options[self::OPTION_KEY_VENDOR_PATH_PATTERN], DIRECTORY_SEPARATOR)
                . DIRECTORY_SEPARATOR
                . $pattern
            ;

            return $this->getFileIn($name, $pathPattern);
        }

        return $file;
    }

    /**
     * @param string $fileName
     * @param string $path
     *
     * @return bool|SplFileInfo
     */
    private function getFileIn($fileName, $path)
    {
        $finder = new Finder();
        try {
            foreach ($finder->files()->in($path)->name($fileName)->depth(0) as $file) {
                return $file;
            }

            return false;
        } catch (\InvalidArgumentException $e) {
            return false;
        }
    }

    /**
     * @param SplFileInfo $file
     * @param string $methodPattern
     *
     * @return string
     */
    private function buildMethodTagFromFile(SplFileInfo $file, $methodPattern)
    {
        $className = $this->replaceProjectPath($file->getPathname());
        $className = $this->replaceVendorPath($className);
        $className = str_replace(['/', '.php'], ['\\', ''], $className);

        return ' * ' . str_replace(
            self::PLACEHOLDER_CLASS_NAME,
            $className,
            $methodPattern
        );
    }

    /**
     * @param string $filePathName
     *
     * @return string
     */
    private function replaceProjectPath($filePathName)
    {
        return $this->replacePath($filePathName, rtrim($this->options[self::OPTION_KEY_PROJECT_PATH_PATTERN], DIRECTORY_SEPARATOR));
    }

    /**
     * @param string $filePathName
     *
     * @return string
     */
    private function replaceVendorPath($filePathName)
    {
        return $this->replacePath($filePathName, rtrim($this->options[self::OPTION_KEY_VENDOR_PATH_PATTERN], DIRECTORY_SEPARATOR));
    }

    /**
     * @param string $filePathName
     * @param string $path
     *
     * @return string
     */
    private function replacePath($filePathName, $path)
    {
        $basePathPattern = str_replace(DIRECTORY_SEPARATOR, '\/', $path);
        $search = '/' . str_replace('*', '(.*?)', $basePathPattern) . '/';
        $replace = '';

        $className = preg_replace($search, $replace, $filePathName);

        return $className;
    }

}
