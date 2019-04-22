let map = new OpenLayers.Map("map");
let event = new Event('pronto');
function getData(url,handle){
    $.ajax({
        type:'GET',
        url:url,
        success: handle
    });

}
function getLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(showPosition);
    } else {
        console.log("Geolocation is not supported by this browser.");
    }
}
function showPosition(position) {
    map.setCenter(
        new OpenLayers.LonLat(position.coords.longitude, position.coords.latitude) // Center of the map
            .transform(
                new OpenLayers.Projection("EPSG:4326"), // transform from new RD
                new OpenLayers.Projection("EPSG:900913") // to Spherical Mercator Projection
            ),
        15); // Zoom level
    document.getElementsByName('lat')[0].value = position.coords.latitude.toFixed(3);
    document.getElementsByName('long')[0].value = position.coords.longitude.toFixed(3);
    getData('api/proximos?longitude='+document.getElementById('long').value+'&latitude='+document.getElementById('lat').value,function (data) {
        let prox = data;
        console.log(prox);
    });
}
function showalert(message,alerttype) {

    $('body').append('<div id="alertdiv" class="alert ' +  alerttype + '" style="z-index: 1000;position: absolute;margin-left: auto;margin-right: auto;left:0;right: 0; width:400px "><a class="close" data-dismiss="alert">×</a><span>'+message+'</span></div>')

    setTimeout(function() { // this will automatically close the alert and remove this if the users doesnt close it in 5 secs


        $("#alertdiv").remove();

    }, 10000);
}
function toast(message,time=3000){
    let htm = `<div id="snackbar">`+message+`</div>`;
    let body = $('body');
    body.append(htm);
    // Get the snackbar DIV
    const x = document.getElementById("snackbar");

    // Add the "show" class to DIV
    x.className = "show";

    // After 3 seconds, remove the show class from DIV
    setTimeout(function () {
        x.className = x.className.replace("show", "");
        $('#snackbar').remove();
    }, time);


}
$(document).ready(function () {
    let form;
    let marked = false;
    let strategy = new OpenLayers.Strategy.Cluster({distance: 15, threshold: 3});
    let markers = new OpenLayers.Layer.Markers( "Markers",{strategies: [strategy]} );
    let heat = new Heatmap.Layer("Heatmap");
    map.addLayer(new OpenLayers.Layer.OSM());
    // set up cluster strategy and vector layer



    getLocation();
    var pontoStyle = new OpenLayers.StyleMap({
        'default': new OpenLayers.Style({
            externalGraphic: "${iconImg}",
            graphicWidth: "${iconSize}",
            graphicHeight: "${iconSize}",
            label: "${getName}",
            fontSize: "9px",
            fontFamily: "Trebuchet MS, sans-serif",
            labelAlign: "cm"
        } , {
            context: {
                iconImg: function (feature) {
                    return renderCluster (feature, 'img');
                },
                iconSize: function (feature) {
                    return renderCluster (feature, 'size');
                },
                getName: function (feature) {
                    return feature.attributes.count;
                }
            }
        })
    });

    getData('api/points',function (data) {
        let points = data.points;
        points.forEach(function (v) {
            let lonLat = new OpenLayers.LonLat( v['longitude'] ,v['latitude'] )
                .transform(
                    new OpenLayers.Projection("EPSG:4326"), // transform from WGS 1984
                    map.getProjectionObject() // to Spherical Mercator Projection
                );
            let marker = new OpenLayers.Marker(lonLat);
            marker.events.register('dblclick', marker, function(evt) { showalert(v.type_ev.name+' <br> <img src="'+v.photo+'" class="img-thumbnail photo_show">','alert-info'); OpenLayers.Event.stop(evt); });
            markers.addMarker(marker,{styleMap: pontoStyle});
            var point = new Heatmap.Source(lonLat);
            heat.addSource(point);

        })

    });

    heat.defaultIntensity = 0.08;
    heat.setOpacity(0.5);

    map.addLayers([heat,markers]);
    map.zoomToMaxExtent();



    map.events.register("moveend", map, function() {
        let position = map.center;
        position = new OpenLayers.LonLat( position.lon ,position.lat )
            .transform(
                map.getProjectionObject(), // to Spherical Mercator Projection
                new OpenLayers.Projection("EPSG:4326") // transform from WGS 1984

            );
        if(marked){
            document.getElementsByName('lat')[0].value = position.lat;
            document.getElementsByName('long')[0].value = position.lon;
        }

    });

    $("#mark-pos").on('click',function (event) {
        if(marked){
            document.getElementById('map').style.cursor = 'auto';
            document.getElementById('mark-pos').innerHTML = 'Marcar Local';
            document.getElementById('mark-pos').class = 'btn btn-success';
            let latitude = document.getElementsByName('lat')[0].value.toFixed(3);
            let longitude = document.getElementsByName('long')[0].value.toFixed(3);
            let lonLat = new OpenLayers.LonLat( longitude, latitude )
                .transform(
                    new OpenLayers.Projection("EPSG:4326"), // transform from WGS 1984
                    map.getProjectionObject() // to Spherical Mercator Projection
                );
            let marker = new OpenLayers.Marker(lonLat);
            var size = new OpenLayers.Size(21,25);
            var offset = new OpenLayers.Pixel(-(size.w/2), -size.h);
            marker.icon = new OpenLayers.Icon('images/icon_place.png',size,offset);
            marker.events.register('mousedown', marker, function(evt) { alert(this.icon.url); OpenLayers.Event.stop(evt); });
            markers.addMarker(marker);

        }else{


            document.getElementById('map').style.cursor = 'move';
            document.getElementById('mark-pos').innerHTML = 'Parar';
            document.getElementById('mark-pos').class = 'btn btn-danger';


        }
        marked = !marked;


    });
    $('#photo').change(function (event) {
        form = new FormData();
        form.append('photo', event.target.files[0]); // para apenas 1 arquivo

        $.ajax({
            url: 'api/photo', // Url do lado server que vai receber o arquivo
            data: form,
            processData: false,
            contentType: false,
            type: 'POST',
            success: function (data) {
                let photo = document.getElementsByName('photo_url')[0];
                let photo_show = document.getElementById('photo_show');
                photo.value = data['url'];
                photo_show.src = data['url'];
            },
            error: function (err) {
                toast('Tipo Invalido, apenas fotos são aceitas!!!',3000);

            }
        });
    });

    $('#enviar').on('click', function (ev) {

        let latitude = document.getElementsByName('lat')[0].value;
        let longitude = document.getElementsByName('long')[0].value;
        let datahora = new Date().toISOString().slice(0, 19).replace('T', ' ');
        let photo = document.getElementsByName('photo_url')[0].value;
        let type_ev_id = document.getElementsByName('type_ev_id')[0].value;
        let status = 'REPORTED';

        $.ajax({
            type: "POST",
            url: 'api',
            data: {
                type_ev_id: type_ev_id,
                latitude: latitude,
                longitude: longitude,
                datahora: datahora,
                photo: photo,
                status: status
            },
            success: function (data) {
                if (data['status']) {
                    let position = map.center;
                    position = new OpenLayers.LonLat( position.lon ,position.lat );
                    let marker = new OpenLayers.Marker(position);
                    markers.addMarker(marker);
                    let point = new Heatmap.Source(position);
                    heat.addSource(point);

                    toast('Ponto cadastrado com sucesso!!!');
                }
            },
            error: function (err) {
                toast('Houve algum problema, e não foi possivel cadastrar o ponto!!!')

            }
        });
    })

});









