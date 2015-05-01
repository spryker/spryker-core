<?php
/**
 * @author Marco Roßdeutscher <marco.rossdeutscher@project-a.com>
 * @version 15.06.12 15:09
 */
interface SprykerFeature_Zed_Sales_Business_Interface_LockConstant
{
    const LOCK_RESOURCE_NAME = 'OrderStateMachine';
    const LOCK_EXPIRE_INTERVAL_AS_STRING = '30 minutes';
    const TRY_IS_LOCKED_COUNT = 10;
}
