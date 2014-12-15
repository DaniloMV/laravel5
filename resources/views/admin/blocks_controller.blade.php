<style>
.wrapper {
	margin: auto;
	width: 800px;
	position: relative;
}
.sidebar {
	background: lightblue;
	height: 500px;
}
.container {
	border: 1px solid red;
	height: 500px;
	position: relative;
}
.header {
	width: 100%;
	height:80px;
	background: lightblue;
	position: relative;
}
.menu {
	width: 100%;
	height:40px;
	background: pink;
	position: relative;
}
.left {
	float:left;
	width: 30%;
	height: 300px;
	background: #888;
	position: relative;
}
.middle {
	float:left;
	width: 70%;
	height: 300px;
	background: lightgreen;
	position: relative;
}
.footer {
	height:80px;
	background: yellow;
	width: 100%;
	clear: both;
	position: relative;
}
.submit {
	width: 100%;
	text-align: center;
	margin-top: 30px;
}
button {
	display: inline-block;
}
ul.droptrue { list-style-type: none; margin: 1%; padding: 0; background: #eee; width: 98%; height:98%; position: absolute; top:1%; left:1%;}
ul.droptrue li { font-size: 1.2em; width: 100%; }
.ui-state-highlight { background: red; min-height: 20px;}

.tabs {
	display: table;
	width: 100%;
}

.tabs dd {
	display: table-cell;
	float: none;
}

.tabs dd a {
	text-align: center;
}

</style>
<script>
	$(function() {
	$( "ul.droptrue" ).sortable({
		connectWith: "ul",
	 	placeholder: "ui-state-highlight",
	 	receive: function(){
			var region = $(this).attr('data-region');
			var counter = 0;
			$('ul[data-region='+region+'] li input').each( function() {
				block_id = $(this).attr('data-id');
				$(this).attr('name', 'block['+region+']['+block_id+']');
				$(this).val(++counter);
			});
		},
		update: function(event, ui) {
			var region = $(this).attr('data-region');
			var counter = 0;
			$('ul[data-region='+region+'] li input').each( function() {
				$(this).val(++counter);
				});
			}
		}).disableSelection();
	});
</script>

<div class="row">
	<div class="small-3 large-3 columns sidebar">
		@include('admin/region', array('block'=> $block, 'id' => '0' ))
	</div>
	<div class="small-9 large-6 columns">
		<div class="container">
			<div class="header">
				@include('admin/region', array('block'=> $block, 'id' => '1' ))
			</div>
			<div class="menu">
				@include('admin/region', array('block'=> $block, 'id' => '2' ))
			</div>
			<div class="content">
				<div class="left">
					@include('admin/region', array('block'=> $block, 'id' => '3' ))
				</div>
				<div class="middle">
					@include('admin/region', array('block'=> $block, 'id' => '4' ))
				</div>
			</div>
			<div class="footer">
				@include('admin/region', array('block'=> $block, 'id' => '5' ))
			</div>
		</div>
	</div>
	<div class="small-0 large-3 columns">
	</div>
</div>