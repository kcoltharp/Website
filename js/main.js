$(function(){
	$('.tab-panels .tabs li').on('click', function(){
		var $panel = $(this).closest('.tab-panels');
		$panel.find('.tabs li.active').removeClass('active');
		$(this).addClass('active');
		var panelToShow = $(this).attr('rel');
		$panel.find('.panel.active').slideUp(300, showNextPanel);
		function showNextPanel(){
			$(this).removeClass('active');
			$('#' + panelToShow).slideDown(300, function(){
				$(this).addClass('active');
			});//end slideDown
		}//end showNextPanel function
	});//end tab-panels->click or tabs li->click functions

	$('#itemList').on('click', 'div#item', function(event){
		event.preventDefault();
		var item = $('div#item');
		var $this = $(this);
		$(this).toggleClass('done');
	});

	$('#add-item').on('click', addItem);
	$('#del-item').on('click', delItems);
});//end jquery ready

function delItems(){
	$('div#item').each(function(){
		if($(this).hasClass('done')){
			var itemID = $(this).data('itemid');
			var itemName = $(this).text();

			$.ajax({
				url: "php/delItems.php",
				type: "GET",
				data: {itemID: itemID},
				async: false,
				success: function(data){
					$('#itemList').empty();
					$('#itemList').append(data);
					document.getElementById('newItem').value = "";
				}
			});
		}
	});
}

function getItems(){
	$.ajax({
		url: "php/getItems.php",
		type: "GET",
		async: false,
		success: function(data){
			$('#itemList').append(data);
		}

	});
}

function addItem(){
	var item = $('#newItem').val();
	$.ajax({
		url: "php/addItem.php",
		type: "GET",
		data: {item: item},
		async: false,
		success: function(data){
			$('#itemList').empty();
			$('#itemList').append(data);
			document.getElementById('newItem').value = "";
		}
	});
}



function validateForm(form){
	fail = validateUsername(form.username.value);
	fail += validatePassword(form.password.value);

	if(fail === ""){
		return true;
	}else{
		alert(fail);
		return false;
	}//end if...else
}//end function validateForm

function validateUsername(field){
	if(field === ""){
		return "No username was entered!\n";
	}else{
		return "";
	}//end if...else
}//end function validateUsername

function validatePassword(field){
	var n = field.length;
	if(field === ""){
		return "No password was entered!\n";
	}else if(n > 32){
		return "The password you entered was to long!\n";
	}else{
		return "";
	}//end if...else
}//end function validatePassword
