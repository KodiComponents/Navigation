<section class="sidebar">
	@yield('sidebar.top')

	<ul class="sidebar-menu">
		@yield('sidebar.ul.top')

		@foreach($pages as $page)
			{!! $page->render() !!}
		@endforeach

		@yield('sidebar.ul.bottom')
	</ul>

	@yield('sidebar.bottom')
</section>