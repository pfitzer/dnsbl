[![Build Status](https://travis-ci.org/pfitzer/dnsbl.svg?branch=master)](https://travis-ci.org/pfitzer/dnsbl)

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

$result = $dnsbl->lookup('127.0.0.1');
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