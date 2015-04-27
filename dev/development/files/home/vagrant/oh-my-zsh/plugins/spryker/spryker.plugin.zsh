alias composer='php -d xdebug.remote_enable=0 composer.phar'
alias ci='php -d xdebug.remote_enable=0 composer.phar install'
alias cu='php -d xdebug.remote_enable=0 composer.phar update'

codecept () {
    pushd /data/shop/development/current
    APPLICATION_ENV=development APPLICATION_STORE=DE vendor/bin/codecept run $*
    popd
}

debug-console () {
    pushd /data/shop/development/current
    XDEBUG_CONFIG="remote_host=10.10.0.1" PHP_IDE_CONFIG="serverName=zed.spryker.dev" vendor/bin/console $*
    popd
}

console () {
    pushd /data/shop/development/current
    vendor/bin/console $*
    popd
}
