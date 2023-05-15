# Very short description of the package

[![Latest Version on Packagist](https://img.shields.io/packagist/v/summonshr/requests.svg?style=flat-square)](https://packagist.org/packages/summonshr/requests)
[![Total Downloads](https://img.shields.io/packagist/dt/summonshr/requests.svg?style=flat-square)](https://packagist.org/packages/summonshr/requests)

Laravel application tends to have too much controllers which does not do much. Even from documentation, some request is process by custom laravel requests, then some process happens in controller and returns response. If you see the pattern, we really do not require any controller at all.

## Installation

You can install the package via composer:

```bash
composer require summonshr/requests
```

## Usage

```php
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateApplication extends FormRequest
{
    // To call on specific method
    const REQUEST_METHOD = 'GET';

    // To call on specific action
    const ACTION = 'create_application';

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            //
        ];
    }

    public function process()
    {
        // Process your requests here
    }
}
```

When making request, send a parameter 'action' in each request on universal route.

```json
{
    "action": "create_application",
    // Rest of the parameters
}
```

In case of GET Request, send it to query param 'action'.

The application will automatically call the Request specified as per in Requests directory.
### Testing

```bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email summonshr@gmail.com instead of using the issue tracker.

## Credits

-   [Suman Shrestha](https://github.com/summonshr)
-   [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Laravel Package Boilerplate

This package was generated using the [Laravel Package Boilerplate](https://laravelpackageboilerplate.com).
