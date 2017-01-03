//Document ready : Executes when document is loaded
$("document").ready(function()
	{
		//When user click on btn_download
		$(".btn_download").click(function()
			{
				//Show Modal
				$("#downloading_model").modal({backdrop: 'static', keyboard: false}).show();
				$.ajax
				(
					{
						url: "download_album.php",
						type: 'POST',
						data:
						{
							album_id : $(this).data('album-id')
						},
						dataType: 'json',
						success: function(response)
						{
							if(response.flag==1)
							{
								$(".zip_download_link").show();
								$(".response_message").text(response.message);
								$(".zip_download_link").find('a').attr('href', response.download_link);
							}
							else
							{
								$(".response_message").text(response.message);
							}
						}
					});
			});
		//When user click on Download Selected Or Download All button
		$(".btn_download_multiple").click(function()
			{
				$("#action_type").val($(this).data('action-type'));
				//Show Modal
				$("#downloading_model").modal({backdrop: 'static', keyboard: false}).show();
				$.ajax
				(
					{
						url: "download_album.php",
						type: 'POST',
						data:$("#frm_albums").serialize(),
						dataType: 'json',
						success: function(response)
						{
							if(response.flag==1)
							{
								$(".zip_download_link").show();
								$(".response_message").text(response.message);
								$(".zip_download_link").find('a').attr('href', response.download_link);
							}
							else
							{
								$(".response_message").text(response.message);
							}
						}
					});
				return false;
			});
			
			//When user click on Move Selected or Move All
			$(".btn_move_multiple").click(function()
			{
				//Check atleast one checkbox is checked or not
				if($('#frm_albums input[type=checkbox]:checked').length==0 && $(this).data('action-type')!="all")
				{
					$("#moving_model").find(".response_message").text("Please select atleast one album to move to picasa.");			
					$(".close_moving_modal").show();
					$("#moving_model").modal({backdrop: 'static', keyboard: false}).show();
					return false;
				}
				else
				{
					$("#moving_model").find(".response_message").text("Please wait while moving your album photos to picasa...");	
					$("#moving_model").modal({backdrop: 'static', keyboard: false}).show();
					$("#action_type").val($(this).data('action-type'));
					$("#frm_albums").attr("action", "picasa_move.php");
					$("#frm_albums").submit();
				}
							
			});
			
			//When user click on Single Album Move button
			$(".btn_move").click(function()
			{
				var album_id = $(this).data("album-id");
				$("#chk_"+album_id).prop('checked', true);
				$("#moving_model").find(".response_message").text("Please wait while moving your album photos to picasa...");
				$("#moving_model").modal({backdrop: 'static', keyboard: false}).show();
				$("#action_type").val("selected");
				$("#frm_albums").attr("action", "picasa_move.php");
				$("#frm_albums").submit();
				
			});
		//Downloading Modal close event -- When user close downloading dailog box
		$('#downloading_model').on('hidden.bs.modal', function ()
			{
				$(".zip_download_link").hide();
				$(".response_message").text("Please wait while preparing your file to download...");
				$(".zip_download_link").find('a').attr('href', "#");
			});
		
		//When in URL #moved found
		if(window.location.hash.substr(1)=='moved')
		{
			$("#moving_model").find(".response_message").text("Your album has been moved to Picasa.");			
			$(".close_moving_modal").show();
			$("#moving_model").modal({backdrop: 'static', keyboard: false}).show();
		}
		
		//When in URL #move_album found
		if(window.location.hash.substr(1)=='move_album')
		{
			$("#moving_model").find(".response_message").text("Please wait while moving your album photos to picasa...");
			$("#moving_model").modal({backdrop: 'static', keyboard: false}).show();
			window.location.href = "picasa_move.php";			
		}

	});