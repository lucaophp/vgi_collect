<!DOCTYPE HTML>
<html lang="{{ app()->getLocale() }}">
<head>
    <title>OpenLayers Simplest Example</title>
    <base href="/">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>
<div class="modal-body row">
    <div class="alert-success" id="inf">

    </div>
    <div class="col-md-6">
        <div id="map" style="height:100%;width: 50%;position: fixed"></div>
    </div>
    <div class="col-md-6">
        <div class="alert-success" id="data_user"></div>
        <a class="text-right text-danger" href="logout"><button class="btn btn-danger" style="position:absolute;right: 0;top: 0">Sair</button></a>
        <div id="list_info">

            <div class="page-header">
                <h1 id="title_ev">Eventos Para Aprovação</h1>

            </div>
            <table class="table table-responsive table-striped" id="tbl_dists">
                <thead>
                <tr>
                    <th>Tipo</th>
                    <th>Rua</th>
                    <th>Bairro</th>
                    <th>Cidade</th>
                    <th>Distância</th>
                    <th>Quantidade</th>

                </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
        <div class="alert-success" style="position: fixed;bottom: 2px;right:2px;width: 49%;min-width: 300px;">
            <button class="btn btn-danger form-control" id="eventConf">Eventos Confirmados</button>
        </div>

    </div>
</div>



<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.js" type="text/javascript"></script>
<script src="http://www.openlayers.org/api/OpenLayers.js"></script>
<script src="http://oobrien.com/heatmap/HeatmapLayer.js"></script>
<script src="{{ asset('js/util.js') }}"></script>
<script src="{{ asset('js/admin.js') }}"></script>
</body>
</html>