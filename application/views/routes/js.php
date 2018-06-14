<!-- <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script> -->
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCiOq8kMZrsigU3tKmRosWjiOHual56a2M&libraries=drawing"></script>
<script src="<?=url::base()?>plugins/jQuery-Tags-Input/dist/jquery.tagsinput.min.js"></script>
<script>
	var Dom_Active,Table_Active;
	var textSelect;
	var geocoder;
	var all 				  = '';
	var goo 				  = google.maps;
  var dialog        = $('#new-route');
	var selected              = [];
	var unselected            = [];
	var idRoute 			  = 0;
	var setActive 			  = 0;
	var arr_service 		  = [];
	var arr_zone 			  = [];
	var Total_Record_Filter   = 0;
	var Total_Record          = 0;
	var image = {
	    url: 'https://developers.google.com/maps/documentation/javascript/examples/full/images/beachflag.png',
	    // This marker is 20 pixels wide by 32 pixels high.
	    size: new google.maps.Size(20, 32),
	    // The origin for this image is (0, 0).
	    origin: new google.maps.Point(0, 0),
	    // The anchor for this image is the base of the flagpole at (0, 32).
	    anchor: new google.maps.Point(0, 32)
  	};

	function NewSet(t_this){
    	$('<li class=""><a data-toggle="tab" onclick="SetActive(this)" idSet="'+all+'" class="tab_set" href="#set_'+all+'">Set '+all+' *</a></li>').insertBefore(t_this);
    	$.post('<?=url::base()?>routes/getNewSet',{idSet: all})
    	.success(function (result) {
    		$('#routes').append(result);
    	})
    	all = parseInt(all) + 1;
    }

	function Showmap(route_id,t_this) {
    	var ele = $(t_this);
    	if(ele.hasClass('btn-info')){
	    	$.ajax({
				url: '<?=url::base()?>routes/loadZone',
				async: false,
				type: 'post',
				data: {
					route_id: route_id
				},
				success: function(result) {
					var data = JSON.parse(result);
					var all = data['map'];
					
					if(all.length != 0){
						var total = data['total'];
						for(var i in total){
							var row = [];
							var service_id;
							for(var j in all){
								if(all[j].date == total[i].date){
									row.push(all[j]);
								}
							}
							arr_zone.push({data: row, route_id: route_id});
						}
						ele.removeClass('btn-info');
						ele.addClass('btn-primary');
						idRoute = route_id;
					}
					else{
						var ans = confirm('Not data to show!Do you want to set zone for route?');
						if(ans){
							ele.removeClass('btn-info');
    						ele.addClass('btn-primary');
    						idRoute = route_id;
						}
					}
					var service = data['service'];

					for(var i in service){
						var address = service[i].service_address_1;
						arr_service.push({address: address, route_id: service[i].service_route, service_id: service[i].service_id});
					}
					// console.log(service[0].service_route);

					var routeInfo = data['route_info'];
					$('#route-name_' + setActive).html('Route ' + routeInfo[0].route_no + ' - ' + routeInfo[0].route_name);
				},
				error: function(xhr, desc, err) {
					alert("error");
					console.log(xhr);
					console.log("Details: " + desc + "\nError:" + err);
				}
			});
		}
		else{
			ele.removeClass('btn-primary');
    		ele.addClass('btn-info');
    		idRoute = 0;

    		removeZone(route_id);
    		removeService(route_id);
		}
		Route_Active.DrawMap(arr_service,arr_zone);
    }
    function checkMap(route_id) {
  		for(var i in arr_zone){
  			if(arr_zone[i].route_id == route_id)
  				return 'primary';
  		}
  		for(var i in arr_service){
  			if(arr_service[i].route_id == route_id)
  				return 'primary';
  		}
  		return 'info';
  	}

  	function removeZone(route_id) {
  		var index = [];

  		for(var i in arr_zone){
  			if(arr_zone[i].route_id == route_id)
  				index.push(i);
  		}

  		for(var j = index.length - 1; j >= 0; j--){
  			arr_zone.splice(index[j],1);
  		}
  	}
  	function removeService(route_id){
  		var index = [];

  		for(var i in arr_service){
  			if(arr_service[i].route_id == route_id)
  				index.push(i);
  		}

  		for(var j = index.length - 1; j >= 0; j--){
  			arr_service.splice(index[j],1);
  		}
  	}
    function NewRoute() {
      // Js_Top.show_loading();
      $.ajax({
        url: '<?=url::base()?>routes/addRouteHtml',
        method: 'POST',
        data: {
          idSet: setActive
        },
        success: function(result){
          $('#wrap-overlay').html(result);
          Js_Top.openNav();
          // Js_Top.hide_loading();
          jquery_plugins.MaskPhone();
          jquery_plugins.NoSpaceInput();
        }
      })
    }
    var vali_num = $('.no-validate');
    vali_num.addClass('hidden');
    function CheckExistingRoute(t_this) {
      var val = $(t_this).val();
      $.post('<?=url::base()?>routes/checkRouteID',{idRoute: val})
      .done(function(result) {
        if(result){
          $('.no-validate').removeClass('hidden');
        }
        else
          $('.no-validate').addClass('hidden');
      })
    }
  	function drawShape(data,type,map) {
  		if(type == 'CIRCLE'){
    		var center = {lat: parseFloat(data[0].latitude), lng: parseFloat(data[0].longitude)};
    		var radius = parseFloat(data[0].radius);
			var overlay = new google.maps.Circle({
				strokeOpacity: 0.8,
				strokeWeight: 2,
				fillOpacity: 0.35,
				center: center,
				// map: map,
				radius: radius
			});
			overlay.setMap(map);
    	}
    	if(type == 'RECTANGLE'){
    		var location1 = {lat: parseFloat(data[0].latitude), lng: parseFloat(data[0].longitude)};
    		var location2 = {lat: parseFloat(data[1].latitude), lng: parseFloat(data[1].longitude)};

    		// console.log(location1 + location2);

    		var bound = new google.maps.LatLngBounds(location1, location2);
    		var overlay = new google.maps.Rectangle({
				strokeOpacity: 0.8,
				strokeWeight: 2,
				fillOpacity: 0.35,
				bounds: bound
			});
			overlay.setMap(map);
    	}
    	if(type == 'POLYGON'){
    		var path = [];
    		for(var i in data){
    			path.push({lat: parseFloat(data[i].latitude), lng: parseFloat(data[i].longitude)});
    		}

    		var overlay = new google.maps.Polygon({
				strokeOpacity: 0.8,
				strokeWeight: 2,
				fillOpacity: 0.35,
				paths: path
			});
			overlay.setMap(map);
    	}
  	}
  	//Click tab to select a set
  	function SetActive(t_this) {
  		setActive = $(t_this).attr('idSet');
  		arr_zone = [];
  		arr_service = [];
  		idRoute = 0;

  		var waper = $('#routes');
  		Route_Active.init(waper);
  	}
  	//Click to active this set
  	function ActiveSet(t_this) {
  		var val = $(t_this).val();
  		$.post('<?=url::base()?>routes/setActiveSet',{setID: val},function (result) {
  			console.log(result);
  			location.reload();
  		})
  	}
    function EmptyItem() {
      alert('This has no items!');
    }
</script>
<script>
    $(document).ready(function () {
        // initMap();
        $.post('<?=url::base()?>routes/getAllSet',function (result) {
            $('#routes').html(result);
            var waper = $('#routes');
            // console.log(waper);
            Route_Active.init(waper);
        })

        $.post('<?=url::base()?>routes/countAllSet',function (result) {
        	var res = JSON.parse(result);
        	all = parseInt(res[0]) + 1;
        	setActive = parseInt(res[1].id);
        })

        $('.no-validate').addClass('hidden');

        $('#tags').tagsInput();
        // var goo = google.maps;
        // var map = new goo.Map(document.getElementById('map'), {});
        
        //  center: {lat: 37.3840875, lng: -122.0127955},
        //  zoom: 12
        // });
    })
</script>
<!-- <script src="load_zone.js"></script>
<script src="load_user.js"></script>
<script src="show_save_zone.js"></script> -->
