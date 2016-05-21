<?php

use KodiComponents\Navigation\Contracts\PageInterface;
use KodiComponents\Navigation\Page;
use Mockery as m;

class PageTest extends TestCase
{
    /**
     * @covers Page::__constructor
     */
    public function testConstructor()
    {
        $page = new Page('Title', 'http://site.com', 'pageId', 550, 'fa fa-user');

        $this->assertInstanceOf(PageInterface::class, $page);
        $this->assertEquals(0, $page->countPages());
    }

    /**
     * @covers Page::addPage
     */
    public function testAddPage()
    {
        $page = new Page('Title');

        $page->addPage('Page 2');

        $this->assertEquals(1, $page->countPages());

        $this->assertTrue($page->hasChild());

        $child = $page->getPages()->first();

        $this->assertEquals('Page 2', $child->getTitle());
        $this->assertTrue($child->isChild());
        $this->assertTrue($child->isChildOf($page));
    }

    /**
     * @covers Page::setPages
     */
    public function testSetPages()
    {
        $page = new Page('Title');

        $page->setPages(function (PageInterface $page) {
            $page->addPage('Page 1');
            $page->addPage('Page 2');

            $page->addPage('Page 3')->setPages(function (PageInterface $page) {
                $page->addPage('Page 4');

                $page->addPage('Page 5')->setPages(function (PageInterface $page) {
                    $page->addPage('Page 6');
                    $page->addPage('Page 7');
                });
            });
        });

        $this->assertEquals(7, $page->countPages());
        $this->assertTrue($page->hasChild());
    }

    /**
     * @covers Page::getId
     */
    public function testGetId()
    {
        $page = new Page('Page 1');
        $page2 = $page->addPage('Page 2');

        $this->assertEquals(md5('Page 1'), $page->getId());
        $this->assertEquals(md5('Page 1/Page 2'), $page2->getId());
    }

    /**
     * @covers Page::setId
     */
    public function testSetId()
    {
        $page = new Page('Page 1');
        $page->setId('pageId');

        $this->assertEquals('pageId', $page->getId());
    }

    /**
     * @covers Page::getTitle
     */
    public function testGetTitle()
    {
        $page = new Page('Page 1');

        $this->assertEquals('Page 1', $page->getTitle());
    }

    /**
     * @covers Page::setTitle
     */
    public function testSetTitle()
    {
        $page = new Page('Page 1');

        $page->setTitle('Page 2');
        $this->assertEquals('Page 2', $page->getTitle());
    }

    /**
     * @covers Page::getIcon
     * @covers Page::setIcon
     */
    public function testGetIcon()
    {
        $page = new Page();

        $page->setIcon('fa fa-test');
        $this->assertEquals('<i class="fa fa-test"></i>', $page->getIcon());
    }

    /**
     * @covers Page::getUrl
     * @covers Page::setUrl
     */
    public function testGetUrl()
    {
        $page = new Page('Page 1', 'site.com');

        $this->assertEquals(url('site.com'), $page->getUrl());

        $page->setUrl('http://test.com');
        $this->assertEquals('http://test.com', $page->getUrl());

        $url = m::mock(\Illuminate\Contracts\Routing\UrlGenerator::class);
        $url->shouldReceive('full')->once();

        $page->setUrl($url);
        $page->getUrl();
    }

    /**
     * @covers Page::getPath
     */
    public function testGetPath()
    {
        $page = new Page('Page 1');

        $page2 = $page->addPage('Page 2');
        $page3 = $page2->addPage('Page 3');
        $page4 = $page3->addPage('Page 4');

        $this->assertEquals(['Page 1', 'Page 2', 'Page 3'], $page3->getPath());
    }

    /**
     * @covers Page::getPriority
     * @covers Page::setPriority
     */
    public function testGetPriority()
    {
        $page = new Page('Page 1');

        $page->setPriority('Test');
        $this->assertEquals(0, $page->getPriority());

        $page->setPriority('1000');
        $this->assertEquals(1000, $page->getPriority());

        $page->setPriority(-500);
        $this->assertEquals(-500, $page->getPriority());

        $page->setPriority(-5, 00);
        $this->assertEquals(-5, $page->getPriority());
    }

    /**
     * @covers Page::isActive
     * @covers Page::setActive
     */
    public function testSetActive()
    {
        $page = new Page('Page 1');

        $page2 = $page->addPage('Page 2');
        $page3 = $page2->addPage('Page 3');
        $page4 = $page3->addPage('Page 4');

        $this->assertFalse($page->isActive());
        $this->assertFalse($page2->isActive());
        $this->assertFalse($page3->isActive());
        $this->assertFalse($page4->isActive());

        $page3->setActive();
        $this->assertTrue($page->isActive());
        $this->assertTrue($page2->isActive());
        $this->assertTrue($page3->isActive());
        $this->assertFalse($page4->isActive());
    }

    /**
     * @covers Page::getParent
     * @covers Page::isChild
     * @covers Page::isChildOf
     */
    public function testGetParent()
    {
        $page = new Page('Page 1');

        $page2 = $page->addPage('Page 2');
        $page3 = $page2->addPage('Page 3');
        $page4 = $page3->addPage('Page 4');

        $this->assertEquals($page, $page2->getParent());
        $this->assertEquals($page2, $page3->getParent());

        $this->assertTrue($page2->isChild());
        $this->assertTrue($page2->isChildOf($page));

        $this->assertEquals($page3, $page4->getParent());
        $this->assertTrue($page3->isChild());
        $this->assertTrue($page3->isChildOf($page2));

        $this->assertTrue($page4->isChild());
        $this->assertTrue($page4->isChildOf($page3));

        $this->assertFalse($page4->isChildOf($page2));

        $this->assertNull($page->getParent());
        $this->assertNotEquals($page2, $page4->getParent());
    }

    /**
     * @covers Page::getLevel
     */
    public function testGetLevel()
    {
        $page = new Page('Page 1');

        $page2 = $page->addPage('Page 2');
        $page3 = $page2->addPage('Page 3');
        $page4 = $page3->addPage('Page 4');

        $this->assertEquals(1, $page2->getLevel());
        $this->assertEquals(2, $page3->getLevel());
        $this->assertEquals(3, $page4->getLevel());
    }

    /**
     * @covers Page::getAccessLogic
     * @covers Page::setAccessLogic
     * @covers Page::checkAccess
     */
    public function testGetAccessLogic()
    {
        $page = new Page('Page 1');

        $page->setAccessLogic(function (PageInterface $page) {
            return strpos($page->getTitle(), 'Page') !== false;
        });

        $page2 = $page->addPage('Page 2');
        $page3 = $page2->addPage('Page 3');

        $page3->setAccessLogic(function (PageInterface $page) {
            return 'Page 2' == $page->getTitle();
        });

        $this->assertTrue(is_callable($page->getAccessLogic()));
        $this->assertTrue(is_callable($page3->getAccessLogic()));
        $this->assertTrue(is_callable($page2->getAccessLogic()));

        $this->assertTrue($page->checkAccess());
        $this->assertTrue($page2->checkAccess());
        $this->assertFalse($page3->checkAccess());
    }

    /**
     * @covers Page::toArray
     */
    public function testToArray()
    {
        $page = new Page('Title', 'http://site.com', 'pageId', 550, 'fa fa-user');

        $this->assertEquals([
            'child' => [],
            'hasChild' => false,
            'id' => 'pageId',
            'title' => 'Title',
            'icon' => '<i class="fa fa-user"></i>',
            'priority' => 550,
            'url' => 'http://site.com',
            'path' => ['Title'],
            'isActive' => false,
            'attributes' => '',
            'badge' => null,
        ], $page->toArray());
    }
}
