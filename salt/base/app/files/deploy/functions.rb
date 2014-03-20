require 'rubygems'
require 'fileutils'
require 'highline/import'
require 'net/ssh/multi'
require 'optparse'

## Execute command (parallel) on ssh hosts
def multi_ssh_exec(servers, command, options={})
  if servers.is_a? Array then server_array=servers; else server_array=[servers]; end
  Net::SSH::Multi.start(:default_user => "root") do |session|
    server_array.each { |server| session.use server }
    channels = session.exec command
    session.loop
    failed = channels.select { |c| c[:exit_status] != 0 }
    if failed.any?
      failed.each { |f| puts "[#{f[:host]}] FAILED!" } unless options[:dont_display_failed]
      return false
    end
    return true
  end
end

## Execute command (parralel) on ssh hosts. Throw exception any command or host failed
def multi_ssh_exec!(servers, command)
  if !multi_ssh_exec(servers, command)
    puts red "Command failed on one or more servers. Aborting."
    exit 1
  end
  return true
end

## Execute comands locally, in parallel
def multi_exec(commands)
  commands.each { |command| exec (command) if fork.nil? }
  out = Process.waitall
  if out.select { |pid,status| status!=0 }.any?
    return false
  end
  return true
end

# Execute commands, locally, in parallel. Throw exception if any of them failed.
def multi_exec!(commands)
  if !multi_exec(commands)
    puts red "Command failed. Aborting."
    exit 1
  end
  return true
end

# Parser for commandline parameters
def parse_commandline_parameters
  $parameters={}
  $opt_parser = OptionParser.new do |opt|
    opt.banner = "Usage: deploy.rb <command> [OPTIONS]"
    opt.separator  ""
    opt.separator  "Commands"
    opt.separator  "  deploy - perform deployment"
    opt.separator  ""
    opt.separator  "Options"

    opt.on("-e","--environment ENVIRONMENT","Environment to deploy") do |environment|
      $parameters[:environment] = environment
    end

    opt.on("-s","--scmpath PATH","Path in SCM (e.g. trunk, branches/my_branch, tags/go_live_1)") do |scmpath|
      $parameters[:scmpath] = scmpath
    end

    opt.on("-r","--reindex","Force reindexing") do
      $parameters[:reindex] = true
    end

    opt.on("-n","--no-reindex","Do not reindex") do
      $parameters[:reindex] = false
    end

    opt.on("-v","--verbose","Switch on verbose mode") do
      $parameters[:verbose] = true
    end

    opt.on("-h","--help","Show help") do
      puts $opt_parser
      exit
    end

    # Parser for custom options
    $project_options.select {|o| o.has_key? :cmdline }.each do |option|
      option[:cli_options] = option[:options] unless option.has_key? :cli_options
      option[:value] = option[:cli_options][1] || ''
      opt.on(option[:cmdline], option[:question]) do
        option[:value] = option[:cli_options].first
      end
    end
  end
  $opt_parser.parse!
end

# Execute SVN with given args, passing credentials from configfile
def svn(args)
  return `svn --username=#{$svn_user} --password=#{$svn_password} --no-auth-cache --non-interactive --trust-server-cert #{args}`
end

# Execute SVNSYNC with given args, passing credentials from configfile
def svnsync(args)
  return `svnsync --username=#{$svn_user} --password=#{$svn_password} --no-auth-cache --non-interactive --trust-server-cert #{args}`
end

# SVN helpers
def svn_get_revision(url)
  return (svn "info #{url}").map(&:split).select{ |i| i[0]=="Revision:"}.flatten[1]
end

# GIT helpers
def git_list_tags
  return `git --git-dir #{$git_path}/.git tag -l | sed -e 's/\*//g' -e 's/^ *//g'`
end
def git_list_branches
  return `git --git-dir #{$git_path}/.git branch -r | grep -v HEAD | sed -e 's/^[ ]*\//g' -e 's/origin[/]//g'`
end
def git_pull
  return `cd #{$git_path}/ && git checkout -q master && git pull --all --tags -q --force`
end
def git_prune
  return `git --git-dir=#{$git_path}/.git remote prune origin`
end
def git_get_revision
  return `git --git-dir=#{$git_path}/.git rev-parse HEAD`.chomp
end

# Show menu with all items from argument array, return choosen array element
def choose_item_from_array(prompt, items)
  puts ""
  choose do |menu|
    menu.prompt = prompt
    items.each { |item| menu.choice(item) { |choice| return choice }}
  end
end

## Ask for project-specific configuration options (if any)
# { :question => "Do you want to reload solr cores?", :options => %w(yes no), :variable => "reload_solr_cores" },
def ask_project_options
  $project_options.select{ |o| (o.has_key? :ask_question) && o[:ask_question] && ((!o.has_key? :value) or (o[:value].empty?))}.each do |option|
    option[:value] = choose_item_from_array(option[:question].strip + ": ", option[:options])
  end
end

# Return string with current time, for directory namings
def current_time_dirname
  return Time.new().strftime('%Y%m%d-%H%M%S')
end

# Create directory and change ownership to www-data
def mkdir(dir)
  system "install -d -o #{$www_user} -g #{$www_group} #{dir}"
end
def mkdir_if_missing(dir)
  if !File.directory? dir
    puts "### Creating directory: #{dir}"
    mkdir dir
  end
end

# Get the login name of current user
def get_current_user
  return ENV['SUDO_USER'] || `whoami`.strip
end

# Color support for console
def colorize(text, color_code)
  "#{color_code}#{text}\033[0m"
end

# Helpers for colorized output
def red(text); colorize(text, "\033[31m"); end
def yellow(text); colorize(text, "\033[33m"); end
def green(text); colorize(text, "\033[32m"); end
def put_status(text); puts(yellow("### "+text)); end
def put_error(text); puts(red("!!! "+text)); end
