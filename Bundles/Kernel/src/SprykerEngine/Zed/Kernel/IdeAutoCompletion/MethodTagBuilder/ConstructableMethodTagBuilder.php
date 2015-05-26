<?php

namespace SprykerEngine\Zed\Kernel\IdeAutoCompletion\MethodTagBuilder;

use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ConstructableMethodTagBuilder extends AbstractMultiFileMethodTagBuilder
{

    const METHOD_STRING_PATTERN = '@method \{{className}} create{{methodName}}';

    /**
     * @param OptionsResolver $resolver
     */
    protected function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
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

        return false;
    }

    /**
     * @param $className
     *
     * @return string
     */
    protected function buildMethodNameFromClassName($className)
    {
        $methodName = parent::buildMethodNameFromClassName($className);

        $constructorData = $this->createConstructor($className);
        if ($constructorData) {
            $methodName .= '(' . $constructorData . ')';
        } else {
            $methodName .= '()';
        }

        return $methodName;
    }

    /**
     * @param $className
     * @return array
     * @throws \ErrorException
     */
    protected function createConstructor($className)
    {
//        if (!class_exists($className)) {
//            return false;
//        }
        try {
            $class = new \ReflectionClass($className);
//            if (is_null($class) || $class->isAbstract() || $class->isInterface() || $class->isTrait()) {
//                return false;
//            }

            $constructor = $class->getConstructor();
            /* @var $constructor \ReflectionMethod */
            if (isset($constructor)) {
                $parameters = $constructor->getParameters();
                if (!empty($parameters)) {
                    $constructorData = [];
                    /* @var $param \ReflectionParameter */
                    foreach ($parameters as $param) {
                        $paramData = '';
                        if ($param->isArray()) {
                            $paramData .= 'array ';
                        }

                        if ($param->getClass()) {
                            $paramData .= '\\' . $param->getClass()->getName() . ' ';
                        }

                        $paramData .= '$' . $param->getName();

                        if ($param->isOptional() && !$constructor->isInternal()) {
                            $paramData .= ' = ';
                            $defaultValue = $param->getDefaultValue();
                            if (is_null($defaultValue)) {
                                $paramData .= 'null';
                            } elseif (is_bool($defaultValue)) {
                                $paramData .= ($defaultValue) ? 'true' : 'false';
                            } elseif (is_array($defaultValue)) {
                                $paramData .= '[]';
                            } elseif (is_string($defaultValue)) {
                                $paramData .= '\'' . addslashes($defaultValue) . '\'';
                            } else {
                                $paramData .= $defaultValue;
                            }
                        }

                        $constructorData[] = $paramData;
                    }

                    return implode(', ', $constructorData);
                }
            }
        } catch (\Exception $e) {
            echo $e->getMessage() . PHP_EOL;
            echo '<pre>' . PHP_EOL . \Symfony\Component\VarDumper\VarDumper::dump($className) . PHP_EOL . 'Line: ' . __LINE__ . PHP_EOL . 'File: ' . __FILE__ . die();
        }

        return false;
    }

}
