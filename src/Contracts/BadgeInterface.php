<?php

namespace KodiComponents\Navigation\Contracts;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Contracts\View\Factory;

interface BadgeInterface extends Renderable, Arrayable
{
    /**
     * @return string
     */
    public function getValue();

    /**
     * @param Factory $view
     *
     * @return void
     */
    public function setViewFactory(Factory $view);
}
