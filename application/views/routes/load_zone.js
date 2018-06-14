var arr_zone = [];
var arr_user_in_zone = [];

function load_zone(){

	$.ajax({
		url: '../php/load_zone.php',
		async: false,
		dataType: 'json',
		type: 'post',
		
		success: function(result) {
			if(result['result']==true){
				// alert("success");
				
				var parsed = result["arr"];

				var arr = [];

				var coord = [];

				var radius = 0;

				

				for(var i in parsed){

					arr.push(parsed[i]);

					// set_overlay(arr)

					if(arr[i].type == "CIRCLE"){
						// coord.push( { lat: parseFloat(arr[i].coordinate_json.geometry[0]), lng: parseFloat(arr[i].coordinate_json.geometry[1]) } );
						coord.push( new google.maps.LatLng(parseFloat(arr[i].coordinate_json.geometry[0]), parseFloat(arr[i].coordinate_json.geometry[1] ) ) );
						radius = parseFloat(arr[i].coordinate_json.radius);
						// alert(coord[0].lat);
						// alert(radius);
						
						arr_zone.push({
							overlay: new google.maps.Circle({
								strokeOpacity: 0.8,
								strokeWeight: 2,
								fillOpacity: 0.35,
								center: coord[0],
								// map: map,
								radius: radius
							}),

							zone_number: arr[i].zone_number,
							type: "CIRCLE",
							user_in_zone: arr[i].user_in_zone
						});

						// alert(arr_zone[i].user_in_zone[2]);

						

						radius = 0;
					}
					else if(arr[i].type == "RECTANGLE"){
						

						// coord.push({lat: arr[i].coordinate_json.geometry[j][0], lng: arr[i].coordinate_json.geometry[j][1]});

						coord.push( new google.maps.LatLng(parseFloat(arr[i].coordinate_json.geometry[0][0]), parseFloat(arr[i].coordinate_json.geometry[0][1] ) ) );
						coord.push( new google.maps.LatLng(parseFloat(arr[i].coordinate_json.geometry[1][0]), parseFloat(arr[i].coordinate_json.geometry[1][1] ) ) );	

						var bound = new google.maps.LatLngBounds(coord[0], coord[1]);

						arr_zone.push({
							overlay: new google.maps.Rectangle({
								strokeOpacity: 0.8,
								strokeWeight: 2,
								fillOpacity: 0.35,
								// map: map,
								bounds: bound
							}),

							zone_number: arr[i].zone_number,
							type: "RECTANGLE",
							user_in_zone: arr[i].user_in_zone
						});	
					}
					else{
						for(var j in arr[i].coordinate_json.geometry[0]){
							coord.push( new google.maps.LatLng(parseFloat(arr[i].coordinate_json.geometry[0][j][0]), parseFloat(arr[i].coordinate_json.geometry[0][j][1] ) ) );	
						}
						

						arr_zone.push({
							overlay: new google.maps.Polygon({
								strokeOpacity: 0.8,
								strokeWeight: 2,
								fillOpacity: 0.35,
								// map: map,
								paths: coord
							}),

							zone_number: arr[i].zone_number,
							type: "POLYGON",
							user_in_zone: arr[i].user_in_zone
						});
					}

					

					coord = [];

					// arr_zone[i].overlay.setMap(map);
				}
			}

			var a = 1;

			// window.zone = arr_zone;

			// alert(window.arr_zone.length);
			// return arr_zone;
			// return a;
		},
		error: function(xhr, desc, err) {
			alert("error");
			console.log(xhr);
			console.log("Details: " + desc + "\nError:" + err);
		}
	}); // end ajax call		
}
