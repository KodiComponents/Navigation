<?php

use KodiComponents\Navigation\Contracts\NavigationInterface;
use KodiComponents\Navigation\Contracts\PageInterface;
use KodiComponents\Navigation\Navigation;
use KodiComponents\Navigation\Page;

class NavigationTest extends TestCase
{
    /**
     * @covers Navigation::__constructor
     */
    public function testConstructor()
    {
        $navigation = new Navigation();

        $this->assertInstanceOf(NavigationInterface::class, $navigation);
        $this->assertEquals(0, $navigation->countPages());
        $this->assertEquals([], $navigation->toArray());
    }

    /**
     * @covers Navigation::makePage
     */
    public function testMakePage()
    {
        $page = Navigation::makePage([
            'title' => 'Test',
            'icon' => 'fa fa-user',
            'priority' => 500,
            'url' => 'http://site.com',
            'pages' => [
                [
                    'title' => 'Test 2',
                    'icon' => 'fa fa-group',
                    'url' => 'site.com',
                ],
            ],
        ]);

        $child = $page->getPages()->first();

        $this->assertInstanceOf(PageInterface::class, $page);
        $this->assertInstanceOf(PageInterface::class, $child);

        $this->assertEquals(1, $page->countPages());

        $this->assertEquals('Test', $page->getTitle());
        $this->assertEquals('<i class="fa fa-user"></i>', $page->getIcon());
        $this->assertEquals('http://site.com', $page->getUrl());
        $this->assertEquals(500, $page->getPriority());

        $this->assertEquals('Test 2', $child->getTitle());
        $this->assertEquals('<i class="fa fa-group"></i>', $child->getIcon());
        $this->assertEquals(url('site.com'), $child->getUrl());
        $this->assertEquals(100, $child->getPriority());
    }

    /**
     * @covers Navigation::getCurrentUrl
     */
    public function testGetCurrentUrl()
    {
        $navigation = new Navigation();

        $this->assertEquals(url()->current(), $navigation->getCurrentUrl());

        $navigation->setCurrentUrl('http://site.com/test');
        $this->assertEquals('http://site.com/test', $navigation->getCurrentUrl());
    }

    /**
     * @covers Navigation::setCurrentUrl
     */
    public function testSetCurrentUrl()
    {
        $navigation = new Navigation();

        $navigation->setCurrentUrl('http://site.com/test');
        $this->assertEquals('http://site.com/test', $navigation->getCurrentUrl());
    }

    /**
     * @covers Navigation::setFromArray
     */
    public function testSetFromArray()
    {
        $navigation = new Navigation();

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

        $expected = [
            '0cbc6611f5540bd0809a388dc95a615b' => [
                'child' => [
                    'b3f66ec1535de7702c38e94408fa4a17' => [
                        'child' => [],
                        'hasChild' => false,
                        'id' => '19fa931404bfc619f236396fa99d0cc8',
                        'title' => 'Test3',
                        'icon' => '<i class="fa fa-user"></i>',
                        'priority' => 100,
                        'url' => 'http://site.com',
                        'path' => ['Test', 'Test3'],
                        'isActive' => false,
                        'attributes' => '',
                        'badge' => null,
                    ],
                ],
                'hasChild' => true,
                'id' => '0cbc6611f5540bd0809a388dc95a615b',
                'title' => 'Test',
                'icon' => '<i class="fa fa-user"></i>',
                'priority' => 500,
                'url' => 'http://site.com',
                'path' => ['Test'],
                'isActive' => false,
                'attributes' => ' class="has-child"',
                'badge' => null,
            ],
            'e1b849f9631ffc1829b2e31402373e3c' => [
                'child' => [],
                'hasChild' => false,
                'id' => 'e1b849f9631ffc1829b2e31402373e3c',
                'title' => 'Test1',
                'icon' => '<i class="fa fa-user"></i>',
                'priority' => 600,
                'url' => 'http://site.com',
                'path' => ['Test1'],
                'isActive' => false,
                'attributes' => '',
                'badge' => null,
            ],
        ];

        $this->assertEquals($expected, $navigation->toArray());

        $this->assertEquals(3, $navigation->countPages());

        $navigation->setFromArray([
            [
                'title' => 'Test 4',
                'icon' => 'fa fa-user',
                'priority' => 700,
                'url' => 'http://site.com',
            ],
        ]);

        $expected = $expected + [
                '9fe74bb46baed663321329a1fc479e8b' => [
                    'child' => [],
                    'hasChild' => false,
                    'id' => '9fe74bb46baed663321329a1fc479e8b',
                    'title' => 'Test 4',
                    'icon' => '<i class="fa fa-user"></i>',
                    'priority' => 700,
                    'url' => 'http://site.com',
                    'path' => ['Test 4'],
                    'isActive' => false,
                    'attributes' => '',
                    'badge' => null,
                ],
            ];

        $this->assertEquals(4, $navigation->countPages());
        $this->assertEquals($expected, $navigation->toArray());
    }

    /**
     * @covers Navigation::addPage
     */
    public function testAddPage()
    {
        $navigation = new Navigation();

        $navigation->addPage('Title');

        $this->assertEquals(1, $navigation->countPages());

        $navigation->addPage([
            'title' => 'Test 4',
            'icon' => 'fa fa-user',
            'priority' => 700,
            'url' => 'http://site.com',
        ]);

        $this->assertEquals(2, $navigation->countPages());

        $navigation->addPage(new Page('Test 5'));

        $this->assertEquals(3, $navigation->countPages());
        $this->assertEquals([
            'b78a3223503896721cca1303f776159b' => [
                'child' => [],
                'hasChild' => false,
                'id' => 'b78a3223503896721cca1303f776159b',
                'title' => 'Title',
                'icon' => null,
                'priority' => 100,
                'url' => null,
                'path' => ['Title'],
                'isActive' => false,
                'attributes' => '',
                'badge' => null,
            ],
            '9fe74bb46baed663321329a1fc479e8b' => [
                'child' => [],
                'hasChild' => false,
                'id' => '9fe74bb46baed663321329a1fc479e8b',
                'title' => 'Test 4',
                'icon' => '<i class="fa fa-user"></i>',
                'priority' => 700,
                'url' => 'http://site.com',
                'path' => ['Test 4'],
                'isActive' => false,
                'attributes' => '',
                'badge' => null,
            ],
            'ce03a4296e564386d37eb22a7dce0623' => [
                'child' => [],
                'hasChild' => false,
                'id' => 'ce03a4296e564386d37eb22a7dce0623',
                'title' => 'Test 5',
                'icon' => null,
                'priority' => 100,
                'url' => null,
                'path' => ['Test 5'],
                'isActive' => false,
                'attributes' => '',
                'badge' => null,
            ],
        ], $navigation->toArray());
    }

    /**
     * @covers Navigation::getPages
     */
    public function testGetPages()
    {
        $navigation = new Navigation();
        $this->assertInstanceOf(\KodiComponents\Navigation\PageCollection::class, $navigation->getPages());
    }

    /**
     * @covers Navigation::countPages
     */
    public function testCountPages()
    {
        $navigation = new Navigation([
            [
                'title' => 'Title',
                'pages' => [
                    [
                        'title' => 'Title 1',
                        'pages' => [
                            [
                                'title' => 'Title 2',
                                'pages' => [
                                    [
                                        'title' => 'Title 3',
                                        'pages' => [
                                            [
                                                'title' => 'Title 4',
                                                'pages' => [
                                                    [
                                                        'title' => 'Title 5',
                                                        'pages' => [
                                                            [
                                                                new Page('Title 7'),
                                                            ],
                                                        ],
                                                    ],
                                                ],
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            [
                'title' => 'Title 8',
            ],
        ]);

        $this->assertEquals(8, $navigation->countPages());
        $this->assertTrue(is_integer($navigation->countPages()));
    }

    /**
     * @covers Navigation::setAccessLogic
     */
    public function testSetAccessLogic()
    {
        $navigation = new Navigation();

        $navigation->setAccessLogic(function ($page) {
            return false;
        });

        $this->assertTrue(is_callable($navigation->getAccessLogic()));
    }

    /**
     * @covers Navigation::getAccessLogic
     */
    public function testGetAccessLogic()
    {
        $navigation = new Navigation();

        $this->assertTrue($navigation->getAccessLogic());

        $navigation->setAccessLogic(function ($page) {
            return false;
        });

        $this->assertTrue(is_callable($navigation->getAccessLogic()));
    }

    /**
     * @covers Navigation::hasChild
     */
    public function testHasChild()
    {
        $navigation = new Navigation([
            [
                'title' => 'Title',
                'pages' => [
                    [
                        'title' => 'Title 1',
                        'pages' => [
                            [
                                'title' => 'Title 2',
                                'pages' => [
                                    [
                                        'title' => 'Title 3',
                                        'pages' => [
                                            [
                                                'title' => 'Title 4',
                                                'pages' => [
                                                    [
                                                        'title' => 'Title 5',
                                                        'pages' => [
                                                            [
                                                                new Page('Title 7'),
                                                            ],
                                                        ],
                                                    ],
                                                ],
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            [
                'title' => 'Title 8',
            ],
        ]);

        $this->assertTrue($navigation->hasChild());
        $this->assertTrue($navigation->getPages()->first()->hasChild());
        $this->assertTrue($navigation->getPages()->first()->getPages()->first()->hasChild());
        $this->assertFalse($navigation->getPages()->last()->hasChild());
    }

    /**
     * @covers Navigation::getCurrentPage
     */
    public function testGetCurrentPage()
    {
        $navigation = new Navigation([
            [
                'title' => 'Page 1',
                'url' => 'http://site.com/page-1',
            ],
            [
                'title' => 'Page 2',
                'url' => 'http://site.com/page-2',
                'pages' => [
                    [
                        'title' => 'Page 5',
                        'url' => 'http://site.com/page-2/page-5',
                    ],
                    [
                        'title' => 'Page 6',
                        'url' => 'http://site.com/page-2/page-6',
                    ],
                ],
            ],
            [
                'title' => 'Page 3',
                'url' => 'http://site.com/page-3',
            ],
            [
                'title' => 'Page 4',
                'url' => 'http://site.com/page-3',
            ],
        ]);

        $navigation->setCurrentUrl('http://site.com/page-2/page-5');

        $this->assertInstanceOf(PageInterface::class, $navigation->getCurrentPage());
        $this->assertEquals('Page 5', $navigation->getCurrentPage()->getTitle());

        $navigation->setCurrentUrl('http://site.com/page-3');
        $this->assertEquals('Page 3', $navigation->getCurrentPage()->getTitle());

        $navigation->setCurrentUrl('http://site.com/page');
        $this->assertNull($navigation->getCurrentPage());
    }

    /**
     * @covers Navigation::toArray
     */
    public function testToArray()
    {
        $navigation = new Navigation();

        $this->assertTrue(is_array($navigation->toArray()));
    }

    /**
     * @covers Navigation::filterByAccessRights
     */
    public function testFilterByAccessRights()
    {
        $pages = [
            [
                'title' => 'Page 1',
                'url' => 'http://site.com/page-1',
            ],
            [
                'title' => 'Page 2',
                'url' => 'http://site.com/page-2',
                'pages' => [
                    [
                        'title' => 'Page 5',
                        'url' => 'http://site.com/page-2/page-5',
                    ],
                    [
                        'title' => 'Page 6',
                        'url' => 'http://site.com/page-2/page-6',
                    ],
                ],
            ],
            [
                'title' => 'Page 3',
                'url' => 'http://site.com/page-3',
            ],
            [
                'title' => 'Page 4',
                'url' => 'http://site.com/page-3',
            ],
        ];

        $navigation = new Navigation($pages);

        $this->assertEquals(6, $navigation->countPages());

        $navigation->setAccessLogic(function ($page) {
            return $page->getTitle() == 'Page 2';
        });

        $navigation->filterByAccessRights();

        $this->assertEquals(1, $navigation->countPages());
        $this->assertEquals('Page 2', $navigation->getPages()->first()->getTitle());

        $navigation = new Navigation($pages);
        $navigation->setAccessLogic(function ($page) {
            return $page->getTitle() == 'Page 2' or $page->isChild();
        });
        $navigation->filterByAccessRights();

        $this->assertEquals(3, $navigation->countPages());
        $this->assertEquals(2, $navigation->getPages()->first()->countPages());
        $this->assertEquals('Page 2', $navigation->getPages()->first()->getTitle());
    }

    /**
     * @covers Navigation::sort
     */
    public function testSort()
    {
        $navigation = new Navigation([
            [
                'title' => 'Page 1',
                'priority' => 800,
            ],
            [
                'title' => 'Page 2',
                'priority' => 100,
                'pages' => [
                    [
                        'title' => 'Page 5',
                        'priority' => 300,
                    ],
                    [
                        'title' => 'Page 6',
                        'priority' => 200,
                    ],
                ],
            ],
            [
                'title' => 'Page 3',
                'priority' => 600,
            ],
            [
                'title' => 'Page 4',
                'pages' => [
                    [
                        'title' => 'Page 7',
                        'priority' => 300,
                    ],
                    [
                        'title' => 'Page 8',
                        'priority' => 200,
                    ],
                ],
            ],
        ]);

        $navigation->sort();

        $this->assertEquals([
            '9cd0e179d2e28222d91e2415d0eb8ade' => [
                'child' => [
                    '57a047b16059f2bcaaaaaa3cf5e521e0' => [
                        'child' => [],
                        'hasChild' => false,
                        'id' => '8e66e59973708ffc406444148008626c',
                        'title' => 'Page 6',
                        'icon' => null,
                        'priority' => 200,
                        'url' => null,
                        'path' => ['Page 2', 'Page 6',],
                        'isActive' => false,
                        'attributes' => '',
                        'badge' => null,
                    ],
                    'ecdd972f0bcbc358d240ed0767885f42' => [
                        'child' => [],
                        'hasChild' => false,
                        'id' => '0d92a8d86d8d60be99f5f3c496192b84',
                        'title' => 'Page 5',
                        'icon' => null,
                        'priority' => 300,
                        'url' => null,
                        'path' => ['Page 2', 'Page 5',],
                        'isActive' => false,
                        'attributes' => '',
                        'badge' => null,
                    ],
                ],
                'hasChild' => true,
                'id' => '9cd0e179d2e28222d91e2415d0eb8ade',
                'title' => 'Page 2',
                'icon' => null,
                'priority' => 100,
                'url' => null,
                'path' => ['Page 2'],
                'isActive' => false,
                'attributes' => ' class="has-child"',
                'badge' => null,
            ],
            'badfcb348b9ac6607ac264d4b9be5964' => [
                'child' => [
                    '6a6bc5bc485d72522e9a37c80ba127b7' => [
                        'child' => [],
                        'hasChild' => false,
                        'id' => '85fca741a01d8b0cb6c87f5fc729ffd0',
                        'title' => 'Page 8',
                        'icon' => null,
                        'priority' => 200,
                        'url' => null,
                        'path' => ['Page 4', 'Page 8',],
                        'isActive' => false,
                        'attributes' => '',
                        'badge' => null,
                    ],
                    '28950881c8300e328f3ee2530eb1a565' => [
                        'child' => [],
                        'hasChild' => false,
                        'id' => 'ce1f93d8120197b74c2627b38176b507',
                        'title' => 'Page 7',
                        'icon' => null,
                        'priority' => 300,
                        'url' => null,
                        'path' => ['Page 4', 'Page 7',],
                        'isActive' => false,
                        'attributes' => '',
                        'badge' => null,
                    ],
                ],
                'hasChild' => true,
                'id' => 'badfcb348b9ac6607ac264d4b9be5964',
                'title' => 'Page 4',
                'icon' => null,
                'priority' => 100,
                'url' => null,
                'path' => ['Page 4',],
                'isActive' => false,
                'attributes' => ' class="has-child"',
                'badge' => null,
            ],
            'cdc737b3649adb23c0561b9a9e4295ee' => [
                'child' => [],
                'hasChild' => false,
                'id' => 'cdc737b3649adb23c0561b9a9e4295ee',
                'title' => 'Page 3',
                'icon' => null,
                'priority' => 600,
                'url' => null,
                'path' => ['Page 3',],
                'isActive' => false,
                'attributes' => '',
                'badge' => null,
            ],
            'dffded34c96876a50a41f3a69c15e186' => [
                'child' => [],
                'hasChild' => false,
                'id' => 'dffded34c96876a50a41f3a69c15e186',
                'title' => 'Page 1',
                'icon' => null,
                'priority' => 800,
                'url' => null,
                'path' => ['Page 1',],
                'isActive' => false,
                'attributes' => '',
                'badge' => null,
            ],
        ], $navigation->toArray());
    }
}
