BootForm, forms for Laravel 5
==================================


BootForms builds on the more general BootstrapForm dwightwatson/bootstrap-form package by adding another layer of abstraction to quickly generate markup for standard Bootstrap 3 forms.

## Installation

First, require the package using Composer.

```shell
composer require therali/bootform
```

Now, add these service providers to your `config/app.php` file (don't add the `HtmlServiceProvider` if you already have it).

```php
Collective\Html\HtmlServiceProvider::class,
Therali\BootForm\BootFormServiceProvider::class,
```

And finally add these to the aliases array (note: Form and Html must be listed before BootForm):

```php
'Form'     => Collective\Html\FormFacade::class,
'HTML'     => Collective\Html\HtmlFacade::class,
'BootForm' => Therali\BootForm\Facades\BootForm::class,
```

Feel free to use a different alias for BootForm if you'd prefer something shorter.

## Configuration

There are a number of configuration options available for BootForm. Run the following Artisan command to publish the configuration option to your `config` directory:

```shell
php artisan vendor:publish
```
