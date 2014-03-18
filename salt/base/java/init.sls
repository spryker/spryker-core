java:
  pkg.installed:
   - name: openjdk-7-jre-headless
  alternatives.set:
    - name: java
    - path: /usr/lib/jvm/java-7-openjdk-amd64/jre/bin/java
    - require:
      - pkg: openjdk-7-jre-headless