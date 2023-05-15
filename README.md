[![Latest Version on Packagist](https://img.shields.io/packagist/v/summonshr/requests.svg?style=flat-square)](https://packagist.org/packages/summonshr/requests)
[![Total Downloads](https://img.shields.io/packagist/dt/summonshr/requests.svg?style=flat-square)](https://packagist.org/packages/summonshr/requests)

# Why this package

Laravel application tends to have too much controllers which does not do much. Same all five request, index, create, show, edit and destroy. For each controller, we would have five functions, five requests to validate those requests too. This seems redundant. For anything to add, I would have to add atleast one route, one controller and one request.

Rather than that, we could simply have one route that would accept anything, which would call a file that would authorize, validate and process a request. 

So, this package was invented.

No more controllers(If you need one, let me know why). No more adding routes, just dive through requests.

One route to rule them all.

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
use Summonshr\Requests\Contracts\UniversalRequestInterface;

class CreateApplication extends FormRequest implements UniversalRequestInterface
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

Package is smart enough to find out method and action on its own too.

```
    'store' => 'POST',
    'create' => 'POST',
    'edit' => 'PUT',
    'destroy' => 'DELETE',
    'show' => 'GET',
```

Any request class starting with store, create, edit, destroy, show will automatically have POST, POST, PUT, DELETE, SHOW  by default. You can change those by publishing config files too.

Action is resolved as kebab case of the request class names if not specified in ACTION constant.

For example, CreateUserRequest would have action 'create-user-request' as action by default.

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
