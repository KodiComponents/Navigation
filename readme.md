# Laravel navigation class

## Installation

1. Require this package in your composer.json and run composer update:

	`composer require kodicomponents/navigation`
	
2. After composer update, insert service provider `KodiComponents\Navigation\NavigationServiceProvider::class,` before 
`Application Service Providers...` to the `config/app.php`

**Example**
```php
	...
	/*
	 * Navigation Service Provider
	 */
	KodiComponents\Navigation\NavigationServiceProvider::class,
	
	/*
	 * Application Service Providers...
	 */
	App\Providers\AppServiceProvider::class,
	...
```