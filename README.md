[![CI](https://github.com/pfitzer/dnsbl/actions/workflows/test.yml/badge.svg)](https://github.com/pfitzer/dnsbl/actions/workflows/test.yml) [![Code Climate](https://codeclimate.com/github/pfitzer/dnsbl/badges/gpa.svg)](https://codeclimate.com/github/pfitzer/dnsbl) [![Issue Count](https://codeclimate.com/github/pfitzer/dnsbl/badges/issue_count.svg)](https://codeclimate.com/github/pfitzer/dnsbl) [![Packagist](https://img.shields.io/packagist/v/pfitzer/dnsbl.svg?maxAge=2592000)](https://packagist.org/packages/pfitzer/dnsbl)


## DNSBL
check ip for blacklisting

install
-------
composer require pfitzer/dnsbl
```
"require": {
    "pfitzer/dnsbl": "*"
}
```
usage
-----
```
$dnsbl = new Dnsbl();

try {
    $result = $dnsbl->lookup('127.0.0.1');
} catch (\InvalidArgumentException $e) {
    # do something
}
```

contribute
----------
feel free to do so

#### install dev dependencies
```
composer install
```
#### run unit tests
```
phpunit --configuration phpunit.xml
```
