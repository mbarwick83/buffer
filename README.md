# Buffer API for Laravel 5.*

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Total Downloads][ico-downloads]][link-downloads]

Simple Buffer API package for Laravel 5.*.

## Installation

To install, run the following command in your project directory

``` bash
$ composer require mbarwick83/buffer dev-master
```

Then in `config/app.php` add the following to the `providers` array:

```
Mbarwick83\Buffer\BufferServiceProvider::class
```

Also, if you must (recommend you don't), add the Facade class to the `aliases` array in `config/app.php` as well:

```
'Buffer'    => Mbarwick83\Buffer\Facades\Buffer::class
```

**But it'd be best to just inject the class, like so (this should be familiar):**

```
use Mbarwick83\Buffer\Buffer;
```

## Configuration

To publish the packages configuration file, run the following `vendor:publish` command:

```
php artisan vendor:publish
```

This will create a `buffer.php` in your config directory. Here you **must** enter your Buffer API Keys. Get your API keys at [https://buffer.com/developers/api](https://buffer.com/developers/api).

## Example Usage

``` php
use Mbarwick83\Buffer\Buffer;

// Get login url:
public function index(Buffer $buffer)
{
	return $buffer->getLoginUrl();
}

// Get access token on callback, once user has authorized via above method
public function callback(Request $request, Buffer $buffer)
{
	$response = $buffer->getAccessToken($request->code);

	if (isset($response['code']))
        throw new \Exception($response['error_message'], $response['code']);

	return $buffer->getUserDetails($response['access_token']);
}
```

Those are the only three custom classes for the API package (i.e. `getLoginUrl()`, `getAccessToken()`, and `getUserDetails()`. The rest of the API works with `POST` and `GET` requests based on Buffers's end points to keep this package ***super simple***. You can view all the end points here [https://buffer.com/developers/api](https://buffer.com/developers/api).

All you need to do is specify if the request is a `POST` or `GET` request, specify **just the end point** and any URL queries that are required (in an array).
For example:

```php
public function index(Buffer $buffer)
{
    $user_profiles = $this->buffer->get('1/profiles.json', ['access_token' => $access_token]);
    return $user_profiles;
}
```

**VERY SIMPLE AND EASY!**

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CONDUCT](CONDUCT.md) for details.

## Credits

- [Mike Barwick][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/mbarwick83/buffer.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/mbarwick83/buffer.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/mbarwick83/buffer
[link-downloads]: https://packagist.org/packages/mbarwick83/buffer
[link-author]: https://github.com/mbarwick83
[link-contributors]: ../../contributors
