<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\Propel\Business\Builder;

use Propel\Generator\Builder\Om\TableMapBuilder as PropelTableMapBuilder;

class TableMapBuilder extends PropelTableMapBuilder
{

    /**
     * Change default propel behaviour
     *
     * @param string $objName
     * @param string $clsName
     *
     * @return string
     */
    public function buildObjectInstanceCreationCode($objName, $clsName)
    {
        $bundle = $this->getBundleName();

        return "
                /** @var \\Generated\\Zed\\Ide\\AutoCompletion \$locator */
                \$locator = \\SprykerEngine\\Zed\\Kernel\\Locator::getInstance();
                $objName = \$locator->"
                    . lcfirst($bundle)
                    . "()->entity"
                    . $this->getObjectClassName() . "();"
        ;
    }

    /**
     * @throws \Exception
     *
     * @return mixed
     */
    protected function getBundleName()
    {
        if (preg_match('/Zed.(.*?).Persistence/', $this->getClasspath(), $matches)) {
            return $matches[1];
        }

        throw new \Exception('Could not extract bundle name!');
    }

    /**
     * Adds the populateObject() method.
     *
     * @param string &$script The script will be modified in this method.
     *
     * @return void
     */
    protected function addPopulateObject(&$script)
    {
        $table = $this->getTable();
        $script .= "
    /**
     * Populates an object of the default type or an object that inherit from the default.
     *
     * @param array  \$row       row returned by DataFetcher->fetch().
     * @param int    \$offset    The 0-based offset for reading from the resultset row.
     * @param string \$indexType The index type of \$row. Mostly DataFetcher->getIndexType().
                                 One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     * @return array           (" . $this->getObjectClassName(). " object, last column rank)
     */
    public static function populateObject(\$row, \$offset = 0, \$indexType = TableMap::TYPE_NUM)
    {
        \$key = {$this->getTableMapClassName()}::getPrimaryKeyHashFromRow(\$row, \$offset, \$indexType);
        if (null !== (\$obj = {$this->getTableMapClassName()}::getInstanceFromPool(\$key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // \$obj->hydrate(\$row, \$offset, true); // rehydrate
            \$col = \$offset + " . $this->getTableMapClass() . "::NUM_HYDRATE_COLUMNS;";
        if ($table->isAbstract()) {
            $script .= "
        } elseif (null == \$key) {
            // empty resultset, probably from a left join
            // since this table is abstract, we can't hydrate an empty object
            \$obj = null;
            \$col = \$offset + " . $this->getTableMapClass() . "::NUM_HYDRATE_COLUMNS;";
        }
        $script .= "
        } else {";
        if (!$table->getChildrenColumn()) {
            $script .= "
            \$cls = " . $this->getTableMapClass() . "::OM_CLASS;";
        } else {
            $script .= "
            \$cls = static::getOMClass(\$row, \$offset, false);";
        }
        $script .= "
            /** @var {$this->getObjectClassName()} \$obj */
            " . $this->buildObjectInstanceCreationCode('$obj', '$cls') . "
            \$col = \$obj->hydrate(\$row, \$offset, false, \$indexType);
            {$this->getTableMapClassName()}::addInstanceToPool(\$obj, \$key);
        }

        return array(\$obj, \$col);
    }
";
    }

}
