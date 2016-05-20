<?php

namespace KodiComponents\Navigation;

use Illuminate\Routing\UrlGenerator;
use KodiComponents\Support\HtmlAttributes;
use KodiComponents\Navigation\Contracts\PageInterface;
use KodiComponents\Navigation\Contracts\BadgeInterface;

class Page extends Navigation implements PageInterface
{
    use HtmlAttributes;

    /**
     * @var string
     */
    protected $id;

    /**
     * @var string
     */
    protected $title;

    /**
     * Menu item icon.
     * @var string
     */
    protected $icon;

    /**
     * Menu item url.
     * @var string
     */
    protected $url;

    /**
     * @var Badge
     */
    protected $badge;

    /**
     * @var int
     */
    protected $priority = 100;

    /**
     * @var bool
     */
    protected $active = false;

    /**
     * @var Page
     */
    protected $parent;

    /**
     * @param string|array|PageInterface|null $page
     *
     * @return Page
     */
    public function addPage($page = null)
    {
        $page = parent::addPage($page);

        $page->setParent($this);

        return $page;
    }

    /**
     * @return string
     */
    public function getId()
    {
        if (is_null($this->id)) {
            return md5(implode('/', $this->getPath()));
        }

        return $this->id;
    }

    /**
     * @param string $id
     *
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     *
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * @param string $icon
     *
     * @return $this
     */
    public function setIcon($icon)
    {
        $this->icon = "<i class=\"{$icon}\"></i>";

        return $this;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        if (strpos($this->url, '://') !== false) {
            return $this->url;
        }

        if (is_string($this->url)) {
            $this->url = url($this->url);
        }

        if ($this->url instanceof UrlGenerator) {
            return $this->url->full();
        }

        return $this->url;
    }

    /**
     * @param string $url
     *
     * @return $this
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return array
     */
    public function getPath()
    {
        $data = [
            $this->getTitle(),
        ];

        $page = $this;

        while (! is_null($page = $page->getParent())) {
            $data[] = $page->getTitle();
        }

        return array_reverse($data);
    }

    /**
     * @return array
     */
    public function getPathArray()
    {
        $data = [
            $this->toArray(),
        ];

        $page = $this;

        while (! is_null($page = $page->getParent())) {
            $data[] = $page->toArray();
        }

        return array_reverse($data);
    }

    /**
     * @return Badge
     */
    public function getBadge()
    {
        return $this->badge;
    }

    /**
     * @param BadgeInterface $badge
     *
     * @return $this
     */
    public function setBadge(BadgeInterface $badge)
    {
        $this->badge = $badge;

        return $this;
    }

    /**
     * @param string   $value
     * @param \Closure $closure
     *
     * @return $this
     */
    public function addBadge($value, \Closure $closure = null)
    {
        $this->badge = app(BadgeInterface::class, [$value]);

        if (is_callable($closure)) {
            call_user_func($closure, $this->badge);
        }

        return $this;
    }

    /**
     * @return int
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * @param int $priority
     *
     * @return $this
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;

        return $this;
    }

    /**
     * @return bool
     */
    public function isActive()
    {
        return $this->active;
    }

    /**
     * @return $this
     */
    public function setActive()
    {
        $this->active = true;

        if (! is_null($this->getParent())) {
            $this->getParent()->setActive();
        }

        return $this;
    }

    /**
     * @param PageInterface $page
     *
     * @return $this
     */
    public function setParent(PageInterface $page)
    {
        $this->parent = $page;

        return $this;
    }

    /**
     * @return PageInterface
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @return Closure
     */
    public function getAccessLogic()
    {
        if (! is_callable($this->accessLogic)) {
            if (! is_null($parent = $this->getParent())) {
                return $parent->getAccessLogic();
            }
        }

        return parent::getAccessLogic();
    }

    /**
     * @return bool
     */
    public function checkAccess()
    {
        $accessLogic = $this->getAccessLogic();

        if (is_callable($accessLogic)) {
            return call_user_func($accessLogic, $this);
        }

        return $accessLogic;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        if ($this->isActive()) {
            $this->setHtmlAttribute('class', config('navigation.class.active', 'active'));
        }

        if ($this->hasChild()) {
            $this->setHtmlAttribute('class', config('navigation.class.has_child', 'has-child'));
        }

        return [
            'child'      => parent::toArray(),
            'hasChild'   => $this->hasChild(),
            'id'         => $this->getId(),
            'title'      => $this->getTitle(),
            'icon'       => $this->getIcon(),
            'priority'   => $this->getPriority(),
            'url'        => $this->getUrl(),
            'path'       => $this->getPath(),
            'isActive'   => $this->isActive(),
            'attributes' => $this->htmlAttributesToString(),
            'badge'      => $this->getBadge(),
        ];
    }

    /**
     * @param string|null $view
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function render($view = null)
    {
        if (is_null($view)) {
            $view = config('navigation.view.navigation', 'navigation::page');
        }

        $data          = $this->toArray();
        $data['pages'] = $this->getPages();

        return view($view, $data)->render();
    }
}
