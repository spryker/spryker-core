# This file is maintained by Salt!

ZSH=$HOME/.oh-my-zsh
ZSH_THEME="robbyrussell"
plugins=(git composer)
source $ZSH/oh-my-zsh.sh
export PS1='%n@%m ${ret_status}%{$fg_bold[green]%}%p %{$fg[cyan]%}%c %{$fg_bold[blue]%}$(git_prompt_info)%{$fg_bold[blue]%} % %{$reset_color%}'
alias composer='php composer.phar'
alias ci='php composer.phar install'
alias cu='php composer.phar update'

codeception () {
    pushd /data/shop/development/current
    APPLICATION_ENV=testing APPLICATION_STORE=DE vendor/bin/codecept run $*
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