@if($page->hasChild())
<li {!! $page->htmlAttributesToString() !!}>
    <a href="#" >
        {!! $page->getIcon() !!}
        <span>{!! $page->getTitle() !!}</span>
        <i class="fa fa-angle-left pull-right"></i>
    </a>

    <ul class="treeview-menu">
        @foreach($pages as $child)
           {!! $child->render() !!}
        @endforeach
    </ul>
</li>
@else
<li {!! $page->htmlAttributesToString() !!}>
    <a href="{{ $page->getUrl() }}">
        {!! $page->getIcon() !!}
        <span>{!! $page->getTitle() !!}</span>
        {!! $page->getBadge() !!}
    </a>
</li>
@endif