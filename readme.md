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


## Using

```php
$navigation = new \KodiComponents\Navigation\Navigation([
	[
		'title' => 'Test',
		'icon' => 'fa fa-user',
		'priority' => 500,
		'url' => 'http://site.com',
		'pages' => [
			[
				'title' => 'Test3',
				'icon' => 'fa fa-user',
				'url' => 'http://site.com',
			],
		],
	],
	[
		'title' => 'Test1',
		'icon' => 'fa fa-user',
		'priority' => 600,
		'url' => 'http://site.com',
	],
]);


// Setting pages from array 
$navigation->setFromArray([
	[
		'title' => 'Test',
		'icon' => 'fa fa-user',
		'priority' => 500,
		'url' => 'http://site.com',
		'pages' => [
			[
				'title' => 'Test3',
				'icon' => 'fa fa-user',
				'url' => 'http://site.com',
			],
		],
	],
	[
		'title' => 'Test1',
		'icon' => 'fa fa-user',
		'priority' => 600,
		'url' => 'http://site.com',
	],
]);


// Setting page with method addPage(array|string|\KodiComponents\Navigation\Contracts\PageInterface)

$newPage = $navigation->addPage('New page');
$subPage = $newPage->addPage('Sub page');

$subPage->setPages(function(PageInterface $page) {
	$page->addPage(...);
	$page->addPage(...);
	
	$page->addPage(...)->setPages(function(PageInterface $page) {
		...
	});
});

$page = new \KodiComponents\Navigation\Page();

$page->setTitle(...);
$page->setIcon(...);
$page->setId(...);

$subPage1 = $newPage->addPage($page);

$navigation->getPages()->push($page);

$navigation->getPages()->prepend($page);

// Child pages
$page->getPages(); // Get sub pages
$navigation->getPages(); // Navigation pages

// Count pages
$navigation->countPages();

// Get first page
$page = $navigation->getPages()->first();

// get sub pages
$page->getPages(); // return KodiComponents\Navigation\PageCollection

// Count sub pages
$page->countPages();

// get parent page
$page->getParent();

// check if page has child
$page->hasChild();
$page->isChildOf($navigation);

// get title
$page->getTitle();

// get icon
$page->getIcon();

// get id
$page->getId();

// get utl
$page->getUrl();

// get path
$page->getPath(); // return array ['first page title', 'second page title', 'current page']

// get priority
$page->getPriority();

// check is active
$page->isActive();

// to array
$navigation->toArray();
$page->toArray();
```

### Searching

```php
// by path
$navigation->getPages()->findByPath('Page 1/Page 2/Page 3');

// or for page
$page->getPages()->findByPath('Page 1/Page 2/Page 3');


// by id

$page = new \KodiComponents\Navigation\Page();
$page->setId('page_id');

$navigation->addPage($page);
$navigation->getPages()->findById('page_id'); // return Page | null

$subPage = $navigation->addPage('Test page without id');
$subPage->addPage('Test subpage without id');

$navigation->getPages()->findById(md5('Test page without id/Test subpage without id'));
```
