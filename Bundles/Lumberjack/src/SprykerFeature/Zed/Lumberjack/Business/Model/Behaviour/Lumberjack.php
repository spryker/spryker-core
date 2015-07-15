<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Lumberjack\Business\Model\Behaviour;

use SprykerFeature\Zed\Lumberjack\Business\LumberjackSettings;
use Propel\Generator\Model\Behavior;

class Lumberjack extends Behavior
{

    /**
     * @return array
     */
    protected function getEntityBlacklist()
    {
        $settings = new LumberjackSettings();

        return $settings->getEntityBlacklist();
    }

    /**
     * @param $builder
     *
     * @return string
     */
    public function postSave($builder)
    {
        $script = '
if ($affectedRows > 0) {
    $blacklistedEntities = ' . var_export($this->getEntityBlacklist(), true) . ';

    if (!in_array(get_class($this), $blacklistedEntities)) {
        $id = $this->getPrimaryKey();
        $id = is_null($id) ? 0 : $id;

        // Fix an issue when having multiple primary keys
        if (is_array($id)) {
            $id = implode(\',\', $id);
        }

        $lumberjack = \SprykerFeature\Shared\Lumberjack\Code\Lumberjack::getInstance();
        $lumberjack->addField("entityData", $this->toArray());
        $lumberjack->addField("entity", get_class($this));
        $lumberjack->addField("entityId", $id);
        $lumberjack->addField("affectedRows", $affectedRows);

        $authIdentity = SprykerFeature\Zed\Auth\Business\Model\Auth::getInstance()->getIdentity();
        if (isset($authIdentity) && $authIdentity instanceof \SprykerFeature\Zed\Acl\Persistence\Propel\SpyAclUser) {
            $lumberjack->addField("aclUserName", $authIdentity->getUsername());
        }

        $subType = $isInsert ? "insert" : "update";
        $lumberjack->send(\SprykerFeature\Shared\Lumberjack\Code\Log\Types::SAVE, get_class($this) . " id:" . $id . " " . $subType, $subType);
    }
}
';

        return $script;
    }

}
