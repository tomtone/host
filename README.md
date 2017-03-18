![team neusta][logo]
# The Hosts-Project #
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/tomtone/test/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/tomtone/test/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/tomtone/test/badges/build.png?b=master)](https://scrutinizer-ci.com/g/tomtone/test/build-status/master)
[![Code Coverage](https://scrutinizer-ci.com/g/tomtone/test/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/tomtone/test/?branch=master)

### What's hosts-Projectg for? ###

The **hosts** Project is a little helper to manage ssh hosts, users and ports with the level configuration hierarchy.

### Minimum Requirements ###


* Unix System
* PHP 7.0 <=
* ssh

How to get started?
---------------------

There are 3 Options to install:

### As a Phar (Recommended)

You may download a ready-to-use version of Box as a Phar:

```sh
$ curl -LSs https://box-project.github.io/box2/installer.php | php
```

The command will check your PHP settings, warn you of any issues, and the download it to the current directory. From there, you may place it anywhere that will make it easier for you to access (such as `/usr/local/bin`) and chmod it to `755`.


```sh
$ hosts --version
```

Whenever a new version of the application is released, you can simply run the `self-update` command to get the latest version:

```sh
$ hosts self-update
```