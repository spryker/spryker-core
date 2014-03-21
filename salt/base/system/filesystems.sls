filesystem-tools:
  pkg.installed:
    - pkgs:
      - btrfs-tools

{% for fs, fs_details in pillar.get('filesystems', {}).items() %}
create-fs-{{ fs }}:
  cmd.run:
    - name: mkfs -t {{ fs_details.filesystem }} {{ fs_details.disk }}{{ fs_details.partition }}
    - onlyif: test -b {{ fs_details.disk }} && parted {{ fs_details.disk }} print | grep '^ *{{ fs_details.partition }}.*GB' | grep -v '{{ fs_details.filesystem }}'
    - requires:
      - pkg: filesystem-tools

{{ fs_details.mount_point }}:
  file.directory

fstab-for-{{ fs }}:
  file.append:
    - name: /etc/fstab
    - text: {{ fs_details.disk }}{{ fs_details.partition }} {{ fs_details.mount_point }} {{ fs_details.filesystem }} {{ fs_details.mount_options }} 0 1
    - require:
      - file: {{ fs_details.mount_point }}
      - cmd: create-fs-{{ fs }}

mount-fs-{{ fs }}:
  cmd.wait:
    - name: mount {{ fs_details.mount_point }}
    - watch:
      - file: fstab-for-{{ fs }}
    - requires:
      - file: {{ fs_details.mount_point }}

{% endfor %}
