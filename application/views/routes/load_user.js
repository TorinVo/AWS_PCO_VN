var arr_user = [];

function load_user(){

	$.ajax({
		url: '../php/load_user.php',
		async: false,
		dataType: 'json',
		type: 'post',
		
		success: function(result) {
			if(result['result']==true){
				// alert("success");
				
				var parsed = result["arr"];

				var arr = [];

				var coord = [];

				for(var i in parsed){

					arr.push(parsed[i]);

					coord.push( new google.maps.LatLng(parseFloat(arr[i].lat), parseFloat(arr[i].lng ) ) );
					
					arr_user.push({
						overlay: new google.maps.Marker({
							position: coord[0],
							title: arr[i].user_id,
							// map: map
						}),

						user_id: arr[i].user_id
					});
				
					coord = [];
					
				}
			}
		},
		error: function(xhr, desc, err) {
			alert("error");
			console.log(xhr);
			console.log("Details: " + desc + "\nError:" + err);
		}
	}); // end ajax call		
}
