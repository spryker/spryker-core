<?php

/**
 * This file is part of the Propel package - modified by Spryker Systems GmbH.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with the source code of the extended class.
 *
 * @license MIT License
 * @see https://github.com/propelorm/Propel2
 */

namespace Spryker\Zed\PropelOrm\Business\Builder;

use Propel\Generator\Builder\Om\ExtensionQueryBuilder as PropelExtensionQueryObjectBuilder;

class ExtensionQueryBuilder extends PropelExtensionQueryObjectBuilder
{
    public const POSITION_OF_ORM = 0;
    public const POSITION_OF_BASE = 4;

    /**
     * @param string|null $ignoredNamespace the ignored namespace
     *
     * @return string
     */
    public function getUseStatements($ignoredNamespace = null): string
    {
        $script = '';
        $declaredClasses = $this->declaredClasses;

        unset($declaredClasses[$ignoredNamespace]);
        ksort($declaredClasses);

        foreach ($declaredClasses as $namespace => $classes) {
            asort($classes);
            $script = $this->addUseStatementsToScript($classes, $namespace, $script);
        }

        return $script;
    }

    /**
     * @param string[] $classes
     * @param string $namespace
     * @param string $script
     *
     * @return string
     */
    protected function addUseStatementsToScript(array $classes, string $namespace, string $script): string
    {
        foreach ($classes as $class => $alias) {
            if ($this->isOwnClass($namespace, $class)) {
                continue;
            }

            $script = $this->addUseStatementToScript($namespace, $class, $alias, $script);
        }

        return $script;
    }

    /**
     * @param string $namespace
     * @param string $class
     * @param string $alias
     * @param string $script
     *
     * @return string
     */
    protected function addUseStatementToScript(string $namespace, string $class, string $alias, string $script): string
    {
        if ($this->isBaseClass($namespace, $class) && $this->sprykerBaseClassExists($class)) {
            $class = 'Abstract' . $class;
            $namespace = $this->getSprykerNamespace();
        }

        if ($class === $alias) {
            return $this->addUseStatementWithoutAlias($namespace, $class, $script);
        }

        return $this->addUseStatementWithAlias($namespace, $class, $alias, $script);
    }

    /**
     * @param string $namespace
     * @param string $class
     * @param string $script
     *
     * @return string
     */
    protected function addUseStatementWithoutAlias(string $namespace, string $class, string $script): string
    {
        $script .= sprintf("use %s\\%s;
", $namespace, $class);

        return $script;
    }

    /**
     * @param string $namespace
     * @param string $class
     * @param string $alias
     * @param string $script
     *
     * @return string
     */
    protected function addUseStatementWithAlias(string $namespace, string $class, string $alias, string $script): string
    {
        $script .= sprintf("use %s\\%s as %s;
", $namespace, $class, $alias);

        return $script;
    }

    /**
     * @param string $namespace
     * @param string $class
     *
     * @return bool
     */
    protected function isOwnClass(string $namespace, string $class): bool
    {
        return ($class === $this->getUnqualifiedClassName() && $namespace === $this->getNamespace());
    }

    /**
     * @param string $namespace
     * @param string $class
     *
     * @return bool
     */
    protected function isBaseClass(string $namespace, string $class): bool
    {
        return ($namespace === $this->getNamespace() . '\\Base' && $class == $this->getUnqualifiedClassName());
    }

    /**
     * @param string $class
     *
     * @return bool
     */
    protected function sprykerBaseClassExists(string $class): bool
    {
        $sprykerNamespace = $this->getSprykerNamespace();
        $sprykerClassName = $this->getSprykerClassName($sprykerNamespace, $class);

        if (class_exists($sprykerClassName)) {
            return true;
        }

        return false;
    }

    /**
     * @return string
     */
    protected function getSprykerNamespace(): string
    {
        $namespaceFragments = explode('\\', $this->getNamespace());
        $namespaceFragments[static::POSITION_OF_ORM] = 'Spryker';
        $namespaceFragments[static::POSITION_OF_BASE] = 'Propel';

        return implode('\\', $namespaceFragments);
    }

    /**
     * @param string $sprykerNamespace
     * @param string $class
     *
     * @return string
     */
    protected function getSprykerClassName(string $sprykerNamespace, string $class): string
    {
        return $sprykerNamespace . '\\Abstract' . $class;
    }
}
