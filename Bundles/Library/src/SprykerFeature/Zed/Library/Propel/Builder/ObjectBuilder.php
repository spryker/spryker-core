<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Library\Propel\Builder;

use Propel\Generator\Model\Column;

class ObjectBuilder extends \Propel\Generator\Builder\Om\ObjectBuilder
{

    /**
     * Change default propel behaviour
     *
     * Adds setter method for boolean columns.
     * @param string &$script The script will be modified in this method.
     * @param Column $col     The current column.
     * @see parent::addColumnMutators()
     */
    protected function addBooleanMutator(&$script, Column $col)
    {
        $clo = $col->getLowercasedName();

        $this->addBooleanMutatorComment($script, $col);
        $this->addMutatorOpenOpen($script, $col);
        $this->addMutatorOpenBody($script, $col);

        $allowNullValues = ($col->getAttribute('required', 'true') === 'true') ? 'false' : 'true';

        $script .= "
        if (\$v !== null) {
            if (is_string(\$v)) {
                \$v = in_array(strtolower(\$v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                \$v = (boolean) \$v;
            }
        }

        \$allowNullValues = $allowNullValues;

        if (is_null(\$v) && !\$allowNullValues) {
            return \$this;
        }

        if (\$this->$clo !== \$v) {
            \$this->$clo = \$v;
            \$this->modifiedColumns[".$this->getColumnConstant($col)."] = true;
        }
";
        $this->addMutatorClose($script, $col);
    }

}
