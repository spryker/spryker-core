<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\Kernel\IdeAutoCompletion;

use SprykerEngine\Zed\Kernel\BundleNameFinder;
use SprykerEngine\Zed\Kernel\IdeAutoCompletion\MethodTagBuilder\MethodTagBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class AbstractIdeAutoCompletionGenerator
{

    const GENERATOR_NAME = 'IDE bundle auto completion class';

    const INTERFACE_NAME = 'AutoCompletion';

    const OPTION_KEY_METHOD_STRING_PATTERN = 'method string pattern';
    const OPTION_KEY_APPLICATION = 'application';
    const OPTION_KEY_NAMESPACE = 'namespace';
    const OPTION_KEY_BUNDLE_NAME_FINDER = 'bundle name finder';
    const OPTION_KEY_INTERFACE_NAME = 'interface name';
    const OPTION_KEY_LOCATION_DIR = 'location dir';
    const OPTION_KEY_HAS_LAYERS = 'has layers';

    const APPLICATION = 'Zed';

    const PLACEHOLDER_BUNDLE = '{{bundle}}';
    const PLACEHOLDER_METHOD_NAME = '{{methodName}}';
    const PLACEHOLDER_NAMESPACE = '{{namespace}}';
    const PLACEHOLDER_INTERFACE_NAME = '{{interfaceName}}';
    const PLACEHOLDER_METHOD_LINES = '{{methodLines}}';
    const PLACEHOLDER_APPLICATION = '{{application}}';

    /**
     * @var array
     */
    protected $options;

    /**
     * @var MethodTagBuilderInterface[]
     */
    protected $methodTagBuilder = [];

    /**
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        $resolver = new OptionsResolver();
        $this->configureOptions($resolver);

        $this->options = $resolver->resolve($options);
        $this->makeDirIfNotExists();
    }

    /**
     * @param OptionsResolver $resolver
     */
    protected function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            self::OPTION_KEY_BUNDLE_NAME_FINDER => new BundleNameFinder(),
            self::OPTION_KEY_APPLICATION => self::APPLICATION,
            self::OPTION_KEY_INTERFACE_NAME => self::INTERFACE_NAME,
        ]);

        $resolver->setRequired([
            self::OPTION_KEY_LOCATION_DIR,
            self::OPTION_KEY_NAMESPACE,
            self::OPTION_KEY_INTERFACE_NAME,
        ]);

        $resolver->setAllowedTypes(self::OPTION_KEY_LOCATION_DIR, 'string');
        $resolver->setAllowedTypes(self::OPTION_KEY_NAMESPACE, 'string');
        $resolver->setAllowedTypes(self::OPTION_KEY_INTERFACE_NAME, 'string');
    }

    /**
     * @param null $output
     */
    abstract public function create($output = null);

    protected function makeDirIfNotExists()
    {
        if (!is_dir($this->options[self::OPTION_KEY_LOCATION_DIR])) {
            mkdir($this->options[self::OPTION_KEY_LOCATION_DIR], 0777, true);
        }
    }

    /**
     * @param MethodTagBuilderInterface $methodTagBuilder
     *
     * @return $this
     */
    public function addMethodTagBuilder(MethodTagBuilderInterface $methodTagBuilder)
    {
        $this->methodTagBuilder[] = $methodTagBuilder;

        return $this;
    }

    /**
     * @param string $bundle
     *
     * @return array
     */
    protected function getMethodTagsByBundle($bundle)
    {
        $methodTags = [];

        foreach ($this->methodTagBuilder as $methodTagBuilder) {
            $methodTags = $methodTagBuilder->buildMethodTags($bundle, $methodTags);
        }

        return $methodTags;
    }

    /**
     * @param string $namespace
     *
     * @return string
     */
    protected function getBaseFile($namespace)
    {
        return '<?php' . PHP_EOL . PHP_EOL . 'namespace ' . $namespace . ';' . PHP_EOL . PHP_EOL;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return self::GENERATOR_NAME;
    }

    /**
     * @return array
     */
    protected function getBundles()
    {
        /** @var BundleNameFinder $bundleNameFinder */
        $bundleNameFinder = $this->options[self::OPTION_KEY_BUNDLE_NAME_FINDER];

        return $bundleNameFinder->getBundleNames();
    }

    /**
     * @param string $fileContent
     */
    protected function saveFileContent($fileContent)
    {
        $pathToFile = $this->options[self::OPTION_KEY_LOCATION_DIR];
        $fileName = $this->options[self::OPTION_KEY_INTERFACE_NAME] . '.php';

        file_put_contents($pathToFile . $fileName, $fileContent);
    }

}
