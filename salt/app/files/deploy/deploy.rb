#!/usr/bin/env ruby
#
# Project-A Ventures - Yves & Zed 2.0
# 
# Application deployment
# Warning! Modifying this file will disable automatic upgrading of deploy software.
#
# author: Marek Obuchowicz <marek.obuchowicz@project-a.com>
#


###
### Configuration parsing - global variables
###

if File.exists? "config.rb"; path_prefix="./"; else path_prefix="/etc/deploy/"; end

require path_prefix+'config.rb'
require path_prefix+'config_local.rb' if File.exists? path_prefix+'config_local.rb'
require path_prefix+'functions.rb'

$ssh_wrapper_path = "/etc/deploy/ssh_wrapper.sh"
$ssh_keyfile_path = "/etc/deploy/deploy.key"
$scm_type = "svn" if $scm_type.nil?
$deploy_tmp_dir = $deploy_dir + "/tmp"
$deploy_log = $deploy_dir + "/deploy.log"
$release_name = current_time_dirname
$deploy_source_dir = $deploy_tmp_dir + '/' + $release_name

ENV['LANG']=ENV['LC_ALL']=ENV['LC_CTYPE']='C'
mkdir_if_missing $deploy_tmp_dir
if $stores.nil?
  puts red "Multistore: disabled - no stores configured in #{path_prefix}config.rb"
  $exec_foreach_store = $exec_default_store = ''
else
  $exec_foreach_store = "deploy/exec_foreach_store"
  $exec_default_store = "deploy/exec_default_store"
end

###
### SVN
###

if $scm_type == "svn"
  # Initial variable parsing
  if ($svn_path.nil?)
    $use_svnsync = false
    $svn_host = $original_svn_url
  else
    $use_svnsync = true
    $svn_host = "file://localhost" + $svn_path
  end

  def initialize_scm
    if $use_svnsync
      initialize_svnsync_dir unless File.directory? $svn_path
      refresh_svnsync
    end
  end

  def initialize_svnsync_dir
    put_status "Initializing local svnsync copy"
    system "svnadmin create #{$svn_path}"
    system "echo '#!/bin/bash' > #{$svn_path}/hooks/pre-revprop-change"
    system "chmod 755 #{$svn_path}/hooks/pre-revprop-change"
    puts svnsync "init #{$svn_host} #{$original_svn_url}"
  end

  def refresh_svnsync
    put_status "Syncing local svn copy (be patient....)"
    svnsync "--non-interactive sync #{$svn_host}"
  end

  def export_source_from_svn(full_svn_url)
    put_status "Exporting source code from SVN - #{full_svn_url}"
    svn "export #{full_svn_url} #{$deploy_source_dir}"
    if (not $stores.nil?) and (not File.exists? $deploy_source_dir+'/deploy/exec_default_store')
      puts "This release is not compatible with multistore (deploy/exec_default_store not found)"
      puts "Multistore: disabled"
      $exec_foreach_store = $exec_default_store = ''
    end  
  end

  def ask_scm_dir
    if not $parameters[:scmpath].nil?
      value = $parameters[:scmpath]
      puts "Using SVN path: #{value}"
      return value
    end
    if $environment == "production"
      all_tags = ((svn "ls #{$original_svn_url}/tags").split("\n")).map { |dir| 'tags/'+dir }
      selected_tag = choose_item_from_array("Select tag to deploy: ", all_tags.sort.reverse.first(10))
      return selected_tag
    else
      all_branches = ((svn "ls #{$original_svn_url}/branches").split("\n")).map { |dir| 'branches/'+dir }
      selected_branch = choose_item_from_array("Select branch to deploy: ", ["trunk"] + all_branches) 
      if not $svn_no_auto_tags
        if choose_item_from_array("Do you want to create a tag: ", %w(yes no)) == "yes"
          tag_name = Time.now.utc.strftime("%Y-%m-%d__%H-%M")
          puts green "Creating tag #{tag_name} from #{selected_branch}"
          svn "-m 'Auto tag' copy #{$original_svn_url}#{selected_branch} #{$original_svn_url}tags/#{tag_name}"
          refresh_svnsync
          return "tags/" + tag_name
        end
      end
      return selected_branch
    end
  end
  
  def get_scm_changelog(revision)
    svn("log --stop-on-copy --limit 1 #{revision} | tail -n +4 | head -n 1").chomp
  end
end

###
### Git
###

if $scm_type == "git" # If $scm_type is declared in config.rb as "git"
  def initialize_scm
    if (File.exist?($ssh_wrapper_path)) && (File.exists?($ssh_keyfile_path))
      put_status "Enabled SSH wrapper + keyfile for GIT"
      ENV['GIT_SSH'] = $ssh_wrapper_path
    else
      puts red "Error: SSH wrapper (#{$ssh_wrapper_path}) or keyfile (#{$ssh_keyfile_path}) not found!"
      exit 1
    end
    clone_git_repository unless File.directory?($git_path)
    put_status "SCM: git pull (be patient...)"
    git_pull
    git_prune
  end

  def clone_git_repository # If git clone does not exist, clone from $original_git_url defined in config.rb
    put_status "Local Git repository does not exist. Cloning from Git origin"
    result = system "git clone #{$original_git_url} #{$git_path}"
    if !result
      puts red "GIT clone failed! Aborting deployment"
      puts "Please solve the git problem, then retry"
      exit 2
    end
  end

  def ask_scm_dir
    if not $parameters[:scmpath].nil?
      value = $parameters[:scmpath]
      return value
    end

    all_tags = ((git_list_tags).split("\n")).sort.reverse
    all_branches = ((git_list_branches).split("\n"))
    all_branches = ["master"] + (all_branches-["master"]) # Move master to the top 
    if $environment == "production"
      selected_tag = choose_item_from_array("Select tag to deploy: ", all_tags.first(20))
      put_status "SCM: git checkout #{selected_tag}"
      system "cd #{$git_path}/ && git checkout -q -f #{selected_tag}"
      return selected_tag
    else
      branches = all_branches
      branches = all_branches + all_tags.first(10) if (defined?($allow_tags_on_staging)) 
      selected_branch = choose_item_from_array("Select branch to deploy: ", branches)
      return selected_branch
    end
  end

  def export_source_from_git(branch)
    put_status "SCM: git checkout #{branch}"
    result = system "cd #{$git_path}/ && git checkout -q -f #{branch}"
    if !result
      puts red "git checkout #{branch} failed! Aborting deployment"
      puts "Please solve the git problem, then retry"
      exit 3
    end
    system "cd #{$git_path}/ && git merge -q --ff-only origin/#{branch} || echo '--thats fine when deploying tags...'"
    put_status "Copying source files..."
    system "rsync -a --delete --exclude=.git #{$git_path} #{$deploy_source_dir}" 
  end

  def rsync_source_from_git
    put_status "Exporting source code from local Git repository"
    system "rsync -a --delete --exclude=.git #{$git_path} #{$deploy_source_dir}"
  end
  
  # Todo:
  # - print git revision + branch to rev.txt and skype chat
  
  # git --git-dir=/data/deploy/git/.git show v5.0.0 | head -n1
  # git --git-dir=/data/deploy/git/.git show v5.0.0 | head -n1 | cut -d ' ' -f2
end

###
### Configuration of application
###

def create_deploy_vars_file
  tools_hosts = $jobs_hosts || [$tools_host]
  tools_host = tools_hosts[0]
  solr_hosts = $solr_hosts . join ' '
  jobs_hosts = tools_hosts . join ' '

  File.open("#{$deploy_source_dir}/deploy/vars", "w") do |f|
    f.puts "# Deployment configuration variables"
    f.puts "# This file is generated automatically by deploy.rb, do not modify"
    f.puts ""
    f.puts "deploy_source_dir=\"#{$deploy_source_dir}\""
    f.puts "destination_release_dir=\"#{$destination_release_dir}\""
    f.puts "destination_current_dir=\"#{$destination_current_dir}\""
    f.puts "newrelic_api_key=\"#{$newrelic_api_key}\""
    f.puts "revision=\"#{$revision}\""
    f.puts "shared_dir=\"/data/shop/#{$environment}/shared\""
    f.puts "environment=\"#{$environment}\""
    f.puts "verbose=\"#{$parameters[:verbose]}\""
    f.puts "storage_dir=\"#{$storage_dir}/#{$environment}\"" unless $storage_dir.nil?
    f.puts "deploy_user=\"#{get_current_user}\""
    f.puts "stores=\"#{$stores.map { |a| a['store'] }.join " "}\"" unless $stores.nil?
    f.puts "admin_host=\"#{tools_host}\""
    f.puts "dwh_host=\"#{$dwh_host}\"" unless $dwh_host.nil?
    f.puts "scm_path=\"#{$scm_path}\""
    f.puts "solr_hosts=(\"#{solr_hosts}\")"
    f.puts "jobs_hosts=(\"#{jobs_hosts}\")"
    f.puts "jobs_master=\"#{tools_host}\""
    f.puts "changelog=\"#{$changelog}\""
    $project_options.each { |o| f.puts o[:variable] + "=\"" + (o[:value].nil? ? "":o[:value]) + "\"" }
    f.puts ""
    f.puts "export APPLICATION_ENV=\"#{$environment}\""
  end
end

def create_deploy_stores_file
  File.open("#{$deploy_source_dir}/deploy/stores", "w") do |f|
    f.puts "# Deployment stores configuration"
    f.puts "# This file is generated automatically by deploy.rb, do not modify"
    f.puts ""
    val_locales=val_appdomains=val_stores=""
    if ($stores.nil?)
      val_stores = "DE"
      val_locales = "de_DE"
      val_appdomains = "00"
      stores_array_max=0
    else
      $stores.each do |store|
        val_stores += "#{store['store']} "
        val_locales += "#{store['locale']} "
        val_appdomains += "#{store['appdomain']} "
      end
      stores_array_max=($stores.count)-1
    end
    f.puts "stores=(#{val_stores.strip})"
    f.puts "locales=(#{val_locales.strip})"
    f.puts "appdomains=(#{val_appdomains.strip})"
    f.puts "stores_array_max=#{stores_array_max}"
  end
end

def create_rev_txt
  return if $rev_txt_locations.nil?
  $rev_txt_locations.each do |appdir|
    File.open("#{$deploy_source_dir}/#{appdir}/rev.txt", "w") do |f|
      f.puts "Date: "+`date`
      f.puts "Path: #{$scm_path}"
      f.puts "Revision: #{$revision}"
      f.puts "Deployed by: #{get_current_user}"
    end
  end
end

def prepare_code
  put_status "Preparing application code and symlinks..."
  old_dir = Dir.getwd
  Dir.chdir $deploy_source_dir
  if File.exists? "deploy/prepare_code"
    script_name = "deploy/prepare_code"
  else
    script_name = "deploy/create_shared_links"
    puts yellow "Please rename deploy/create_shared_links to deploy/prepare_code  (workaround activated)"
  end
  system "chmod 755 deploy/*"
  result = system "#{$exec_foreach_store} #{$debug} #{script_name}"
  if !result
    puts red "Command failed. Aborting deployment."
    exit 1
  end
  Dir.chdir old_dir
end

def check_configuration
  put_status "Checking hosts ..."
  hosts = $app_hosts || $zed_hosts
  result = multi_ssh_exec!(hosts, "cd #{$destination_release_dir} && su #{$www_user} -c \"#{$exec_foreach_store} #{$debug} deploy/check_configuration\" ")
end

def configure_hosts
  put_status "Configuring hosts...."


  # Version 1.0 uses "configure_host" action to setup solr as well 
  hosts = $app_hosts || $zed_hosts
  result = multi_ssh_exec!(hosts, "cd #{$destination_release_dir} && su #{$www_user} -c \"#{$exec_foreach_store} #{$debug} deploy/configure_host\" ")

  # In 2.0 configuring solr and jenkins is seperate action/file
  hosts = $solr_hosts || []
  result = multi_ssh_exec!(hosts, "cd #{$destination_release_dir} && su #{$www_user} -c \"#{$exec_default_store} #{$debug} deploy/setup_solr\" ")

  #hosts = $jobs_hosts || []
  #result = multi_ssh_exec!(hosts, "cd #{$destination_release_dir} && su #{$www_user} -c \"#{$exec_default_store} #{$debug} deploy/setup_jenkins\" ")
end

def check_for_migrations
  put_status "Checking for migrations"
  hosts = $jobs_hosts || [$tools_host]
  host = hosts[0]
  result = multi_ssh_exec(host, "cd #{$destination_release_dir} && su #{$www_user} -c \"#{$exec_foreach_store} #{$debug} deploy/check_for_migrations\" ", {:dont_display_failed => 1})
  return true # result
end

def perform_migrations
  put_status "Executing migrations"
  hosts = $jobs_hosts || [$tools_host]
  host = hosts[0]
  result = multi_ssh_exec!(host, "cd #{$destination_release_dir} && su #{$www_user} -c \"#{$exec_foreach_store} #{$debug} deploy/perform_migrations\" ")
end

def initialize_database
  put_status "Initializing database"
  hosts = $jobs_hosts || [$tools_host]
  host = hosts[0]
  result = multi_ssh_exec!(host, "cd #{$destination_release_dir} && [ -f deploy/initialize_database ] && su #{$www_user} -c \"#{$exec_foreach_store} #{$debug} deploy/initialize_database\" ")
end

def activate_maintenance
  put_status "Activating maintenance mode"
  hosts = $web_hosts || $frontend_hosts || $zed_hosts
  result = multi_ssh_exec(hosts, "cd #{$destination_release_dir} && su #{$www_user} -c \"#{$exec_default_store} #{$debug} deploy/enable_maintenance\" ")
end

def deactivate_maintenance
  put_status "Dectivating maintenance mode"
  hosts = $web_hosts || $frontend_hosts || $zed_hosts
  result = multi_ssh_exec(hosts, "cd #{$destination_release_dir} && su #{$www_user} -c \"#{$exec_default_store} #{$debug} deploy/disable_maintenance\" ")
end

def activate_cronjobs
  put_status "Activating cronjobs"
  hosts = $jobs_hosts.first || [$tools_host]
  hosts = (hosts + $dwh_host).uniq if $use_dwh

  result = multi_ssh_exec(hosts, "cd #{$destination_release_dir} && #{$exec_default_store} #{$debug} deploy/enable_cronjobs")

  # Legacy - Yves+Zed 1.0
  result = multi_ssh_exec($frontend_hosts, "cd #{$destination_release_dir} && [ -f deploy/enable_local_cronjobs ] && #{$exec_default_store} #{$debug} deploy/enable_local_cronjobs") unless $frontend_hosts.nil?
end

def deactivate_cronjobs
  put_status "Deactivating cronjobs"
  hosts = $jobs_hosts || [$tools_host]
  result = multi_ssh_exec(hosts, "cd #{$destination_release_dir} && #{$exec_default_store} #{$debug} deploy/disable_cronjobs")

  # Legacy - Yves+Zed 1.0
  result = multi_ssh_exec($frontend_hosts, "cd #{$destination_release_dir} && [ -f deploy/disable_local_cronjobs ] && #{$exec_default_store} #{$debug} deploy/disable_local_cronjobs") unless $frontend_hosts.nil?
end 

###
### SOLR and KV-Store
###

def reindex_full
  if ($use_solr) 
    put_status "Reindexing solr..."
    hosts = $jobs_hosts || [$solr_host]
    host = hosts[0]
    result = multi_ssh_exec(host, "cd #{$destination_release_dir} && [ -f deploy/reindex_solr ] && su #{$www_user} -c \"#{$exec_foreach_store} #{$debug} deploy/reindex_solr\" ")
  end

  put_status "Reindexing KV-store..."
  hosts = $jobs_hosts || [$tools_host]
  host = hosts[0]
  if File.exists? "deploy/reindex_memcache"
    script_name = "deploy/reindex_memcache"
  else
    script_name = "deploy/reindex_kv"
    puts yellow "Please rename deploy/reindex_memcache to deploy/reindex_kv  (workaround activated)"
  end
  result = multi_ssh_exec(host, "cd #{$destination_release_dir} && [ -f #{script_name} ] && su #{$www_user} -c \"#{$exec_foreach_store} #{$debug} #{script_name}\" ")

  # Legacy - Yves+Zed 1.0
  result = multi_ssh_exec($frontend_hosts, "cd #{$destination_release_dir} && [ -f deploy/reindex_memcache ] && su #{$www_user} -c \"#{$exec_foreach_store} #{$debug} deploy/reindex_memcache\" ") unless $frontend_hosts.nil?
end

def ask_reindex
  if not $parameters[:reindex].nil?
    value = $parameters[:reindex]
    puts "Perform full reindex: #{value}"
    return value
  end
  if choose_item_from_array("Perform FULL solr/memcache import? ", %w(yes no)) == "yes"
    if $environment == "production"
      puts red "Warning, this will cause downtime while performing reindex." 	
      return false if choose_item_from_array("Proceed anyway? ", %w(yes no)) != "yes"
    end
    return true
  end
  return false
end

###
### Source code distribution
###

def create_tarball(tarfile)
  put_status "Creating tarball: #{tarfile}"
  system "chown -R #{$www_user}:#{$www_group} #{$deploy_tmp_dir}"
  system "tar --dir #{$deploy_tmp_dir} -cpf #{tarfile} #{$release_name}"
  FileUtils.rm_rf "#{$deploy_tmp_dir}/#{$release_name}"
end

def distribute_tarball(tarfile)
  put_status "Distributing tarball"
  destination_tar_dir = $destination_dir + '/' + $environment + '/releases'
  hosts = $app_hosts || $zed_hosts
  multi_ssh_exec(hosts, "[ -d #{destination_tar_dir} ] || mkdir #{destination_tar_dir}")
  rsync_commands = []
  hosts.each do |host|
    rsync_commands.push "rsync -ra #{tarfile} #{$rsync_user}@#{host}:#{destination_tar_dir}/#{$environment}.tar"
  end
  multi_exec! rsync_commands

  put_status "Uncompressing tarballs"
  multi_ssh_exec(hosts, "rm -rf #{$destination_release_dir}")
  multi_ssh_exec!(hosts, "cd #{destination_tar_dir} && tar xf #{$environment}.tar")
  multi_ssh_exec!(hosts, "rm -f #{destination_tar_dir}/#{$environment}.tar")
end

def activate_release
  put_status "Activating new release"
  restart_fpm_command = "/etc/init.d/php5-fpm restart"
  web_hosts = $web_hosts || $zed_hosts
  app_hosts = $app_hosts || $zed_hosts
  app_only_hosts = app_hosts - web_hosts
  multi_ssh_exec!(web_hosts, "ln -bns #{$destination_release_dir} #{$destination_current_dir} && #{restart_fpm_command}")
  multi_ssh_exec!(app_only_hosts, "ln -bns #{$destination_release_dir} #{$destination_current_dir}")
end

def create_lockfile
  if File.exists? $lockfile
    puts red "! Warning: lockfile exists. Is another deployment in progress?"
    puts "To remove lockfile, execute:"
    puts "sudo rm -f #{$lockfile}"
    exit 1
  end
  FileUtils.touch $lockfile
end

def remove_lockfile
  FileUtils.rm_f $lockfile
end

###
### Notifications
###

def send_notifications_after
  put_status "Sending notifications after deployment"
  hosts = $jobs_hosts || [$tools_host]
  host = hosts[0]
  result = multi_ssh_exec(host, "cd #{$destination_release_dir} && [ -f deploy/send_notifications ] && deploy/send_notifications", {:dont_display_failed => 1})
  result = multi_ssh_exec(host, "cd #{$destination_release_dir} && [ -f deploy/send_notifications_after ] && deploy/send_notifications_after", {:dont_display_failed => 1})
end

###
### Menus
###

def display_main_menu
  case ARGV[0]
    when "deploy"
      $interactive = false
      put_status "Non-interactive: deploy"
      perform_deploy
    when "", nil
      choose do |menu|
        menu.prompt = "Choose an action: "
        menu.choice(:deploy, "Deploy application") { perform_deploy }
      end
    else
      put_error "Unknown command: #{ARGV[0]}"
      puts $opt_parser
      exit 1
  end
end

def ask_environment
  if not $parameters[:environment].nil?
    value = $parameters[:environment]
    puts "Using environment: #{value}"
    if $environments.index(value).nil?
      put_error "Unknown environment on this host: #{value}"
      exit 1
    end
    return value
  end
  choose_item_from_array("Deploy to environment: ", $environments)
end

###
### Main actions
###

def perform_deploy
  if (($project_options.select { |opt| opt[:variable] == "debug" }.first)[:value] == "true") 
    puts yellow "### Enabled debugging for project task scripts"
    $debug = "bash -x"
  end
  
  initialize_scm
  $environment = ask_environment

  $destination_release_dir = $destination_dir + '/' + $environment + '/releases/' + $release_name
  $destination_current_dir = $destination_dir + '/' + $environment + '/current'
  tarfile = "#{$deploy_tmp_dir}/#{$environment}.tar"
  $lockfile = $deploy_dir + '/.lock.' + $environment

  $scm_path = ask_scm_dir
  create_lockfile
  perform_full_import = ask_reindex
  ask_project_options

  if $scm_type == "svn"
    export_source_from_svn ($svn_host + $scm_path)
    $revision = svn_get_revision($svn_host + $scm_path)
    $changelog = get_scm_changelog($svn_host + $scm_path)
  end
  if $scm_type == "git"
    export_source_from_git $scm_path
    $revision = git_get_revision
    # TODO: $changelog=
  end

  create_deploy_vars_file
  create_deploy_stores_file
  create_rev_txt
  prepare_code
  create_tarball tarfile
  distribute_tarball tarfile
  check_configuration
  configure_hosts
  have_migrations = check_for_migrations
  show_maintenance = true if have_migrations
  deactivate_cronjobs
  activate_maintenance if show_maintenance
  perform_migrations if have_migrations
  deactivate_maintenance
  initialize_database
  activate_release
  reindex_full if perform_full_import
  activate_cronjobs
  send_notifications_after
  remove_lockfile
end

###
### Main
###

puts yellow "Project-A Ventures - Yves & Zed - #{$project_name}"
parse_commandline_parameters
display_main_menu
