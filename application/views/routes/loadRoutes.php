<script>
	Route_Active = {
		settings: {
			Map: $('#map'),
	        TblActive: $('.tbl_routes_1'),
	        CheckboxAll: $('#allRoute'),
	        BtnPrev: $('.btn_prev_page_customers_active'),
	        BtnNext: $('.btn_next_page_customers_active'),
	        TotalRecord: $('#TotalRecord'),
	        Button_Unselect_All: $('#Button_Unselect_All'),
	        Button_All: $('#Button_All')
	    },
	    LoadDefault: function(){
	    	textSelect= $('#item_count_' + setActive);
	    	selected = [];
	  		unselected = [];
	  		textSelect.text(0);

	  		$('[name = select_all_'+setActive+']').prop('checked',false);
	    	Route_Active.settings.TblActive = $('.tbl_routes_' + setActive);
	    	Table_Active = Dom_Active.TblActive.DataTable({
	    		destroy: true,
	            serverSide: true,
	            processing: false,
	            autoWidth: false,
	            deferRender: true,
	            ordering: false,
	            pageLength: 10,
	            deferRender: true,
	            ajax: {
	                url: "<?php echo url::base()?>routes/LoadRoutes",
	                type: "POST",
	                data: {
	                	idSet: setActive,
	                    search: $('#searchRoute_'+setActive+'').val(),
	            //         d._ac_in_tive      = document.getElementById('ac_in_tive').value,
	            //         d.ValFilterType    = ValFilterType,
	            //         d.ValFilterBalance = ValFilterBalance
	                },
	                beforeSend : function(){
	                    Js_Top.Add_Image_Loading_Datatables(Route_Active.settings.TblActive);
	                },
	                complete: function(d){
	                    Total_Record_Filter = d.responseJSON.recordsFiltered;
	                    Total_Record = d.responseJSON.recordsTotal;
	                    Route_Active.settings.TblActive.children('div:last-child').remove();
	                    // $("#loading").hide();
	            //         $('.tab-content').children('div:last-child').remove();
	            //         Arr_id_Search = d.responseJSON.Str_id;
	                }
	            },
	            columnDefs: [{
	                orderable: false,
	                targets: 0,
	            }],
	            order: [
	                [1, 'asc']
	            ],
	            "columns": [
	            {
	                "class":"td_route_chk_" + setActive,
	                "data": null,
	                "orderable": false,
	                "render": function ( data, type, full, meta ) {
	                    return '<div class="custom-checkbox"><input onchange="Route_Active.ClickItem('+full.tdID+',this)" type="checkbox" class="chk_record_detail" id="td_row_'+full.tdID+'"><label style="margin-bottom: 4px !important;" for="td_row_'+full.tdID+'"></label></div>';
	                }
	            },{
	            	"class":"td_route_no_" + setActive,
	                "data": "<button>Click!</button>",
	                "orderable": false,
	                "render": function ( data, type, full, meta ) {
	                    return '<p style="cursor:pointer;font-weight:bold" onclick="Route_Active.EditRoute('+full.tdID+',0)">'+full.tdNo+'</p>';
	                }
	            },{
	            	"class":"td_route_name_" + setActive,
	                "data": null,
	                "orderable": false,
	                "render": function ( data, type, full, meta ) {
	                    return '<p style="cursor:pointer;font-weight:bold" onclick="Route_Active.EditRoute('+full.tdID+',0)">'+full.tdName+'</p>';
	                }
	            },{
	            	"class":"td_route_service_" + setActive,
	                "data": null,
	                "orderable": false,
	                "render": function ( data, type, full, meta ) {
	                    return '<p style="cursor:pointer;font-weight:bold" onclick="Route_Active.EditRoute('+full.tdID+',0)">'+full.tdService+'</p>';
	                }
	            },{
	            	"class":"td_route_map_" + setActive,
	                "data": null,
	                "orderable": false,
	                "render": function ( data, type, full, meta ) {
	                    return '<button type="button" value="'+full.tdID+'" class="btn btn-'+checkMap(full.tdID)+'" onclick="Showmap('+full.tdID+',this)">See Map</button>';
	                }
	            },{
	            	"class":"td_route_zip_" + setActive,
	                "data": null,
	                "orderable": false,
	                "render": function ( data, type, full, meta ) {
	                    return '<superscripted  style="cursor:pointer;font-weight:bold" onclick="Route_Active.EditRoute('+full.tdID+',0)">'+full.tdZIP+'</superscripted>';
	                }
	            },{
	            	"class":"td_route_technician_" + setActive,
	                "data": null,
	                "orderable": false,
	                "render": function ( data, type, full, meta ) {
	                    return '<p style="cursor:pointer;font-weight:bold" onclick="Route_Active.EditRoute('+full.tdID+',0)">'+full.tdTechnician+'</p>';
	                }
	            }],
	            rowCallback: function( row, data ) {
					var id = data.tdID;
					if($('[name = select_all_'+ setActive+']').is(':checked')){
						if( $.inArray(id, unselected) === -1){
							$(row).find('.chk_record_detail').prop('checked', true);
						}
						if($('.dataTables_filter input').val() == ''){
							var total_row_report = Route_Active.settings.TblActive.dataTable().fnSettings().fnRecordsTotal();
							$('#item_count').text(total_row_report - unselected.length);
						}else{
							var total_row_report_filter = Route_Active.settings.TblActive.dataTable().fnSettings().fnRecordsDisplay();
							$('#item_count').text(total_row_report_filter - unselected.length);
						}
					}else{
						if ( $.inArray(id, selected) !== -1 ) {
							$(row).find('.chk_record_detail').prop('checked', true);
						}
						$('#item_count').text(selected.length);
					}
				}
	    	})
	    },
	    EditRoute: function(idRoute){
	    	$.ajax({
		        url: '<?=url::base()?>routes/editRouteHtml',
		        method: 'POST',
		        data: {
		          idRoute: idRoute
		        },
		        success: function(result){
		          $('#wrap-overlay').html(result);
		          Js_Top.openNav();
		          // Js_Top.hide_loading();
		          jquery_plugins.MaskPhone();
		          jquery_plugins.NoSpaceInput();
	        	}
	      	})
	    },
	    LoadMap: function () {
	    	Route_Active.settings.Map = new goo.Map(document.getElementById('map_' + setActive), {
		      center: {lat: 10.830102, lng: 106.639720},
		      zoom: 15
		    });
		    
	    	geocoder = new goo.Geocoder();

		    var drawingManager = new goo.drawing.DrawingManager({

				map: Route_Active.settings.Map,

				drawingMode: goo.drawing.OverlayType.HAND,

				drawingControl: true,

				drawingControlOptions: {
					position: goo.ControlPosition.TOP_CENTER,
					drawingModes: [
						goo.drawing.OverlayType.CIRCLE,
						goo.drawing.OverlayType.POLYGON,
						goo.drawing.OverlayType.RECTANGLE
					]
				},

				markerOptions: image,
				
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
			goo.event.addListener(drawingManager, 'overlaycomplete', function(event) {
	        	var shape = event.overlay;
				var type = event.type;
				var data = [];

				if(type == 'circle'){
					var center = JSON.parse(JSON.stringify(shape.center));
					var radius = shape.radius;
					data.push({center: center, radius: radius});
	        	}
				
	        	if(type == 'rectangle'){
	        		var bound = JSON.parse(JSON.stringify(shape.bounds));
	        		//north + west, south + east
	        		var position = [{lat: bound.south, lng: bound.west}, {lat: bound.north, lng: bound.east}];
	        		data.push(position);
	        	}
	        	if(type == 'polygon'){
	        		var position = JSON.parse(JSON.stringify(shape.latLngs.b[0].b));
	        		data.push(position);
	        	}

	        	if(idRoute == 0){
	        		alert('Please select route first!');
	        		shape.setMap(null);
	        	}
	        	else{

		        	$.post('<?=url::base()?>routes/saveZone',{data: data, type: type,id: idRoute})
		        	.success(function (result) {
		        		var data = JSON.parse(result);
		        		arr_zone.push({data: data, route_id: data[0].route_id});
		        		shape.setMap(null);
		        		drawShape(data,data[0].type,Route_Active.settings.Map);
		        	})
		        }
	        })
	    },
	    DrawMap: function (arr_service, arr_zone) {
	    	Route_Active.LoadMap();
	    	for(var i in arr_service){
	    		var address = arr_service[i].address;
	    		// console.log(arr_service[i].route_id);
	    		geocoder.geocode({ 'address': address}, function(results, status) {
			      if (status == google.maps.GeocoderStatus.OK) {
			       	var location = results[0].geometry.location;
			       	var overlay = new google.maps.Marker({
						position: location,
						icon: image,
						// title: arr_service[i].route_id
					});
					overlay.setMap(Route_Active.settings.Map);
			      }
			    });
			    // console.log('2' + l);
	    	}
	    	var data;
	  		for(var i in arr_zone){
	  			var type = arr_zone[i].data[0].type;
	  			data = arr_zone[i].data;
	  			drawShape(data,type,Route_Active.settings.Map);
	        }
	    },
	    CheckAll: function (t_this) {
	    	if(t_this.checked){
                $(':checkbox', Route_Active.settings.TblActive.DataTable().rows().nodes()).prop('checked', true);
                selected=[];
                textSelect.text(Total_Record);
            } else {
                $(':checkbox', Route_Active.settings.TblActive.DataTable().rows().nodes()).prop('checked', false);
                unselected=[];
                textSelect.text(0);
            }
            // e.stopPropagation();
	    },
	    ClickItem: function (id, element) {
	    	if($('#allRoute_' + setActive).is(":checked")){
	    		var nonindex = $.inArray(id, unselected);
		    	if(nonindex === -1)
		    		unselected.push(id);
		    	else
		    		unselected.splice(nonindex,1);
		    	textSelect.text(Total_Record - unselected.length);
	    	}
	    	else{
	    		var index = $.inArray(id, selected);
				if ( index === -1 ) {
					selected.push(id);
				} else {
					selected.splice(index, 1);
				}
				textSelect.text(selected.length);
	    	}
	    },
	    Search: function(){
		    Route_Active.LoadDefault();
		    selected = [];
		    unselected = [];
		    textSelect.text(0);
	    },
	    SaveRoute: function () {
	    	var route_no = $('[name = route_no]').val();
	    	var route_name = $('[name = route_name]').val();
	    	var route_zip = $('[name = route_zip]').val();
	    	var route_technician = $('[name = technician]').val();

	    	$.post('<?=url::base()?>routes/insertRoute',{
	    		no: route_no,
	    		name: route_name,
	    		zip: route_zip,
	    		technician: route_technician,
	    		idSet: setActive
	    	})
	    	.done(function (result) {
	    		if(result){
	    			Js_Top.closeNav();
	    			Js_Top.success('Save Success.');
	    			Route_Active.settings.TblActive.DataTable().draw();
	    		}
	    		else{
	    			Js_Top.warning('Some error!');
	    		}
	    	})
	    },
	    Ready: function(arr_total_check){
		    Route_Active.LoadDefault();
		    Route_Active.LoadMap();
	    },
	    init: function(){
	    	Dom_Active = this.settings;
	        Dom_Active.TblActive;
	        Dom_Active.CheckboxAll;
	        Dom_Active.BtnPrev;
	        Dom_Active.BtnNext;
	        Dom_Active.TotalRecord;
	        Dom_Active.Button_Unselect_All;
	        Dom_Active.Button_All;
	        this.Ready();
	    }
	}
	// function view_map() {
	// 	var goo = google.maps;

	//     var map = new goo.Map(document.getElementById('map_' + setActive), {
	//       center: {lat: 10.830102, lng: 106.639720},
	//       zoom: 15
	//     });
	// }
	// $(document).ready(function () {
	// 	view_map();
	// })
</script>