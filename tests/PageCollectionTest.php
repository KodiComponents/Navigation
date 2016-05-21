<?php

use KodiComponents\Navigation\Contracts\PageInterface;
use KodiComponents\Navigation\Page;

class PageCollectionTest extends TestCase
{
    /**
     * @covers PageCollection::push
     */
    public function testPush()
    {
        $collection = (new Page('Page'))->getPages();

        $collection->push(new Page('Page 1'));

        $this->assertEquals(1, $collection->count());

        $collection->each(function ($page, $key) {
            $this->assertEquals($page->getId(), $key);
        });
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testPushException()
    {
        $collection = (new Page('Page'))->getPages();
        $collection->push('test');
    }

    /**
     * @covers PageCollection::push
     */
    public function testPrepend()
    {
        $collection = (new Page('Page'))->getPages();

        $collection->prepend(new Page('Page 1'), 'test');

        $this->assertEquals(1, $collection->count());

        $collection->each(function ($page, $key) {
            $this->assertEquals($page->getId(), $key);
        });
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testPrependException()
    {
        $collection = (new Page('Page'))->getPages();
        $collection->prepend('test', 'test');
    }

    /**
     * @covers PageCollection::findByPath
     */
    public function testFindByPath()
    {
        $navigation = new \KodiComponents\Navigation\Navigation([
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

        $page = new Page('Page 1');
        $navigation->addPage($page);

        $page2 = $page->addPage('Page 2');
        $page3 = $page2->addPage('Page 3');
        $page4 = $page3->addPage('Page 4');

        $this->assertEquals($page3, $navigation->getPages()->findByPath('Page 1/Page 2/Page 3'));
        $this->assertEquals($page3, $navigation->getPages()->findByPath('Page 1|Page 2|Page 3', '|'));

        $this->assertEquals($page2, $navigation->getPages()->findByPath('Page 1/Page 2'));

        $this->assertInstanceOf(PageInterface::class, $navigation->getPages()->findByPath('Title/Title 1'));
    }



    /**
     * @covers PageCollection::findById
     */
    public function testFindById()
    {
        $navigation = new \KodiComponents\Navigation\Navigation([
            [
                'title' => 'Title',
                'pages' => [
                    [
                        'title' => 'Title 1',
                        'pages' => [
                            [
                                'title' => 'Title 2',
                                'id' => 'title_2',
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

        $found = $navigation->getPages()->findById('title_2');
        $this->assertEquals('Title 2', $found->getTitle());

        $found = $navigation->getPages()->findById('title_4');
        $this->assertNull($found);

        $found = $navigation->getPages()->findById(md5('Title 8'));
        $this->assertEquals('Title 8', $found->getTitle());

        $found = $navigation->getPages()->findById(md5('Title/Title 1/Title 2/Title 3'));
        $this->assertEquals('Title 3', $found->getTitle());
    }
}
