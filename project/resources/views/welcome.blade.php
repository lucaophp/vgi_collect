<!DOCTYPE HTML>
<html lang="{{ app()->getLocale() }}">
<head>
    <title>OpenLayers Simplest Example</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>
<div id="mark-form">
    <input type="hidden" name="lat" id="lat">
    <input type="hidden" name="long" id="long">
    <!-- Trigger the modal with a button -->
    <button type="button" class="btn btn-success" id="mark-pos">Marcar Local
    </button>
    <button type="button" class="btn btn-danger btn-lg" data-toggle="modal" data-target="#myModal">Marcar Inscidente
    </button>


    <!-- Modal -->
    <div id="myModal" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Cadastrar Inscidente</h4>
                </div>
                <div class="modal-body form-horizontal" style="overflow: auto">
                    <div class="form-group">
                        <label class="control-label col-sm-2">Tipo de Ocorrencia:</label>
                        <div class="col-sm-10">

                            <select class="form-control" name="type_ev_id">
                                @foreach($type as $t)
                                    <option value="{{ $t->id }}">{{ $t->name }}</option>
                                @endforeach
                            </select>

                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2">Foto:</label>
                        <div class="col-sm-10">
                            <input class="form-control" type="file" name="photo" id="photo">
                            <input type="hidden" name="photo_url">
                            <img class="img-thumbnail" src="" id="photo_show">

                        </div>
                    </div>
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
    </div>
    <!--<input type="submit" name="position" id="enviar" value="Marcar Incidente">-->
</div>
<div id="map" style="height:100%;width: 100%;position: absolute;"></div>
<!-- The actual snackbar -->
<!--<div id="snackbar">Ponto marcado com sucesso!!!</div>-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.js" type="text/javascript"></script>
<script src="http://www.openlayers.org/api/OpenLayers.js"></script>
<script src="http://oobrien.com/heatmap/HeatmapLayer.js"></script>
<script src="{{ asset('js/main.js') }}"></script>

</body>
</html>
