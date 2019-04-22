<!DOCTYPE HTML>
<head>
	<title>Coletor de Incidentes</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	  <style>
	  	.modal-backdrop {
		  z-index: -1;
		}
	</style>
</head>
<body style="margin: 0;padding: 0">
	<div style="position: fixed;z-index: 10;bottom: 20px;right: 20px;">
		<input type="hidden" name="lat">
		<input type="hidden" name="long">
		<!-- Trigger the modal with a button -->
		<button type="button" class="btn btn-danger btn-lg" data-toggle="modal" data-target="#myModal">Marcar Inscidente</button>

		<!-- Modal -->
		<div id="myModal" class="modal fade" role="dialog">
		  <div class="modal-dialog">

		    <!-- Modal content-->
		    <div class="modal-content">
		      <div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal">&times;</button>
		        <h4 class="modal-title">Cadastrar Inscidente</h4>
		      </div>
		      <div class="modal-body form-horizontal"  style="overflow: auto">
		      	<div class="form-group">
			    	<label class="control-label col-sm-2">Ocorrido:</label>
			    	<div class="col-sm-10">
			      		<input class="form-control"  type="text" name="description">

			    	</div>
			  	</div>
			  	<div class="form-group">
			    	<label class="control-label col-sm-2">Tempo estimado:</label>
			    	<div class="col-sm-10">
			      		<input class="form-control"  type="data" name="time">

			    	</div>
			  	</div>
			  	<br />
			  	<div class="form-group">
			    	<label class="control-label col-sm-2">Observação:</label>
			    	<div class="col-sm-10">
			      		<textarea class="form-control" name="obs"></textarea>

			    	</div>
			  	</div>
			  	<br />
			  	<div class="form-group">
			    	<label class="control-label col-sm-2">Foto:</label>
			    	<div class="col-sm-10">
			      		<input class="form-control" type="file" name="photo"></textarea>

			    	</div>
			  	</div>
		      </div>
		      <div class="modal-footer">
		      	<div class="form-group">
				    <div class="col-sm-offset-2 col-sm-10">
				      <button type="submit" class="btn btn-default" id="enviar" data-dismiss="modal">Cadastrar</button>
				    </div>
				</div>
		        
		      </div>
		    </div>

		  </div>
		</div>
		<!--<input type="submit" name="position" id="enviar" value="Marcar Incidente">-->
	</div>
	<div id="demoMap" style="height:100%;width: 100%;position: absolute;"></div>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.js" type="text/javascript"></script>
	<script src="http://www.openlayers.org/api/OpenLayers.js"></script>
	<script>
		$(document).ready(function(){
			$('#enviar').on('click',function(ev){
				let lat =  document.getElementsByName('lat')[0].value;
				let long =  document.getElementsByName('long')[0].value;
				$.ajax({
				  type: "POST",
				  url: 'send.php',
				  data: {lat:lat,long:long},
				  success: function(data){
				  	alert("Sucesso");
				  }
				});
			})
			
		});
		map = new OpenLayers.Map("demoMap");
	    map.addLayer(new OpenLayers.Layer.OSM());
	    map.zoomToMaxExtent();

	    
		function getLocation() {
		    if (navigator.geolocation) {
		        navigator.geolocation.getCurrentPosition(showPosition);
		    } else {
		        console.log("Geolocation is not supported by this browser.");
		    }
		}
		function showPosition(position) {
			map.setCenter(
			    new OpenLayers.LonLat(position.coords.longitude,position.coords.latitude) // Center of the map
			        .transform(
			            new OpenLayers.Projection("EPSG:4326"), // transform from new RD 
			            new OpenLayers.Projection("EPSG:900913") // to Spherical Mercator Projection
			        ),
			15); // Zoom level
			document.getElementsByName('lat')[0].value = position.coords.latitude;
			document.getElementsByName('long')[0].value = position.coords.longitude;
		}
		getLocation();
	    
	</script>
	
</body>
