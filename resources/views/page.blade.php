@if($hasChild)
<li {!! $attributes !!}>
    <a href="#" >
        {!! $icon !!}
        <span>{!! $title !!}</span>

        @if($badges->count() > 0)
            <span class="sidebar-page-badges">
        @foreach($badges as $badge)
                    {!! $badge->render() !!}
                @endforeach
        </span>
        @endif

        <i class="fa fa-angle-left pull-right"></i>
    </a>

    <ul class="treeview-menu">
        @foreach($child as $subPage)
           {!! $subPage->render() !!}
        @endforeach
    </ul>
</li>
@else
<li {!! $attributes !!}>
    <a href="{{ $url }}">
        {!! $icon !!}
        <span>{!! $title !!}</span>

        @if($badges->count() > 0)
        <span class="sidebar-page-badges">
        @foreach($badges as $badge)
        {!! $badge->render() !!}
        @endforeach
        </span>
        @endif
    </a>
</li>
@endif