jQuery(document).ready(function ($){
	$('#jform_prodi').change(function(){
		var prodi = $(this).val();
		$.ajax({
			url: 'index.php?option=com_siak&view=jurusans&format=json&filter.prodi=' + prodi,
			dataType: 'json'
		}).done(function(data) {           
            
			$('#jform_jurusan').each(function() {
					$(this).empty();				
			});
            
            $('#jform_jurusan').append($('<option>').text("Pilih Konsentrasi").val(''));
			$.each(data.data, function (i, val) {
                var option = $('<option>');
				option.text( val.title ).val(val.id);
				$('#jform_jurusan').append(option);
			});
			$('#jform_jurusan').trigger('liszt:updated');
		});
	});
});