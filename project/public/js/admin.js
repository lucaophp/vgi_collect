$(document).ready(function () {
    let active = false;
    let urlProximos = 'api/proximos';
    getLocation();
    map.zoomTo(2);
    map.addLayer(new OpenLayers.Layer.OSM());

    var strategies = [
        new OpenLayers.Strategy.BBOX(),
        new OpenLayers.Strategy.Cluster()
    ];
    let markers = new OpenLayers.Layer.Markers("Markers", {strategies: [strategies]});
    var table = document.getElementById("tbl_dists").getElementsByTagName('tbody')[0];
    var CLUSTER_SCALE_THRESHOLD = 432550;
    loadPoints(markers);
    $('#eventConf').on('click',function () {

        if(active){
            this.innerHTML = 'Eventos Confirmados';
            document.getElementById('title_ev').innerHTML = 'Eventos Para Aprovação';
            urlProximos = 'api/proximos';
        }else{

            this.innerHTML = 'Eventos Para Aprovação';
            document.getElementById('title_ev').innerHTML = 'Eventos Confirmados';
            urlProximos = 'api/proximos/active';
        }
        active = !active;

        document.dispatchEvent(event);

    });
    getDataUser(function(data){
       document.getElementById('data_user').innerHTML =
           data['name']+' está logado pelo email '+data['email']+' e tem a função de '+data['type']+'<p><a href="registrar/update">Atualizar Dados</a></p>';

    });


    $(document).on('pronto', function () {

        getData(urlProximos+'?longitude=' + lng + '&latitude=' + lat, function (data) {
            console.log(data);
            table.innerHTML = '';
            data.forEach(function (val) {

                getAddress(val.latitude, val.longitude, function (address) {
                    let row = table.insertRow();
                    let dist = ((val.distance * 1.609344 < 1) ? (val.distance * 1.609344 * 1000).toFixed(3) + ' Metros' : (val.distance * 1.609344).toFixed(3) + ' Km');
                    row.innerHTML += '<td>' + val.name + '</td>';
                    row.innerHTML += '<td>' + address.road + '</td>';
                    row.innerHTML += '<td>' + address.suburb + '</td>';
                    row.innerHTML += '<td>' + address.town + ' - ' + address.state + '</td>';
                    row.innerHTML += '<td>' + dist + '</td>';
                    row.innerHTML += '<td>' + val.qtd + '</td>';
                    if(active){
                        row.innerHTML += '<td style="display: none;"><button class="btn btn-success" data-toggle="modal" data-target="#myModal">Atualizar</button><button class="btn btn-danger">Finalizar</button> </td>';

                    }else{
                        row.innerHTML += '<td style="display: none;"><button class="btn btn-success" data-toggle="modal" data-target="#myModal">Confirmar</button><button class="btn btn-danger">Remover</button> </td>';
                    }
                    let tds = row.getElementsByTagName('td');
                    let tam = tds.length;
                    let btns = tds[tam - 1].getElementsByClassName('btn');
                    let btn_confirm = btns[0];
                    let btn_delete = btns[1];
                    row.addEventListener('click', function (ev) {
                        let lonLat = new OpenLayers.LonLat(val['longitude'], val['latitude'])
                            .transform(
                                new OpenLayers.Projection("EPSG:4326"), // transform from WGS 1984
                                map.getProjectionObject() // to Spherical Mercator Projection
                            );
                        map.setCenter(lonLat, 19);

                    });
                    row.onmouseover = function () {
                        let elem = document.getElementById('inf');
                        elem.style.display = "block";
                        elem.style.position = "fixed";
                        //elem.style.backgroundColor = 'green';
                        elem.style.width = "100%";
                        elem.style.height = "30px";
                        elem.style.zIndex = 300;
                        elem.innerHTML = 'Longitude: ' + val['longitude'] + ' | Latitude: ' + val['latitude'] + '. Foi Reportado por ' + val.qtd + ' Pessoa(s). Fica aproximadamente à ' + dist + ' metros de você.';
                        let tds = row.getElementsByTagName('td');
                        let tam = tds.length;
                        tds[tam - 1].style.display = 'block';

                    };
                    row.onmouseout = function () {
                        document.getElementById('inf').style.display = "none";
                        let tds = row.getElementsByTagName('td');
                        let tam = tds.length;
                        tds[tam - 1].style.display = 'none';
                    };
                    btn_delete.addEventListener('click', function (ev) {
                        if(active){
                            finallyEvent(val.ids,function (data) {
                                document.dispatchEvent(event);
                                toast("Evento Finalizado!!!");
                                //map.removeLayer(markers);
                                loadPoints(markers);

                            });
                        }else{
                            deleteEventRep(val.ids, val.latitude, val.longitude, val.name, function (data) {
                                document.dispatchEvent(event);
                                toast("Evento Deletado!!!");
                                //map.removeLayer(markers);
                                loadPoints(markers);


                            });
                        }


                    });
                    btn_confirm.addEventListener('click', function (ev) {
                        modal('Confirmação de Evento',
                            `
                            <div class="form-group">
                                <input type="hidden" id="id" value="NONE">
                                <input type="hidden" id="ids" value="`+val.ids+`">
                                <label class="control-label col-sm-2">Descrição:</label>
                                <div class="col-sm-10">
        
                                    <textarea id="descricao" class="textarea form-control"></textarea>
        
                                </div>
                                <label class="control-label col-sm-2">Estimativa:</label>
                                <div class="col-sm-10">
        
                                    <input type="time" id="estimate" class="textarea form-control">
        
                                </div>
                                <label class="control-label col-sm-2">Nível de Gravidade:</label>
                                <div class="col-sm-10">
                                   <select id="level" class="form-control">
                                        <option value="0">Normal - Não Bloqueia Via</option>
                                        <option value="1">Médio - Bloqueia meia pista</option>
                                        <option value="2">Grave - Bloqueia toda pista</option>
                                    </select> 
                                </div>
                            </div>
                       `,function(ev,data){
                            if(active){
                                updateEvent(data,function (v) {
                                    document.dispatchEvent(event);
                                    toast('Evento Atualizado!!!');

                                })
                            }else{
                                confirmEvent(data,function (v) {
                                    document.dispatchEvent(event);
                                    toast('Evento Confirmado!!!');
                                });
                            }


                        }
                        );
                        if(active){
                            getEvent(val.ids,function (data) {
                                document.getElementById('level').value = data.level;
                                document.getElementById('estimate').value = data.estimate;
                                document.getElementById('descricao').value = data.obs;
                                document.getElementById('id').value = data.id;

                            });
                        }
                    });


                })


            });

        });

    });


    map.zoomToMaxExtent();

});