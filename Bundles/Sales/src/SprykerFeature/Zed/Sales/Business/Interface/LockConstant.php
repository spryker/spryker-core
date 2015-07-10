<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */
interface SprykerFeature_Zed_Sales_Business_Interface_LockConstant
{

    const LOCK_RESOURCE_NAME = 'OrderStateMachine';
    const LOCK_EXPIRE_INTERVAL_AS_STRING = '30 minutes';
    const TRY_IS_LOCKED_COUNT = 10;

}
