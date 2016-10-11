[![Build Status](https://travis-ci.org/pfitzer/dnsbl.svg?branch=master)](https://travis-ci.org/pfitzer/dnsbl)

## DNSBL
check ip for blacklisting

usage
-----
```
$dnsbl = new Dnsbl();

$result = $dnsbl->lookup('127.0.0.1');
```

testing
-------
```
phpunit --configuration phpunit.xml
```