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
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function render()
    {
        return view(config('navigation.view.badge', 'navigation::badge'), $this->toArray());
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->render();
    }
}
