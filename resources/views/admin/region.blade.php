<ul id="sortable{{ $id }}" data-region='{{ $id }}' class="droptrue">
	@if (!empty($block[$id]))
		@foreach ($block[$id] as $position => $blok)
			<li class="ui-state-default">
			<input type="hidden" data-id="{{ $blok['id'] }}" name="block[{{ $id }}][{{ $blok['id'] }}]" value="{{ $position }}">
				{{ $blok['name'] }}
                                <a style="display: inline-block;float: right;" href="{{ url('admin/blocks/edit/pl_'.$blok['id']) }}">edit</a>
			</li>
		@endforeach
	@endif
</ul>