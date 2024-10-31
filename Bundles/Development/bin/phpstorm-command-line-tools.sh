#!/bin/bash

# The directory/file selected in PhpStorm ($FileName$) needs to be transformed to the widly used "module" parameter structure (first match applies):
# 1. Items in vendor direcotry are resolved to Core
#    a. items ending as "/spryker" OR "/spryker/Bundles": are resolved to "Spryker.all"
#    b. items ending as "/spryker-shop" OR "/spryker-shop/Bundles": are resolved to "SprykerShop.all"
#    c. items ending as "/spryker-eco" OR "/spryker-eco/Bundles": are resolved to "SprykerEco.all"
#    d. items containing "/spryker/spryker/" AND 1 more item: are resolved as "Spryker.{selected camelcased name}" (nonsplit modules)
#    d. items containing "/spryker-shop/" AND 1 more item: are resolved as "SprykerShop.{selected camelcased name}"
#    d. items containing "/spryker-eco/" AND 1 more item: are resolved as "SprykerEco.{selected camelcased name}"
#    d. items containing "/spryker/" AND 1 more item: are resolved as "Spryker.{selected camelcased name}" (standalone modules)
# 2. If item is the root "src" directory OR a 1st level directory in "src" OR a 2nd level directory in "src": are resolved to "all" (project)
# 3. Item: is resolved to "{selected camelcased name}" (project)
TARGET_MODULE_EXPRESSION=$(cat <<'EOF'
    TARGET_MODULE=$(if [[ $FileDirRelativeToProjectRoot$ == vendor* ]]; then if [[ $FileDirRelativeToProjectRoot$ == */spryker || $FileDirRelativeToProjectRoot$ == *spryker/Bundles ]]; then echo 'Spryker.all'; elif [[ $FileDirRelativeToProjectRoot$ == */spryker-shop || $FileDirRelativeToProjectRoot$ == *spryker-shop/Bundles ]]; then echo 'SprykerShop.all'; elif [[ $FileDirRelativeToProjectRoot$ == */spryker-eco || $FileDirRelativeToProjectRoot$ == *spryker-eco/Bundles ]]; then echo 'SprykerEco.all'; elif [[ $FileDirRelativeToProjectRoot$ == */spryker/spryker/* ]]; then echo \&quot;Spryker.$TARGET_DIR\&quot;; elif [[ $FileDirRelativeToProjectRoot$ == */spryker-shop/* ]]; then echo \&quot;SprykerShop.$TARGET_DIR\&quot;; elif [[ $FileDirRelativeToProjectRoot$ == */spryker-eco/* ]]; then echo \&quot;SprykerEco.$TARGET_DIR\&quot;; elif [[ &quot;$FileDirRelativeToProjectRoot$&quot; == */spryker/* &amp;&amp; ! &quot;$FileDirRelativeToProjectRoot$&quot; == */spryker/*/* ]]; then echo \&quot;Spryker.$TARGET_DIR\&quot;; fi; else if [[ &quot;$FilePath$&quot; == &quot;$ProjectFileDir$/src&quot; ]] || [[ &quot;$FilePath$&quot; == &quot;$ProjectFileDir$/src/*&quot; &amp;&amp; ! &quot;$FilePath$&quot; == &quot;$ProjectFileDir$/src/*/*/*&quot; ]]; then echo \&quot;all\&quot;; else echo \&quot;$TARGET_DIR\&quot;; fi; fi);
EOF
)

# The directory/file selected in PhpStorm ($FileName$) is transformed to camelcase into TARGET_DIR variable (eg: event-behavior => EventBehavior, AclExtension => AclExtension)
TARGET_DIR_EXPRESSION=$(cat <<'EOF'
    TARGET_DIR=$(echo '$FileName$' | awk -F- '{ OFS=\&quot;\&quot;; for(i=1; i&lt;=NF; i++) $i = toupper(substr($i,1,1)) substr($i,2); print }');
EOF
)

XML_CONTENT_START=$(cat <<'EOF'
    <toolSet name="Spryker Tools">
EOF
)

XML_CONTENT_END=$(cat <<'EOF'

    </toolSet>
EOF
)

XML_GENERIC_COMMANDS=$(cat <<'EOF'

       <tool name="code:sniff:style -f" showInMainMenu="false" showInEditor="false" showInProject="false" showInSearchPopup="false" disabled="false" useConsole="true" showConsoleOnStdOut="true" showConsoleOnStdErr="true" synchronizeAfterRun="true">
         <exec>
           <option name="COMMAND" value="bash " />
           <option name="PARAMETERS" value="-c &quot;echo ''; __TARGETDIR__ __TARGETMODULE__ echo \&quot; Executing: APPLICATION_ENV=development DEVELOPMENT_CONSOLE_COMMANDS=1 $ProjectFileDir$/__COMMAND__ c:s:s -m $TARGET_MODULE -f \&quot; ; echo ''; __TARGETDIR__ __TARGETMODULE__ APPLICATION_ENV=development DEVELOPMENT_CONSOLE_COMMANDS=1 $ProjectFileDir$/__COMMAND__ c:s:s -m $TARGET_MODULE -f; echo ''; &quot;" />
           <option name="WORKING_DIRECTORY" value="$ProjectFileDir$" />
         </exec>
       </tool>
       <tool name="code:phpstan" showInMainMenu="false" showInEditor="false" showInProject="false" showInSearchPopup="false" disabled="false" useConsole="true" showConsoleOnStdOut="true" showConsoleOnStdErr="true" synchronizeAfterRun="true">
         <exec>
           <option name="COMMAND" value="bash " />
           <option name="PARAMETERS" value="-c &quot;echo ''; __TARGETDIR__ __TARGETMODULE__ echo \&quot; Executing: APPLICATION_ENV=development DEVELOPMENT_CONSOLE_COMMANDS=1 $ProjectFileDir$/__COMMAND__ code:phpstan -m $TARGET_MODULE -vvv \&quot; ; echo ''; __TARGETDIR__ __TARGETMODULE__ APPLICATION_ENV=development DEVELOPMENT_CONSOLE_COMMANDS=1 $ProjectFileDir$/__COMMAND__ code:phpstan -m $TARGET_MODULE -vvv ; echo ''; &quot;" />
           <option name="WORKING_DIRECTORY" value="$ProjectFileDir$" />
         </exec>
       </tool>
       <tool name="code:sniff:architecture" showInMainMenu="false" showInEditor="false" showInProject="false" showInSearchPopup="false" disabled="false" useConsole="true" showConsoleOnStdOut="true" showConsoleOnStdErr="true" synchronizeAfterRun="true">
         <exec>
           <option name="COMMAND" value="bash " />
           <option name="PARAMETERS" value="-c &quot;echo ''; __TARGETDIR__ __TARGETMODULE__ echo \&quot; Executing: APPLICATION_ENV=development DEVELOPMENT_CONSOLE_COMMANDS=1 $ProjectFileDir$/__COMMAND__ code:sniff:architecture -m $TARGET_MODULE -vvv \&quot; ; echo ''; __TARGETDIR__ __TARGETMODULE__ APPLICATION_ENV=development DEVELOPMENT_CONSOLE_COMMANDS=1 $ProjectFileDir$/__COMMAND__ code:sniff:architecture -m $TARGET_MODULE -vvv ; echo ''; &quot;" />
           <option name="WORKING_DIRECTORY" value="$ProjectFileDir$" />
         </exec>
       </tool>
EOF
)

XML_CORE_COMMANDS=$(cat <<'EOF'

       <tool name="codecept run (limited, localmachine)" showInMainMenu="false" showInEditor="false" showInProject="false" showInSearchPopup="false" disabled="false" useConsole="true" showConsoleOnStdOut="true" showConsoleOnStdErr="true" synchronizeAfterRun="true">
         <exec>
           <option name="COMMAND" value="bash " />
           <option name="PARAMETERS" value="-c &quot;echo ''; __TARGETDIR__ __TARGETMODULE__ echo \&quot; Executing: APPLICATION_ENV=development DEVELOPMENT_CONSOLE_COMMANDS=1 $ProjectFileDir$/vendor/bin/codecept run -c $FileDirRelativeToProjectRoot$ \&quot; ; echo ''; __TARGETDIR__ __TARGETMODULE__ APPLICATION_ENV=development DEVELOPMENT_CONSOLE_COMMANDS=1 $ProjectFileDir$/vendor/bin/codecept run -c $FileDirRelativeToProjectRoot$ ; echo ''; &quot;" />
           <option name="WORKING_DIRECTORY" value="$ProjectFileDir$" />
         </exec>
       </tool>
       <tool name="dev:validate-module-transfers" showInMainMenu="false" showInEditor="false" showInProject="false" showInSearchPopup="false" disabled="false" useConsole="true" showConsoleOnStdOut="true" showConsoleOnStdErr="true" synchronizeAfterRun="true">
         <exec>
           <option name="COMMAND" value="bash" />
           <option name="PARAMETERS" value="-c &quot;echo ''; __TARGETDIR__ __TARGETMODULE__ echo \&quot; Executing: APPLICATION_ENV=development $ProjectFileDir$/vendor/bin/spryker-dev-console dev:validate-module-transfers -m $TARGET_MODULE -vvv \&quot; ; echo ''; __TARGETDIR__ __TARGETMODULE__ APPLICATION_ENV=development $ProjectFileDir$/vendor/bin/spryker-dev-console dev:validate-module-transfers -m $TARGET_MODULE -vvv ; echo ''; &quot;" />
           <option name="WORKING_DIRECTORY" value="$ProjectFileDir$" />
         </exec>
       </tool>
       <tool name="dev:validate-module-schemas" showInMainMenu="false" showInEditor="false" showInProject="false" showInSearchPopup="false" disabled="false" useConsole="true" showConsoleOnStdOut="true" showConsoleOnStdErr="true" synchronizeAfterRun="true">
         <exec>
           <option name="COMMAND" value="bash" />
           <option name="PARAMETERS" value="-c &quot;echo ''; __TARGETDIR__ __TARGETMODULE__ echo \&quot; Executing: APPLICATION_ENV=development $ProjectFileDir$/vendor/bin/spryker-dev-console dev:validate-module-schemas -m $TARGET_MODULE -vvv \&quot; ; echo ''; __TARGETDIR__ __TARGETMODULE__ APPLICATION_ENV=development $ProjectFileDir$/vendor/bin/spryker-dev-console dev:validate-module-schemas -m $TARGET_MODULE -vvv ; echo ''; &quot;" />
           <option name="WORKING_DIRECTORY" value="$ProjectFileDir$" />
         </exec>
       </tool>
       <tool name="dev:validate-module-databuilders" showInMainMenu="false" showInEditor="false" showInProject="false" showInSearchPopup="false" disabled="false" useConsole="true" showConsoleOnStdOut="true" showConsoleOnStdErr="true" synchronizeAfterRun="true">
         <exec>
           <option name="COMMAND" value="bash" />
           <option name="PARAMETERS" value="-c &quot;echo ''; __TARGETDIR__ __TARGETMODULE__ echo \&quot; Executing: APPLICATION_ENV=development $ProjectFileDir$/vendor/bin/spryker-dev-console dev:validate-module-databuilders -m $TARGET_MODULE -vvv \&quot; ; echo ''; __TARGETDIR__ __TARGETMODULE__ APPLICATION_ENV=development $ProjectFileDir$/vendor/bin/spryker-dev-console dev:validate-module-databuilders -m $TARGET_MODULE -vvv ; echo ''; &quot;" />
           <option name="WORKING_DIRECTORY" value="$ProjectFileDir$" />
         </exec>
       </tool>
EOF
)



XML_GENERIC_COMMANDS="${XML_GENERIC_COMMANDS//__TARGETDIR__/$TARGET_DIR_EXPRESSION}"
XML_GENERIC_COMMANDS="${XML_GENERIC_COMMANDS//__TARGETMODULE__/$TARGET_MODULE_EXPRESSION}"
XML_CORE_COMMANDS="${XML_CORE_COMMANDS//__TARGETDIR__/$TARGET_DIR_EXPRESSION}"
XML_CORE_COMMANDS="${XML_CORE_COMMANDS//__TARGETMODULE__/$TARGET_MODULE_EXPRESSION}"

display_help() {
    cat << EOF
Description:
  Copy Spryker Tool configuration files to the local machine PhpStorm configuration folder.

Usage:
  vendor/bin/phpstorm-command-line-tools.sh [options]

Options:
  -y                   Automatically agree to copy/remove the configuration files to the local machine PhpStorm configuration folder.
  --platform=PLATFORM  Set the platform for the configuration (default: docker). Options: docker, local.
  -p PLATFORM          Shorthand for --platform, specify either "docker" or "local".
  -u, --uninstall      Uninstall the Spryker tools configuration from the local PhpStorm configuration folder.
  -h                   Display this help message.

Help:
  This script will copy configuration files to the local PhpStorm configuration folder for the latest version of PhpStorm.

  Example of usage:

    ./phpstorm-command-line-tools.sh -y --platform=local
    This will run the script and automatically copy the configuration files for local machine tools without asking for confirmation.

    ./phpstorm-command-line-tools.sh -u
    This will uninstall the Spryker tools configuration from the local PhpStorm configuration folder.

  Use -h to display this help message:

    ./phpstorm-command-line-tools.sh -h

EOF
}


AUTO_CONFIRM=false
PLATFORM="docker" # Default value
UNINSTALL=false

while [[ $# -gt 0 ]]; do
  case "$1" in
    -y)
      AUTO_CONFIRM=true
      shift # Remove -y from processing
      ;;
    -p)
      # Ensure the platform argument exists and is valid for -p
      if [[ -z "$2" || ( "$2" != "docker" && "$2" != "local" ) ]]; then
        echo "Invalid platform option: $2 (allowed: docker, local)" >&2
        exit 1
      fi
      PLATFORM="$2"
      shift 2 # Remove both -p and its argument
      ;;
    --platform=*)
      PLATFORM="${1#*=}"
      if [[ "$PLATFORM" != "docker" && "$PLATFORM" != "local" ]]; then
        echo "Invalid platform option: $PLATFORM (allowed: docker, local)" >&2
        exit 1
      fi
      shift # Remove this argument
      ;;
    -u|--uninstall)
      UNINSTALL=true
      shift # Remove -u or --uninstall from processing
      ;;
    -h|--help)
      display_help
      exit 0
      ;;
    *)
      echo "Invalid option: $1" >&2
      exit 1
      ;;
  esac
done

delete_from_jetbrains() {
    # Take the base directory as an argument
    local BASE_DIR="$1"

    # Find the latest product version directory
    LATEST_DIR=$(find "$BASE_DIR" -maxdepth 1 -type d -name 'PhpStorm*' | sort -V | tail -n 1)

    # Check if the latest PhpStorm configuration directory was found
    if [[ -n "$LATEST_DIR" ]]; then
        DEST_DIR="$LATEST_DIR/tools"
        FILE_PATH="$DEST_DIR/Spryker Tools.xml"

        # Check if the Spryker Tools.xml file exists
        if [[ -f "$FILE_PATH" ]]; then
            # If AUTO_CONFIRM is not true, ask for confirmation
            if ! $AUTO_CONFIRM; then
                echo -e "\033[0;33mThis script will delete the Spryker Tools configuration file from your local JetBrains PhpStorm configuration directory:\033[0m"
                echo "File to be deleted: $FILE_PATH"
                read -p "Do you agree to proceed with the deletion? (y/n): " confirm

                if [[ "$confirm" != "y" ]]; then
                    echo "Operation canceled."
                    return 1
                fi
            fi

            # Proceed to delete the file
            rm -f "$FILE_PATH"
            echo "Deleted $FILE_PATH ."
            echo -e "\033[0;33mPlease restart PhpStorm for the changes to take effect.\033[0m"
        else
            echo "No Spryker Tools.xml file found in $DEST_DIR."
        fi
    else
        echo "No JetBrains product directory found in $BASE_DIR."
    fi
}

# Function to handle copying on both macOS and Linux
copy_to_jetbrains() {
    # Take the base directory as an argument
    local BASE_DIR="$1"

    # Find the latest product version directory
    LATEST_DIR=$(find "$BASE_DIR" -maxdepth 1 -type d -name 'PhpStorm*' | sort -V | tail -n 1)

    if [[ -n "$LATEST_DIR" ]]; then
        DEST_DIR="$LATEST_DIR/tools"

        # If the -y flag was not provided, ask for confirmation
        if ! $AUTO_CONFIRM; then
            echo -e "\033[0;33mThis script will create or modify a file in your local machine JetBrains PhpStorm configuration directory:\033[0m"
            echo "File: $DEST_DIR/Spryker Tools.xml"
            read -p "Do you agree to proceed? (y/n): " confirm

            if [[ "$confirm" != "y" ]]; then
                echo "Operation canceled."
                exit 1
            fi
        fi

        mkdir -p "$DEST_DIR"

        if [[ "$PLATFORM" == "local" ]]; then
            XML_GENERIC_COMMANDS="${XML_GENERIC_COMMANDS//__COMMAND__/vendor/bin/console}"
            if [[ -f "./vendor/bin/spryker-dev-console" ]]; then
                echo "$XML_CONTENT_START$XML_GENERIC_COMMANDS$XML_CORE_COMMANDS$XML_CONTENT_END" > "$DEST_DIR/Spryker Tools.xml"
            else
                echo "$XML_CONTENT_START$XML_CORE_COMMANDS$XML_CONTENT_END" > "$DEST_DIR/Spryker Tools.xml"
            fi

            echo ""
            echo "Successfully created Spryker Tools.xml in $DEST_DIR with"
            echo "- vendor/bin/console code:sniff:style -f -m [module]"
            echo "- vendor/bin/console code:phpstan -m [module] -vvv"
            echo "- vendor/bin/console code:sniff:architecture -m [module] -vvv"
            if [[ -f "./vendor/bin/spryker-dev-console" ]]; then
                echo "- vendor/bin/codecept run -c [relative directory]"
                echo "- vendor/bin/spryker-dev-console dev:validate-module-transfers -m [module] -vvv"
                echo "- vendor/bin/spryker-dev-console dev:validate-module-schemas -m [module] -vvv"
                echo "- vendor/bin/spryker-dev-console dev:validate-module-databuilders -m [module] -vvv"
            fi
            echo ""
            echo -e "\033[1;33mImportant:\033[0m To execute commands from within PhpStorm, verify that all required tools are installed and runnable on your local machine."
            echo -e "\033[0;33mPlease restart PhpStorm for the changes to take effect.\033[0m"

        elif [[ "$PLATFORM" == "docker" ]]; then
            XML_GENERIC_COMMANDS="${XML_GENERIC_COMMANDS//__COMMAND__/docker/sdk console}"
            echo "$XML_CONTENT_START$XML_GENERIC_COMMANDS$XML_CONTENT_END" > "$DEST_DIR/Spryker Tools.xml"
            echo ""
            echo "Successfully created Spryker Tools.xml in $DEST_DIR with"
            echo "- docker/sdk console code:sniff:style -f -m [module]"
            echo "- docker/sdk console code:phpstan -m [module] -vvv"
            echo "- docker/sdk console code:sniff:architecture -m [module] -vv"
            if [[ -f "./vendor/bin/spryker-dev-console" ]]; then
                echo "Info: vendor/bin/spryker-dev-console and vendor/bin/codecept commands were not registered as they require local machine to run."
            fi
            echo ""
            echo -e "\033[1;33mImportant:\033[0m To execute commands from within PhpStorm, ensure that Spryker Docker is running."
            echo -e "\033[0;33mPlease restart PhpStorm for the changes to take effect.\033[0m"

        else
            echo "Error: Unexpected argument combination. Please specify a valid PLATFORM value ('local' or 'docker')." >&2
            exit 1
        fi
    else
        echo "No JetBrains product directory found in $BASE_DIR."

        exit 0
    fi
}

if [[ "$UNINSTALL" != true ]]; then
    if ! command -v awk &> /dev/null; then
        echo "Error: 'awk' is not installed. Please install it before running this script."
        exit 1
    fi
fi

if [[ "$OSTYPE" == "darwin"* ]]; then
    BASE_DIR="$HOME/Library/Application Support/JetBrains"
elif [[ "$OSTYPE" == "linux-gnu"* ]]; then
    BASE_DIR="$HOME/.config/JetBrains"
else
    echo "Unsupported operating system: $OSTYPE"
    exit 1
fi

if [[ "$UNINSTALL" == true ]]; then
    delete_from_jetbrains "$BASE_DIR"
else
    copy_to_jetbrains "$BASE_DIR"
fi
