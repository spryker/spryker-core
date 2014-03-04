user:

  vagrant:
    fullname: Vagrant User
    shell: /bin/zsh

  root:
    fullname: Root Account
    shell: /bin/bash
    ssh_key: |
      ssh-rsa AAAAB3NzaC1yc2EAAAADAQABAAABAQDFjY5AjrtoWwp1I32TR55b2u/bnMuxeRhDyik4mYSz/9vf1KGBo9cWNcHYuF4K2l5Bgm6GvSWJpHzP3TQtOeR3kKTyKtiBF0jjDANislK2oaaL9b3QkE5JpJkoHkGrhm2ZHOnYbZTEYbYKnxnim8zMqLUB7JYIKHXL92T5cJrZVSt9/Dcs04NHEablPeSz30KU6r+BzNc84l6wdq9imC5glnjEDLIIPeyDBW0M7qbP78Pt8wynuqUzr5HIZSShVMd6nwZfEEtak0vSbSvEgLw7xxZR3NHFcq6FOncclVxIHOW49kwsXweBJi2u25X+Dj2iCkMWnJGk/6jaRT5n1kyj

#/root/.ssh/id_rsa:
#  file.managed:
#    - source: salt://user/files/dev/is_rsa
