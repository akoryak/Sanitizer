# Security/Sanitizer

## Installation

Install the latest version with

```bash
$ composer require akoryak/security
```

## Basic Usage

```php
<?php

// add lib
use Akoryak\Security\Sanitizer;

// sanitize string with html content
$sanit = new Sanitizer()
$str = $sanit->stringValue("<b><script>console.log(document.cookie);</script></b>", Sanitizer::DEFAULT_ALLOWED_HTML_TAGS);
echo $str; // <b>console.log(document.cookie);</b>

// sanitize string with non html content
$str = $sanit->stringValue("<script>console.log(document.cookie);</script>");
echo $str; // &lt;script&gt;console.log(document.cookie);&lt;/script&gt;

// validate email
if ( $sanit->->validateEmail('test"@mail.ca') ) {
	echo "email is valid";
}

// secure array of int before use it into SQL
// CAUTION: use parameterized queries when you can, which are SQL injection resistant by design
$arr = = [0 => '1', 'key2' => 1, 'two' => 'one', '123' => true, 'vulnerability' => ' 1=1" AND 1=(DELETE FROM users);--"'];
$arr = Sanitizer::arrayOfInts($arr);
```

## Third Party Packages

No third party packages are used

## About

### Requirements

- Security works with PHP 7.4 or above.

### Submitting bugs and feature requests

Bugs and feature request are tracked on [GitHub](https://github.com/akoryak/security/issues)

### License

Security/Sanitizer is licensed under the Apache License - see the `LICENSE` file for details