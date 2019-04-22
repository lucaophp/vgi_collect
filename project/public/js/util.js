let map = new OpenLayers.Map("map");
let lat = 0.0;
let lng = 0.0;
let already = false;
let event = new Event('pronto');
function getData(url,handle){
    $.ajax({
        type:'GET',
        url:url,
        success: handle
    });

}
function deleteData(url,handle){
    $.ajax({
        type:'DELETE',
        url:url,
        success: handle
    });
}
function postData(url,data,handle){

    //let nd = valuesToArray(data);
    //console.log(nd);
    $.ajax({
        type:'POST',
        url:url,
        data:data,
        success: handle
    });
}
function putData(url,data,handle) {
    $.ajax({
        type:'PUT',
        url:url,
        data:data,
        success: handle
    });

}

function confirmEvent(data,handle){
    postData('api/confirmEvent',data,handle);
}

function updateEvent(data,handle){
    putData('api/event',data,handle);
}

function finallyEvent(ids,handle){
    deleteData('api/event?ids='+ids,handle);
}


function deleteEventRep(ids,lat,lng,type_name,handle){
    deleteData('api/eventRep?ids='+ids+'&latitude="'+lat+'"&longitude="'+lng+'"&type='+type_name,handle);
}
function getEvent(ids,handle) {
    getData('api/event?ids='+ids,handle);

}
function getDataUser(handle){
    getData('api/user/session',function(data){
        if(data['status']){
            handle(data['user']);
        }
    })
}
function loadPoints(markers){
    getData('api/points',function (data) {
        let points = data.points;
        //map.removeLayer(markers);
        points.forEach(function (v) {
            let lonLat = new OpenLayers.LonLat( v['longitude'] ,v['latitude'] )
                .transform(
                    new OpenLayers.Projection("EPSG:4326"), // transform from WGS 1984
                    map.getProjectionObject() // to Spherical Mercator Projection
                );
            let marker = new OpenLayers.Marker(lonLat);
            marker.events.register('dblclick', marker, function(evt) { showalert(v.type_ev.name+' <br> <img src="'+v.photo+'" class="img-thumbnail photo_show">','alert-info'); OpenLayers.Event.stop(evt); });
            markers.addMarker(marker);



        });
        map.addLayer(markers);

    });


}
function getAddress(lat,lng,handle){
    getData('api/address?lat='+lat+'&lng='+lng,function (data) {
        handle(data[0].address);
    })
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
    lat = position.coords.latitude;
    lng = position.coords.longitude;
    document.dispatchEvent(event);

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
function showalert(message,alerttype) {

    $('body').prepend('<div id="alertdiv" class="alert ' +  alerttype + '" style="z-index: 1000;position: fixed;margin-left: auto;margin-right: auto;margin-bottom:auto;left:0;right: 0; width:400px "><a class="close" data-dismiss="alert">×</a><span>'+message+'</span></div>')

    setTimeout(function() { // this will automatically close the alert and remove this if the users doesnt close it in 5 secs


        $("#alertdiv").remove();

    }, 10000);
}
function modal(title,form,handle) {
    $("#myModal").remove();
    $('body').prepend(`
        <div id="myModal" class="modal fade" role="dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">`+title+`</h4>
                </div>
                <div class="modal-body form-horizontal" style="overflow: auto">
                    `+form+`
                </div>
                <div class="modal-footer">
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-default" id="enviar" data-dismiss="modal">Cadastrar
                            </button>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    `);
    $('#enviar').on('click',function (ev) {
        let data = document.getElementById("myModal").querySelectorAll("input, select, checkbox, textarea");
        let finalData = {};
        data.forEach(function(v){
            finalData[v.id] = v.value;
        });

        handle(ev,finalData);

    });
    
}
