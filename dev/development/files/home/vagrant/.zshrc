# This file is maintained by Salt!

ZSH=$HOME/.oh-my-zsh
ZSH_THEME="robbyrussell"
plugins=(git composer)
source $ZSH/oh-my-zsh.sh
export PS1='%n@%m ${ret_status}%{$fg_bold[green]%}%p %{$fg[cyan]%}%c %{$fg_bold[blue]%}$(git_prompt_info)%{$fg_bold[blue]%} % %{$reset_color%}'
alias composer='php composer.phar'
alias ci='php composer.phar install'
alias cu='php composer.phar update'
