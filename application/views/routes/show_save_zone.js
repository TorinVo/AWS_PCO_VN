var encodeString;
var path;
var drawingManager;
var all_overlays = [];
var i = 0;
var shapes = [];
var poly = [];

clearShapes = function(){
		
	for(var i=0; i < shapes.length; i++){
		
		shapes[i].setMap(null);
	}
	
	shapes=[];
};


function init_zone(callback, map){
	callback(map);
}


function initMap() {

	var goo = google.maps;

	var map = new goo.Map(document.getElementById('map'), {
		center: {lat: 37.3840875, lng: -122.0127955},
		zoom: 12
	});


	drawingManager = new goo.drawing.DrawingManager({

		map: map,

		drawingMode: goo.drawing.OverlayType.POLYGON,

		drawingControl: true,

		drawingControlOptions: {
			position: goo.ControlPosition.TOP_CENTER,
			drawingModes: [
				goo.drawing.OverlayType.CIRCLE,
				goo.drawing.OverlayType.POLYGON,
				goo.drawing.OverlayType.RECTANGLE
			]
		},

		markerOptions: {icon: 'images/beachflag.png'},
		
		circleOptions: {
			strokeColor: '#FF0000',
			fillColor: '#FF0000',
			strokeOpacity: 0.8,
			strokeWeight: 2,
			fillOpacity: 0.2,
			strokeWeight: 5,
			clickable: false,
			editable: true,
			zIndex: 1
		},

		rectangleOptions: {
			strokeColor: '#FF0000',
			fillColor: '#FF0000',
			strokeOpacity: 0.8,
			strokeWeight: 2,
			fillOpacity: 0.2,
			strokeWeight: 5,
			clickable: false,
			editable: true,
			zIndex: 1
		},

		polygonOptions: {
			strokeColor: '#FF0000',
			fillColor: '#FF0000',
			strokeOpacity: 0.8,
			strokeWeight: 2,
			fillOpacity: 0.2,
			strokeWeight: 5,
			clickable: false,
			editable: true,
			zIndex: 1
		},
		
		
		
	});
	
	load_zone();
	load_user();
	init_zone(map);

	goo.event.addListener(drawingManager, 'overlaycomplete', function(event) {
		
		var shape = event.overlay;
		shape.type = event.type;

		shapes.push(shape);


		var data=IO.IN(shapes, false);

		var user_in_zone = JSON.stringify(is_in_zone(shape));
		
		// alert(user_in_zone);

		var zone_number = prompt("Enter Zone Number");
		
		var save = false;
		while(save==false){	

			if(zone_number){

				$.ajax({
					url: '../php/check_zone.php',
					dataType: 'json',
					type: 'post',
					async: false,
					data: {zone_number: zone_number},
					
					success: function(result) {
						
						if(result['result']==true){
						
							$.ajax({
								url: '../php/save_zone.php',
								dataType: 'json',
								type: 'post',
								data: {shapes: JSON.stringify(data), msg:"asdfasdfasdf", zone_number: zone_number, user_in_zone: user_in_zone},
								
								success: function(result) {
									if(result['result']==true){
										alert("Zone Successfully Aded");
										clearShapes();
										load_zone();
										init_zone(map);
									}
									
								},
								error: function(xhr, desc, err) {
									alert("error");
									console.log(xhr);
									console.log("Details: " + desc + "\nError:" + err);
								}
							}); // end ajax call				

							save = true;
						}
						else{
							zone_number = prompt("Zone Number Already Exist, Enter Zone Number");
						}

					},
					error: function(xhr, desc, err) {
						
						console.log(xhr);
						console.log("Details: " + desc + "\nError:" + err);
					}
				}); // end ajax call	
				
			}
			else{

				clearShapes();

				// alert(window.arr_zone.length);

				break;
			}
		}
		
		
	});

	goo.event.addDomListener(document.getElementById('clear_zone'), 'click', clearShapes);

}

function is_in_zone(shape){

	var user_in_zone = [];

	for(var i in arr_user){

		// alert(shape.type);

		if(shape.type == 'polygon'){
			
			if(google.maps.geometry.poly.containsLocation(arr_user[i].overlay.position, shape)){
				user_in_zone.push({user_id: arr_user[i].user_id});
			}
		}
		else{
			if(shape.getBounds().contains(arr_user[i].overlay.position)){
				user_in_zone.push({user_id: arr_user[i].user_id});
			}	
		}	
	}
	
	return user_in_zone;

}

var IO = {
		//returns array with storable google.maps.Overlay-definitions
		IN:function(arr, //array with google.maps.Overlays
		encoded//boolean indicating if pathes should be stored encoded
		){
			var shapes = [];
			var goo = google.maps;
			var shape;
			var tmp;

			a = 0;

			for(var i = 0; i < arr.length; i++)
			{   
				shape = arr[i];
				
				tmp = { type: this.t_(shape.type), id: shape.id||null};

				// document.write(arr.length);
				// document.getElementById('encode').value = arr[i].type;
				// 
				// alert("arr len: " + arr.length);
				// alert("arr type: " + arr[i].type);
				// alert("tmp type: " + tmp.type);
				// alert("shape type: " + shape.type);
				// shape.type = shape.type.toUpperCase();
				// alert("SHAPE type: " + shape.type);

				// alert("arr radius: " + arr[i].getRadius());

				switch(tmp.type){
					
					case 'CIRCLE':
						tmp.radius=shape.getRadius();
						tmp.geometry=this.p_(shape.getCenter());
						break;
					case 'MARKER': 
						tmp.geometry=this.p_(shape.getPosition());   
						break;  
					case 'RECTANGLE': 
						tmp.geometry=this.b_(shape.getBounds()); 
						break;   
					case 'POLYLINE': 
						tmp.geometry=this.l_(shape.getPath(),encoded);
						break;   
					case 'POLYGON': 
						tmp.geometry=this.m_(shape.getPaths(),encoded);
						break;   
				}
			
				shapes.push(tmp);

				a++;
			}
			
			// alert("shape radius: " + shape.getRadius());
			// $.post("../php/save_zone.php", { test: i} );

			return shapes;
		},
		
		OUT:function(arr,//array containg the stored shape-definitions
		map//map where to draw the shapes
		){
			var shapes = [],
				goo=google.maps,
				map=map||null,
				shape,tmp;

			for(var i = 0; i < arr.length; i++){   
				shape=arr[i];       

				switch(shape.type){
					case 'CIRCLE':
						tmp=new goo.Circle({radius:Number(shape.radius),
						center:this.pp_.apply(this,shape.geometry)});
						break;
					case 'MARKER': 
						tmp=new goo.Marker({position:this.pp_.apply(this,shape.geometry)});
						break;  
					case 'RECTANGLE': 
						tmp=new goo.Rectangle({bounds:this.bb_.apply(this,shape.geometry)});
						break;   
					case 'POLYLINE': 
						tmp=new goo.Polyline({path:this.ll_(shape.geometry)});
						break;   
					case 'POLYGON': 
						tmp=new goo.Polygon({paths:this.mm_(shape.geometry)});
						break;   
				}

				tmp.setValues({map:map,id:shape.id})
			
				shapes.push(tmp);
			
			}

			return shapes;
		},
		
		l_:function(path,e){
			
			path=(path.getArray)?path.getArray():path;
			
			if(e){
				return google.maps.geometry.encoding.encodePath(path);
			}
			else{
				var r=[];
				for(var i=0;i<path.length;++i){
					
					r.push(this.p_(path[i]));
				}
				return r;
			}
		},

		ll_:function(path){
			if(typeof path==='string'){
				return google.maps.geometry.encoding.decodePath(path);
			}
			else{
				var r=[];
				for(var i=0;i<path.length;++i){

					r.push(this.pp_.apply(this,path[i]));
				}
				return r;
			}
		},

		m_:function(paths,e){
			var r=[];
			paths=(paths.getArray)?paths.getArray():paths;

			for(var i=0;i<paths.length;++i){

				r.push(this.l_(paths[i],e));
			}

			return r;
		},

		mm_:function(paths){

			var r=[];
			for(var i=0;i<paths.length;++i){

				r.push(this.ll_.call(this,paths[i]));
			}
		
			return r;
		},

		p_:function(latLng){
			return([latLng.lat(),latLng.lng()]);
		},

		pp_:function(lat,lng){
			return new google.maps.LatLng(lat,lng);
		},

		b_:function(bounds){
			return([this.p_(bounds.getSouthWest()),	 this.p_(bounds.getNorthEast())]);
		},
		
		bb_:function(sw,ne){
			return new google.maps.LatLngBounds(this.pp_.apply(this,sw), this.pp_.apply(this,ne));
		},
		
		t_:function(s){
			var t=['CIRCLE','MARKER','RECTANGLE','POLYLINE','POLYGON'];

			for(var i=0;i<t.length;++i){
				if(s===google.maps.drawing.OverlayType[t[i]]){
					return t[i];
				}
			}
		}
}

function init_zone(map){
	for(var i in arr_zone){
		arr_zone[i].overlay.setMap(map);
	}
}