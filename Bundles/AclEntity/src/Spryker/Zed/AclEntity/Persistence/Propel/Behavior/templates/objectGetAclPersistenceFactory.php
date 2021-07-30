// phpcs:ignoreFile
/**
 * @return \Spryker\Zed\AclEntity\Persistence\AclEntityPersistenceFactory
 */
protected function getPersistenceFactory(): \Spryker\Zed\AclEntity\Persistence\AclEntityPersistenceFactory
{
    return (new \Spryker\Zed\Kernel\ClassResolver\Persistence\PersistenceFactoryResolver())
        ->resolve(\Spryker\Zed\AclEntity\Persistence\AclEntityPersistenceFactory::class);
}
