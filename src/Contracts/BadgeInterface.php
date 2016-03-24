<?php

namespace KodiComponents\Navigation\Contracts;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Renderable;

interface BadgeInterface extends Renderable, Arrayable
{
    /**
     * @return string
     */
    public function getValue();
}
