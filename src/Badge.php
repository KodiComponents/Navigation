<?php

namespace KodiComponents\Navigation;

use KodiComponents\Support\HtmlAttributes;
use KodiComponents\Navigation\Contracts\BadgeInterface;

class Badge implements BadgeInterface
{
    use HtmlAttributes;

    /**
     * @var string
     */
    protected $value;

    /**
     * Badge constructor.
     *
     * @param null $value
     */
    public function __construct($value = null)
    {
        $this->setValue($value);

        $this->setHtmlAttribute('class', 'label pull-right');
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        if (! $this->hasClassProperty('label-', 'bg-')) {
            $this->setHtmlAttribute('class', 'label-primary');
        }

        return [
            'value'      => $this->getValue(),
            'attributes' => $this->htmlAttributesToString(),
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
            $view = config('navigation.view.badge', 'navigation::badge');
        }

        return view($view, $this->toArray());
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->render();
    }
}
