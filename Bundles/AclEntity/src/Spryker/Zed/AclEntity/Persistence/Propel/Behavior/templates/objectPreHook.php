/** @var \Spryker\Zed\AclEntity\Business\AclEntityFacadeInterface $aclEntityFacade */
$aclEntityFacade = \Spryker\Zed\Kernel\Locator::getInstance()->aclEntity()->facade();
if ($aclEntityFacade->isActive()) {
    $aclEntityMetadataConfigTransfer = $aclEntityFacade->getAclEntityMetadataConfig();
    if (!in_array(get_class($this), $aclEntityMetadataConfigTransfer->getAclEntityWhitelist())) {
        $this->getPersistenceFactory()
            ->createAclQueryDirector($aclEntityMetadataConfigTransfer->getAclEntityMetadataCollection())
            ->inspect<?php echo $action ?? ''; ?>($this);
    }
}
