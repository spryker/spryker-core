/** @var \Spryker\Zed\AclEntity\Business\AclEntityFacadeInterface $aclEntityFacade */
$aclEntityFacade = \Spryker\Zed\Kernel\Locator::getInstance()->aclEntity()->facade();
if ($aclEntityFacade->isActive() && !$this->isSegmentQuery()) {
    $aclEntityConfigTransfer = $aclEntityFacade->getAclEntityMetadataConfig();
    if (!in_array($this->getModelName(), $aclEntityConfigTransfer->getAclEntityAllowList())) {
        $this->getPersistenceFactory()
            ->createAclQueryDirector($aclEntityConfigTransfer->getAclEntityMetadataCollection())
            ->applyAclRuleOn<?php echo $action ?? ''; ?>Query($this);
    }
}
