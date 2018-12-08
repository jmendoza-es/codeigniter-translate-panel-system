</div><!-- /content -->


	<div id="footer">


    </div>


</div> <!-- wrapper -->
<div class="modal fade" id="delete" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Eliminar traducción</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>¿Estás seguro de eliminar <b><span class="elemento"></span></b>?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success" onClick="eliminarElemento()">Si</button>
        <button type="button" class="btn btn-danger" data-dismiss="modal">No</button>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">

var deleteElement;
	
function eliminarElemento() {
	$('#'+deleteElement).css({background:'red', color:'#fff'}).fadeOut(function() { $(this).remove(); }); 
	$('#delete').modal('hide');
}

$(document).ready(function () {
	
	var newItemCount = 0;
	
	$('#filter').keydown(function(e) {
	  if (e.keyCode == '13') {
		 e.preventDefault();
		 e.stopPropagation();
	   }
	});

	$('#add_new').click(function () {
		var newItem = $('.searchable').first().clone();
		$(newItem).find('td').html('');
		$(newItem).find('td').first().html('<input class="form-control" type="text" placeholder="Valor..." />');
		$(newItem).find('td').last().html('<input class="form-control" type="text" placeholder="Valor..." />');
		$(newItem).addClass('newItem');
		$(newItem).attr("id", 'newItem'+newItemCount);
		$(newItem).prependTo($('.searchable').parent());
		addActions(newItem);
		$(newItem).find('td').first().find('input').focus();
	});
	
	function addActions(newItem) {
		$(newItem).find('input').first().on('keyup', function() {
			var newVal = $(this).val();
			$(this).parent().parent().find('input').last().attr('name','ci_language_'+newVal.toLowerCase());
		});
	}
	
	
	$('.actions').click(function () {
		$('#delete').modal('show');
		var selectedElement = $(this).next().html();
		deleteElement = 'row' + $(this).next().data('parent');
		$('.elemento').html(selectedElement);
	});
	
    $('#filter').keyup(function () {
		
		var rex = new RegExp($(this).val(), 'i');
        $('.searchable').hide();
        $('.searchable').filter(function () {
            return rex.test($(this).text());
        }).show();

    });
	
	$('.sortable th').click(function(e) {
		if($(this).hasClass('active')) {
			if(!$(this).hasClass('up')) {
				$(this).addClass('up');
			} else {
				$(this).removeClass('up');
			}
			e.stopPropagation();
		}
	});
	
	$('.sortable th').click(function(){
		$('.sortable th').removeClass('active');
		if(!$(this).hasClass('active')) {
			$(this).addClass('active');
		} 
		
		var table = $(this).parents('table').eq(0);
		var rows = table.find('tr:gt(0)').toArray().sort(comparer($(this).index()));
		this.asc = !this.asc;
		if (!this.asc){ rows = rows.reverse(); }
		for (var i = 0; i < rows.length; i++){ table.append(rows[i]); }
	});
	
	$(function() {

	  var mark = function() {

		// Read the keyword
		var keyword = $("#filter").val();


		// Remove previous marked elements and mark
		// the new keyword inside the context
		$(".markable").unmark({
		  done: function() {
			$(".markable").mark(keyword);
		  }
		});
	  };

	  $("#filter").on("input", mark);

	});
	
	function comparer(index) {
		return function(a, b) {
			var valA = getCellValue(a, index), valB = getCellValue(b, index)
			return $.isNumeric(valA) && $.isNumeric(valB) ? valA - valB : valA.toString().localeCompare(valB)
		}
	}
	
	function getCellValue(row, index){ return $(row).children('td').eq(index).text() }

});

</script>
</body>
</html>