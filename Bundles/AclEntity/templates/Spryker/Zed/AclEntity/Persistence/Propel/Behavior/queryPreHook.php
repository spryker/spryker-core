// phpcs:ignoreFile
/**
 * @var string|null $action
 */
/** @var \Spryker\Zed\AclEntity\Business\AclEntityFacadeInterface $aclEntityFacade */
$aclEntityFacade = \Spryker\Zed\Kernel\Locator::getInstance()->aclEntity()->facade();
if ($aclEntityFacade->isActive() && !$this->isSegmentQuery()) {
    $aclEntityMetadataConfigRequestTransfer = new \Generated\Shared\Transfer\AclEntityMetadataConfigRequestTransfer();
    $aclEntityMetadataConfigRequestTransfer->setModelName($this->getModelName());
    $aclEntityConfigTransfer = $aclEntityFacade->getAclEntityMetadataConfig(true, $aclEntityMetadataConfigRequestTransfer);
    if (!in_array($this->getModelName(), $aclEntityConfigTransfer->getAclEntityAllowList())) {
        $this->getPersistenceFactory()
            ->createAclQueryDirector($aclEntityConfigTransfer)
            ->applyAclRuleOn<?php echo $action ?? ''; ?>Query($this);
    }
}
