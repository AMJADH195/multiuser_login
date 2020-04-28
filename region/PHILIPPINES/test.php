<?php
// First we execute our common code to connection to the database and start the session
require("connection.php");

// At the top of the page we check to see whether the user is logged in or not
if (empty($_SESSION['user'])) {
  // If they are not, we redirect them to the login page.
  header("Location: login.php");

  // Remember that this die statement is absolutely critical.  Without it,
  // people can view your members-only content without logging in.
  die("Redirecting to login.php");
}

// Everything below this point in the file is secured by the login system

// We can display the user's username to them by reading it from the session array.  Remember that because
// a username is user submitted content we must use htmlentities on it before displaying it to the user.
?>



<!doctype html>
<html lang="en">

<head>
  <script src="js/map.js"></script>
  <title>NAT CAT MISTEO</title>
  <link rel = "icon" href = "images/misteot.jpeg" type = "image/x-icon"> 
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <script src="https://api.mapbox.com/mapbox-gl-js/v1.8.1/mapbox-gl.js"></script>
  <link href="https://api.mapbox.com/mapbox-gl-js/v1.8.1/mapbox-gl.css" rel="stylesheet" />

  <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800,900" rel="stylesheet">

  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="css/style.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <script src='https://cdn.plot.ly/plotly-latest.min.js'></script>
  <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
  <style>
    .des {
      width: 100%;
      text-align: center;
      background-color: #1d1919;
      height: 150px;
      z-index: 999;
      bottom: 0;
      position: absolute;
      display: none;
      transition-duration: 0.8s;
      transition-delay: 0.3s;
      opacity: 0.8;
      margin: 0;
    }

    .nopadding {
      padding: 0;
      margin: 0;
    }

    .sec-footer {
      position: absolute;
      bottom: 0;
    }

    .close-btn {
      position: absolute;
      color: red;
      top: -20px;
      right: 4px;
      z-index: 1;

    }

    p {
      text-align: left;
    }

    .icon {
      position: absolute;
      right: 0;
    }

    footer {
      position: absolute;
      bottom: 0;
      justify-content: center;
      padding: 10px;
    }

    .footer-logo {
      height: 20px;
      margin: auto;
    }

    .mapboxgl-popup-anchor-top .mapboxgl-popup-tip,
    .mapboxgl-popup-anchor-top-left .mapboxgl-popup-tip,
    .mapboxgl-popup-anchor-top-right .mapboxgl-popup-tip {
      border-bottom-color: #fff;
    }

    .mapboxgl-popup-anchor-bottom .mapboxgl-popup-tip,
    .mapboxgl-popup-anchor-bottom-left .mapboxgl-popup-tip,
    .mapboxgl-popup-anchor-bottom-right .mapboxgl-popup-tip {
      border-top-color: #fff;
    }

    .mapboxgl-popup-anchor-left .mapboxgl-popup-tip {
      border-right-color: #fff;
    }

    .mapboxgl-popup-anchor-right .mapboxgl-popup-tip {
      border-left-color: #fff;
    }

    table th#gr {
      background-color: #008000;
      color: white;
    }

    table th#yel {
      background-color: #ffff00;
      color: white;
    }

    table th#red {
      background-color: #ff0000;
      color: white;
    }

    table td {
      color: white;
    }

    #colorscale {
      height: 20px;
      /* width: 190px;  */
      background: linear-gradient(to right, #008000 0%, #ffff00 50%, #ff0000 100%);
      display: none;

    }

    .dot {
      height: 20px;
      width: 20px;
      background-color: #bbb;
      border-radius: 50%;
      display: none;
    }

    .dot-des {
      padding-left: 15px;
      padding-top: 0px;
      display: none;
    }

    .color-des {
      padding: 5px auto;
    }

    .color-info {
      position: absolute;
      bottom: 0px;
      z-index: 1;
      padding-bottom: 100px;
      padding-left: 15px;
    }

    .infotext {
      width: 100%;
      color: #ffffff;
      /* font-family: ; */
      font-size: 11px;
      font-weight: 300;
      display: none;
      padding-bottom: 10px;
    }

    .hrline {
      border-top: 1px solid rgba(255, 255, 255, 0.1) !important;
      display: none;

    }
  </style>
</head>

<body>

  <div class="wrapper d-flex align-items-stretch">
    <nav id="sidebar">
      <div class="p-4 pt-5">
        <div class="row">
          <div class="col-md-8">
            <span>
              <i class="fa fa-1x fa-user" style="color:#DDDDDD;">
                <!-- icon --></i>
              <?php echo htmlentities($_SESSION['user']['username'], ENT_QUOTES, 'UTF-8'); ?> </span>
          </div>
          <div class="col-md-4">
            <a href="logout.php">
            <i class="fa fa-1x fa fa-power-off icon" style="color:#FF0000;padding-top: 5px; padding-right : 20px">
                <!-- icon --></i>
            </a>
          </div>
          

        </div>
        
        <hr class="hrline" id="line" style="display: block;">
        <h3 style="color: #f5cc06; padding-top:1rem">NatCat Data Portal</h3>
        <label>Select Information:</label>
        <select class="form-control" onchange="addLayer(this.value);" style="font-size:11px;">
          <optgroup label="Weather Parameters">
            <option selected disabled hidden style='display: none' value=''></option>
            <option value="2019_heat_index">DIARY HEAT INDEX </option>
            <!-- <option value="2018_heat_index">HEAT INDEX 01-01-2018</option> -->
            <!-- <option value="2015_heat_index">HEAT INDEX 01-01-2015</option> -->
            <!-- <option value="2018_temperature">TEMPERATURE 01-01-2018</option> -->
            <option value="2019_temperature">TEMPERATURE</option>
            <!-- <option value="2018_precipitation">PRECIPITATION 01-01-2018</option> -->
            <option value="2019_precipitation">PRECIPITATION</option>
            <option value="2020_feb_no2">NO2 CONCENTRATION </option>
            <option value="2019_wind">WIND SPEED</option>
            <option value="coc">CURRENT OBSERVED CONDITIONS</option>
          </optgroup>
          <optgroup label="Perils">
            <option value="IBTrACS_Last3years">TROPICAL CYCLONE TRACK</option>
            <option value="seismic_data">SEISMIC EVENTS</option>
            <option value="meteorological_events">METEOROLOGICAL EVENTS</option>
            <option value="climatological_events">CLIMATOLOGICAL EVENTS </option>
            <option value="geophysical_events">GEOPHYSICAL EVENTS</option>
            <option value="hydrological_events">HYDROLOGICAL EVENTS</option>
            <!-- <option value="NLE_India_Geojsn">NATURAL LOSS EVENTS (1980-2018)</option> -->
          </optgroup>
          <optgroup label="Solar Parameters">
            <option value="GTI">GTI</option>
            <option value="DNI">DNI</option>
            <option value="OPTA">OPTA</option>
            <option value="DIF">DHI</option>
            <option value="GHI">GHI</option>
            <option value="PVOUT">PVOUT</option>

          </optgroup>
          <optgroup label="Elevation">
            <option value="DEM">DEM</option>
            <option value="live">Live</option>
          <!-- <optgroup label="Building">
            <option value="buildings_kerala_5.geojson">BUILDING DATA KOCHI</option>

          </optgroup> -->
        </select>
        <p id="info"></p>
        <hr class="hrline" id="line" style="display: block;">
        <hr class="hrline" id="line">
        <!-- <table class="table table-bordered">
        <tbody>
          <tr>
            <th scope="row" id="gr"></th>
            <td >NLE</td>
          </tr>
          <tr>
            <th scope="row" id="yel"></th>
            <td>GPE</td>

          </tr>
          <tr>
            <th scope="row" id="red"></th>
            <td>ME</td>

          </tr>
         
        </tbody>
      </table> -->
        <div class="row color-info">
          <div class="infotext" id="legend">Legend</div>
          <p id="min"></p>
          <div id="colorscale"></div>
          <p id="max"></p>
          <span class="dot" id="dot">
          </span>
          <p class="dot-des" id="dotinfo"></p>
        </div>





        <footer>
          <div class="row justify-content-md-center">
            <a href="https://www.misteo.co/"><img class="footer-logo" src="images/logo2.png"></a>
          </div>
        </footer>

      </div>
    </nav>

    <!-- Page Content  -->
    <div id="content" class="secdiv">
      <div class="des row" id="myDIV">
        <i class="fa fa-times-circle-o fa-3x close-btn" onclick="hideModal()" aria-hidden="true"></i>
        <div class="row">
          <div class="col-8" style="padding-right: 0;">
            <div class="card">
              <div class="card-body" id="attributes">
              </div>
            </div>
          </div>
          <div class="col-4 nopadding">
            <div class="card">
              <div class="card-body">
                <!-- weather widget start -->
                <div id="m-booked-weather-bl250-98173">
                  <div class="booked-wzs-250-175 weather-customize" style="background-color:#137AE9;width:160px;" id="width1">
                    <div class="booked-wzs-250-175_in">
                      <div class="booked-wzs-250-175-data">
                        <div class="booked-wzs-250-175-left-img wrz-18"> <a target="_blank" href="https://www.booked.net/"> <img src="//s.bookcdn.com/images/letter/logo.gif" alt="booked.net - Book Your Hotel" /> </a> </div>
                        <div class="booked-wzs-250-175-right">
                          <div class="booked-wzs-day-deck">
                            <div class="booked-wzs-day-val">
                              <div class="booked-wzs-day-number"><span class="plus">+</span>32</div>
                              <div class="booked-wzs-day-dergee">
                                <div class="booked-wzs-day-dergee-val">&deg;</div>
                                <div class="booked-wzs-day-dergee-name">C</div>
                              </div>
                            </div>
                            <div class="booked-wzs-day">
                              <div class="booked-wzs-day-d">H: <span class="plus">+</span>32&deg;</div>
                              <div class="booked-wzs-day-n">L: <span class="plus">+</span>30&deg;</div>
                            </div>
                          </div>
                          <div class="booked-wzs-250-175-info">
                            <div class="booked-wzs-250-175-city">Kollam </div>
                            <div class="booked-wzs-250-175-date">Monday, 23 March</div>
                            <div class="booked-wzs-left"> <span class="booked-wzs-bottom-l">See 7-Day Forecast</span>
                            </div>
                          </div>
                        </div>
                      </div> <a target="_blank" href="https://www.booked.net/weather/kollam-34703">
                        <table cellpadding="0" cellspacing="0" class="booked-wzs-table-250">
                          <tr>
                            <td>Tue</td>
                            <td>Wed</td>
                            <td>Thu</td>
                            <td>Fri</td>
                            <td>Sat</td>
                            <td>Sun</td>
                          </tr>
                          <tr>
                            <td class="week-day-ico">
                              <div class="wrz-sml wrzs-18"></div>
                            </td>
                            <td class="week-day-ico">
                              <div class="wrz-sml wrzs-18"></div>
                            </td>
                            <td class="week-day-ico">
                              <div class="wrz-sml wrzs-03"></div>
                            </td>
                            <td class="week-day-ico">
                              <div class="wrz-sml wrzs-03"></div>
                            </td>
                            <td class="week-day-ico">
                              <div class="wrz-sml wrzs-03"></div>
                            </td>
                            <td class="week-day-ico">
                              <div class="wrz-sml wrzs-03"></div>
                            </td>
                          </tr>
                          <tr>
                            <td class="week-day-val"><span class="plus">+</span>29&deg;</td>
                            <td class="week-day-val"><span class="plus">+</span>29&deg;</td>
                            <td class="week-day-val"><span class="plus">+</span>30&deg;</td>
                            <td class="week-day-val"><span class="plus">+</span>31&deg;</td>
                            <td class="week-day-val"><span class="plus">+</span>29&deg;</td>
                            <td class="week-day-val"><span class="plus">+</span>29&deg;</td>
                          </tr>
                          <tr>
                            <td class="week-day-val"><span class="plus">+</span>26&deg;</td>
                            <td class="week-day-val"><span class="plus">+</span>27&deg;</td>
                            <td class="week-day-val"><span class="plus">+</span>26&deg;</td>
                            <td class="week-day-val"><span class="plus">+</span>26&deg;</td>
                            <td class="week-day-val"><span class="plus">+</span>26&deg;</td>
                            <td class="week-day-val"><span class="plus">+</span>26&deg;</td>
                          </tr>
                        </table>
                      </a>
                    </div>
                  </div>
                </div>
                <script type="text/javascript">
                  var css_file = document.createElement("link");
                  css_file.setAttribute("rel", "stylesheet");
                  css_file.setAttribute("type", "text/css");
                  css_file.setAttribute("href", 'https://s.bookcdn.com/css/w/booked-wzs-widget-275.css?v=0.0.1');
                  document.getElementsByTagName("head")[0].appendChild(css_file);

                  function setWidgetData(data) {
                    if (typeof(data) != 'undefined' && data.results.length > 0) {
                      for (var i = 0; i < data.results.length; ++i) {
                        var objMainBlock = document.getElementById('m-booked-weather-bl250-98173');
                        if (objMainBlock !== null) {
                          var copyBlock = document.getElementById('m-bookew-weather-copy-' + data.results[i].widget_type);
                          objMainBlock.innerHTML = data.results[i].html_code;
                          if (copyBlock !== null) objMainBlock.appendChild(copyBlock);
                        }
                      }
                    } else {
                      alert('data=undefined||data.results is empty');
                    }
                  }
                </script>
                <script type="text/javascript" charset="UTF-8" src="https://widgets.booked.net/weather/info?action=get_weather_info&ver=6&cityID=34703&type=3&scode=124&ltid=3457&domid=w209&anc_id=91063&cmetric=1&wlangID=1&color=137AE9&wwidth=160&header_color=ffffff&text_color=333333&link_color=08488D&border_form=1&footer_color=ffffff&footer_text_color=333333&transparent=0"></script>
                <!-- weather widget end -->
              </div>
            </div>
          </div>
        </div>
        <div class="row sec-footer nopadding">

        </div>
      </div>
    </div>

    <script>
      
      mapboxgl.accessToken = 'pk.eyJ1IjoiYW1iYXJpc2gtbmFyYXlhbmFuIiwiYSI6ImNrNnY3OTY4YjAydXczZ3A4NzJjZGN3cWgifQ.xHTR3sTQOWCa2Cg5bUtCZA';
      var bounds = [
            [55.962025,6.137904], // Southwest coordinates
            [112.522227,41.333990 ] // Northeast coordinates
      ];
      var map = new mapboxgl.Map({
        container: 'content',
        style: 'mapbox://styles/mapbox/dark-v10',
        zoom: 3,
        minZoom: 3,
        center: [78.9629, 22.89],
        maxBounds: bounds // Sets bounds as max
      });
     
      map.addControl(new mapboxgl.NavigationControl());
      map.addControl(new mapboxgl.ScaleControl({
        position: 'bottom-right'
      }));
      var WMS_URL = "http://3.7.15.178:8080/geoserver/Misteo/wms";
      var layer_name;
      var cLayer;
      var popup;

      function addLayer(value) {
        if (typeof popup !== 'undefined') {
          popup.remove();
        }
        generateInfo(value);
        var mapLayer;
        if (value == '2018_temperature') {
          cLayer = value;

          //remove previously added layer
          mapLayer = map.getLayer('wms-test-layer');
          if (typeof mapLayer !== 'undefined') {
            // Remove map layer & source.
            map.removeLayer('wms-test-layer').removeSource('wms-test-source');
            console.log("remove works");
          }
          //wms layer name
          layer_name = "Misteo:2018_temp";
          map.addSource('wms-test-source', {
            'type': 'raster',
            'tiles': [
              `${WMS_URL}?bbox={bbox-epsg-3857}&format=image/png&service=WMS&version=1.1.1&request=GetMap&srs=EPSG:3857&transparent=true&width=256&height=256&layers=${layer_name}`
            ],
            'tileSize': 256
          });
          map.addLayer({
              'id': 'wms-test-layer',
              'type': 'raster',
              'source': 'wms-test-source',
              'paint': {}
            },
            'aeroway-line'
          );


        }else if (value == "DEM") {
          cLayer = value;
          mapLayer = map.getLayer('wms-test-layer');
          if (typeof mapLayer !== 'undefined') {
            // Remove map layer & source.
            map.removeLayer('wms-test-layer').removeSource('wms-test-source');
            console.log("remove works");
          }
          //wms layer name
          layer_name = "Misteo:maharashtra_dem";
          map.addSource('wms-test-source', {
            'type': 'raster',
            'tiles': [
              `${WMS_URL}?bbox={bbox-epsg-3857}&format=image/png&service=WMS&version=1.1.1&request=GetMap&srs=EPSG:3857&transparent=true&width=256&height=256&layers=${layer_name}`
            ],
            'tileSize': 256
          });
          map.addLayer({
              'id': 'wms-test-layer',
              'type': 'raster',
              'source': 'wms-test-source',
              'paint': {}
            },
            'aeroway-line'
          );

        }

        else if (value == '2018_precipitation') {
          cLayer = value;
          mapLayer = map.getLayer('wms-test-layer');
          if (typeof mapLayer !== 'undefined') {
            // Remove map layer & source.
            map.removeLayer('wms-test-layer').removeSource('wms-test-source');
            console.log("remove works");
          }
          //wms layer name
          layer_name = "Misteo:2018_pre";
          map.addSource('wms-test-source', {
            'type': 'raster',
            'tiles': [
              `${WMS_URL}?bbox={bbox-epsg-3857}&format=image/png&service=WMS&version=1.1.1&request=GetMap&srs=EPSG:3857&transparent=true&width=256&height=256&layers=${layer_name}`
            ],
            'tileSize': 256
          });
          map.addLayer({
              'id': 'wms-test-layer',
              'type': 'raster',
              'source': 'wms-test-source',
              'paint': {}
            },
            'aeroway-line'
          );

        } else if (value == '2019_heat_index') {
          cLayer = value;
          mapLayer = map.getLayer('wms-test-layer');
          if (typeof mapLayer !== 'undefined') {
            // Remove map layer & source.
            map.removeLayer('wms-test-layer').removeSource('wms-test-source');
            console.log("remove works");
          }
          //wms layer name
          layer_name = "Misteo:2019_HI";
          map.addSource('wms-test-source', {
            'type': 'raster',
            'tiles': [
              `${WMS_URL}?bbox={bbox-epsg-3857}&format=image/png&service=WMS&version=1.1.1&request=GetMap&srs=EPSG:3857&transparent=true&width=256&height=256&layers=${layer_name}`
            ],
            'tileSize': 256
          });
          map.addLayer({
              'id': 'wms-test-layer',
              'type': 'raster',
              'source': 'wms-test-source',
              'paint': {}
            },
            'aeroway-line'
          );


        } else if (value == '2019_precipitation') {
          cLayer = value;
          mapLayer = map.getLayer('wms-test-layer');
          if (typeof mapLayer !== 'undefined') {
            // Remove map layer & source.
            map.removeLayer('wms-test-layer').removeSource('wms-test-source');
            console.log("remove works");
          }
          //wms layer name
          layer_name = "Misteo:2019_prec";
          map.addSource('wms-test-source', {
            'type': 'raster',
            'tiles': [
              `${WMS_URL}?bbox={bbox-epsg-3857}&format=image/png&service=WMS&version=1.1.1&request=GetMap&srs=EPSG:3857&transparent=true&width=256&height=256&layers=${layer_name}`
            ],
            'tileSize': 256
          });
          map.addLayer({
              'id': 'wms-test-layer',
              'type': 'raster',
              'source': 'wms-test-source',
              'paint': {}
            },
            'aeroway-line'
          );


        } else if (value == "2020_feb_no2") {
          cLayer = value;
          mapLayer = map.getLayer('wms-test-layer');
          if (typeof mapLayer !== 'undefined') {
            // Remove map layer & source.
            map.removeLayer('wms-test-layer').removeSource('wms-test-source');
            console.log("remove works");
          }
          //wms layer name
          layer_name = "Misteo:No2";
          map.addSource('wms-test-source', {
            'type': 'raster',
            'tiles': [
              `${WMS_URL}?bbox={bbox-epsg-3857}&format=image/png&service=WMS&version=1.1.1&request=GetMap&srs=EPSG:3857&transparent=true&width=256&height=256&layers=${layer_name}`
            ],
            'tileSize': 256
          });
          map.addLayer({
              'id': 'wms-test-layer',
              'type': 'raster',
              'source': 'wms-test-source',
              'paint': {}
            },
            'aeroway-line'
          );

        } else if (value == "2019_temperature") {
          cLayer = value;
          mapLayer = map.getLayer('wms-test-layer');
          if (typeof mapLayer !== 'undefined') {
            // Remove map layer & source.
            map.removeLayer('wms-test-layer').removeSource('wms-test-source');
            console.log("remove works");
          }
          //wms layer name
          layer_name = "Misteo:2019_temp";
          map.addSource('wms-test-source', {
            'type': 'raster',
            'tiles': [
              `${WMS_URL}?bbox={bbox-epsg-3857}&format=image/png&service=WMS&version=1.1.1&request=GetMap&srs=EPSG:3857&transparent=true&width=256&height=256&layers=${layer_name}`
            ],
            'tileSize': 256
          });
          map.addLayer({
              'id': 'wms-test-layer',
              'type': 'raster',
              'source': 'wms-test-source',
              'paint': {}
            },
            'aeroway-line'
          );

        } else if (value == "2019_wind") {
          cLayer = value;
          mapLayer = map.getLayer('wms-test-layer');
          if (typeof mapLayer !== 'undefined') {
            // Remove map layer & source.
            map.removeLayer('wms-test-layer').removeSource('wms-test-source');
            console.log("remove works");
          }
          //wms layer name
          layer_name = "Misteo:2019_wind";
          map.addSource('wms-test-source', {
            'type': 'raster',
            'tiles': [
              `${WMS_URL}?bbox={bbox-epsg-3857}&format=image/png&service=WMS&version=1.1.1&request=GetMap&srs=EPSG:3857&transparent=true&width=256&height=256&layers=${layer_name}`
            ],
            'tileSize': 256
          });
          map.addLayer({
              'id': 'wms-test-layer',
              'type': 'raster',
              'source': 'wms-test-source',
              'paint': {}
            },
            'aeroway-line'
          );

        } else if (value == "GTI") {
          cLayer = value;
          mapLayer = map.getLayer('wms-test-layer');
          if (typeof mapLayer !== 'undefined') {
            // Remove map layer & source.
            map.removeLayer('wms-test-layer').removeSource('wms-test-source');
            console.log("remove works");
          }
          //wms layer name
          layer_name = "Misteo:GTI";
          map.addSource('wms-test-source', {
            'type': 'raster',
            'tiles': [
              `${WMS_URL}?bbox={bbox-epsg-3857}&format=image/png&service=WMS&version=1.1.1&request=GetMap&srs=EPSG:3857&transparent=true&width=256&height=256&layers=${layer_name}`
            ],
            'tileSize': 256
          });
          map.addLayer({
              'id': 'wms-test-layer',
              'type': 'raster',
              'source': 'wms-test-source',
              'paint': {}
            },
            'aeroway-line'
          );

        } else if (value == "DNI") {
          cLayer = value;
          mapLayer = map.getLayer('wms-test-layer');
          if (typeof mapLayer !== 'undefined') {
            // Remove map layer & source.
            map.removeLayer('wms-test-layer').removeSource('wms-test-source');
            console.log("remove works");
          }
          //wms layer name
          layer_name = "Misteo:DNI";
          map.addSource('wms-test-source', {
            'type': 'raster',
            'tiles': [
              `${WMS_URL}?bbox={bbox-epsg-3857}&format=image/png&service=WMS&version=1.1.1&request=GetMap&srs=EPSG:3857&transparent=true&width=256&height=256&layers=${layer_name}`
            ],
            'tileSize': 256
          });
          map.addLayer({
              'id': 'wms-test-layer',
              'type': 'raster',
              'source': 'wms-test-source',
              'paint': {}
            },
            'aeroway-line'
          );

        } else if (value == "OPTA") {
          cLayer = value;
          mapLayer = map.getLayer('wms-test-layer');
          if (typeof mapLayer !== 'undefined') {
            // Remove map layer & source.
            map.removeLayer('wms-test-layer').removeSource('wms-test-source');
            console.log("remove works");
          }
          //wms layer name
          layer_name = "Misteo:OPTA";
          map.addSource('wms-test-source', {
            'type': 'raster',
            'tiles': [
              `${WMS_URL}?bbox={bbox-epsg-3857}&format=image/png&service=WMS&version=1.1.1&request=GetMap&srs=EPSG:3857&transparent=true&width=256&height=256&layers=${layer_name}`
            ],
            'tileSize': 256
          });
          map.addLayer({
              'id': 'wms-test-layer',
              'type': 'raster',
              'source': 'wms-test-source',
              'paint': {}
            },
            'aeroway-line'
          );

        } else if (value == "DIF") {
          cLayer = value;
          mapLayer = map.getLayer('wms-test-layer');
          if (typeof mapLayer !== 'undefined') {
            // Remove map layer & source.
            map.removeLayer('wms-test-layer').removeSource('wms-test-source');
            console.log("remove works");
          }
          //wms layer name
          layer_name = "Misteo:DIF";
          map.addSource('wms-test-source', {
            'type': 'raster',
            'tiles': [
              `${WMS_URL}?bbox={bbox-epsg-3857}&format=image/png&service=WMS&version=1.1.1&request=GetMap&srs=EPSG:3857&transparent=true&width=256&height=256&layers=${layer_name}`
            ],
            'tileSize': 256
          });
          map.addLayer({
              'id': 'wms-test-layer',
              'type': 'raster',
              'source': 'wms-test-source',
              'paint': {}
            },
            'aeroway-line'
          );


        } else if (value == "GHI") {
          cLayer = value;
          mapLayer = map.getLayer('wms-test-layer');
          if (typeof mapLayer !== 'undefined') {
            // Remove map layer & source.
            map.removeLayer('wms-test-layer').removeSource('wms-test-source');
            console.log("remove works");
          }
          //wms layer name
          layer_name = "Misteo:GHI_new";
          map.addSource('wms-test-source', {
            'type': 'raster',
            'tiles': [
              `${WMS_URL}?bbox={bbox-epsg-3857}&format=image/png&service=WMS&version=1.1.1&request=GetMap&srs=EPSG:3857&transparent=true&width=256&height=256&layers=${layer_name}`
            ],
            'tileSize': 256
          });
          map.addLayer({
              'id': 'wms-test-layer',
              'type': 'raster',
              'source': 'wms-test-source',
              'paint': {}
            },
            'aeroway-line'
          );

        } else if (value == "PVOUT") {
          cLayer = value;
          mapLayer = map.getLayer('wms-test-layer');
          if (typeof mapLayer !== 'undefined') {
            // Remove map layer & source.
            map.removeLayer('wms-test-layer').removeSource('wms-test-source');
            console.log("remove works");
          }
          //wms layer name
          layer_name = "Misteo:PVOUT";
          map.addSource('wms-test-source', {
            'type': 'raster',
            'tiles': [
              `${WMS_URL}?bbox={bbox-epsg-3857}&format=image/png&service=WMS&version=1.1.1&request=GetMap&srs=EPSG:3857&transparent=true&width=256&height=256&layers=${layer_name}`
            ],
            'tileSize': 256
          });
          map.addLayer({
              'id': 'wms-test-layer',
              'type': 'raster',
              'source': 'wms-test-source',
              'paint': {}
            },
            'aeroway-line'
          );

        } else if (value == 'IBTrACS_Last3years') {
          cLayer = value;
          mapLayer = map.getLayer('wms-test-layer');
          if (typeof mapLayer !== 'undefined') {
            // Remove map layer & source.
            map.removeLayer('wms-test-layer').removeSource('wms-test-source');
            console.log("remove works");
          }
          //wms layer name
          layer_name = "Misteo:tracks";
          map.addSource('wms-test-source', {
            'type': 'raster',
            'tiles': [
              `${WMS_URL}?bbox={bbox-epsg-3857}&format=image/png&service=WMS&version=1.1.1&request=GetMap&srs=EPSG:3857&transparent=true&width=256&height=256&layers=${layer_name}`
            ],
            'tileSize': 256
          });
          map.addLayer({
              'id': 'wms-test-layer',
              'type': 'raster',
              'source': 'wms-test-source',
              'paint': {}
            },
            'aeroway-line'
          );

        } else if (value == "seismic_data") {
          cLayer = value;
          mapLayer = map.getLayer('wms-test-layer');
          if (typeof mapLayer !== 'undefined') {
            // Remove map layer & source.
            map.removeLayer('wms-test-layer').removeSource('wms-test-source');
            console.log("remove works");
          }
          //wms layer name
          layer_name = "Misteo:seismic";
          map.addSource('wms-test-source', {
            'type': 'raster',
            'tiles': [
              `${WMS_URL}?bbox={bbox-epsg-3857}&format=image/png&service=WMS&version=1.1.1&request=GetMap&srs=EPSG:3857&transparent=true&width=256&height=256&layers=${layer_name}`
            ],
            'tileSize': 256
          });
          map.addLayer({
              'id': 'wms-test-layer',
              'type': 'raster',
              'source': 'wms-test-source',
              'paint': {}
            },
            'aeroway-line'
          );

        } else if (value == "meteorological_events") {
          cLayer = value;
          mapLayer = map.getLayer('wms-test-layer');
          if (typeof mapLayer !== 'undefined') {
            // Remove map layer & source.
            map.removeLayer('wms-test-layer').removeSource('wms-test-source');
            console.log("remove works");
          }
          //wms layer name
          layer_name = "Misteo:meteorological";
          map.addSource('wms-test-source', {
            'type': 'raster',
            'tiles': [
              `${WMS_URL}?bbox={bbox-epsg-3857}&format=image/png&service=WMS&version=1.1.1&request=GetMap&srs=EPSG:3857&transparent=true&width=256&height=256&layers=${layer_name}`
            ],
            'tileSize': 256
          });
          map.addLayer({
              'id': 'wms-test-layer',
              'type': 'raster',
              'source': 'wms-test-source',
              'paint': {}
            },
            'aeroway-line'
          );

        } else if (value == "climatological_events") {
          cLayer = value;
          mapLayer = map.getLayer('wms-test-layer');
          if (typeof mapLayer !== 'undefined') {
            // Remove map layer & source.
            map.removeLayer('wms-test-layer').removeSource('wms-test-source');
            console.log("remove works");
          }
          //wms layer name
          layer_name = "  Misteo:climatological";
          map.addSource('wms-test-source', {
            'type': 'raster',
            'tiles': [
              `${WMS_URL}?bbox={bbox-epsg-3857}&format=image/png&service=WMS&version=1.1.1&request=GetMap&srs=EPSG:3857&transparent=true&width=256&height=256&layers=${layer_name}`
            ],
            'tileSize': 256
          });
          map.addLayer({
              'id': 'wms-test-layer',
              'type': 'raster',
              'source': 'wms-test-source',
              'paint': {}
            },
            'aeroway-line'
          );

        } else if (value == "hydrological_events") {
          cLayer = value;
          mapLayer = map.getLayer('wms-test-layer');
          if (typeof mapLayer !== 'undefined') {
            // Remove map layer & source.
            map.removeLayer('wms-test-layer').removeSource('wms-test-source');
            console.log("remove works");
          }
          //wms layer name
          layer_name = "Misteo:hydrological";
          map.addSource('wms-test-source', {
            'type': 'raster',
            'tiles': [
              `${WMS_URL}?bbox={bbox-epsg-3857}&format=image/png&service=WMS&version=1.1.1&request=GetMap&srs=EPSG:3857&transparent=true&width=256&height=256&layers=${layer_name}`
            ],
            'tileSize': 256
          });
          map.addLayer({
              'id': 'wms-test-layer',
              'type': 'raster',
              'source': 'wms-test-source',
              'paint': {}
            },
            'aeroway-line'
          );

        } else if (value == "NLE_India_Geojsn") {
          cLayer = value;
          mapLayer = map.getLayer('wms-test-layer');
          if (typeof mapLayer !== 'undefined') {
            // Remove map layer & source.
            map.removeLayer('wms-test-layer').removeSource('wms-test-source');
            console.log("remove works");
          }
          //wms layer name
          layer_name = "Misteo:natural";
          map.addSource('wms-test-source', {
            'type': 'raster',
            'tiles': [
              `${WMS_URL}?bbox={bbox-epsg-3857}&format=image/png&service=WMS&version=1.1.1&request=GetMap&srs=EPSG:3857&transparent=true&width=256&height=256&layers=${layer_name}`
            ],
            'tileSize': 256
          });
          map.addLayer({
              'id': 'wms-test-layer',
              'type': 'raster',
              'source': 'wms-test-source',
              'paint': {}
            },
            'aeroway-line'
          );

        } else if (value == "geophysical_events") {
          cLayer = value;
          mapLayer = map.getLayer('wms-test-layer');
          if (typeof mapLayer !== 'undefined') {
            // Remove map layer & source.
            map.removeLayer('wms-test-layer').removeSource('wms-test-source');
            console.log("remove works");
          }
          //wms layer name
          layer_name = "Misteo:buffered";
          map.addSource('wms-test-source', {
            'type': 'raster',
            'tiles': [
              `${WMS_URL}?bbox={bbox-epsg-3857}&format=image/png&service=WMS&version=1.1.1&request=GetMap&srs=EPSG:3857&transparent=true&width=256&height=256&layers=${layer_name}`
            ],
            'tileSize': 256
          });
          map.addLayer({
              'id': 'wms-test-layer',
              'type': 'raster',
              'source': 'wms-test-source',
              'paint': {}
            },
            'aeroway-line'
          );

        } else if (value == '2018_heat_index') {
          cLayer = value;
          mapLayer = map.getLayer('wms-test-layer');
          if (typeof mapLayer !== 'undefined') {
            // Remove map layer & source.
            map.removeLayer('wms-test-layer').removeSource('wms-test-source');
            console.log("remove works");
          }
          //wms layer name
          layer_name = "Misteo:HI";
          map.addSource('wms-test-source', {
            'type': 'raster',
            'tiles': [
              `${WMS_URL}?bbox={bbox-epsg-3857}&format=image/png&service=WMS&version=1.1.1&request=GetMap&srs=EPSG:3857&transparent=true&width=256&height=256&layers=${layer_name}`
            ],
            'tileSize': 256
          });
          map.addLayer({
              'id': 'wms-test-layer',
              'type': 'raster',
              'source': 'wms-test-source',
              'paint': {}
            },
            'aeroway-line'
          );
        } 
        else if (value == 'coc') {
          cLayer = value;
          mapLayer = map.getLayer('wms-test-layer');
          if (typeof mapLayer !== 'undefined') {
            // Remove map layer & source.
            map.removeLayer('wms-test-layer').removeSource('wms-test-source');
            console.log("remove works");
          }
          //wms layer name
          layer_name = "Misteo:india_pincodes";
          map.addSource('wms-test-source', {
            'type': 'raster',
            'tiles': [
              `${WMS_URL}?bbox={bbox-epsg-3857}&format=image/png&service=WMS&version=1.1.1&request=GetMap&srs=EPSG:3857&transparent=true&width=256&height=256&layers=${layer_name}`
            ],
            'tileSize': 256
          });
          map.addLayer({
              'id': 'wms-test-layer',
              'type': 'raster',
              'source': 'wms-test-source',
              'paint': {}
            },
            'aeroway-line'
          );


        } 
        else if(value=="live"){
          cLayer = value;
          mapLayer = map.getLayer('wms-test-layer');
          if (typeof mapLayer !== 'undefined') {
            // Remove map layer & source.
            map.removeLayer('wms-test-layer').removeSource('wms-test-source');
            console.log("remove works");
          }
       

            console.log("live ");
            var xmlHttp = new XMLHttpRequest();
            xmlHttp.open("GET", 'https://cors-anywhere.herokuapp.com/http://ds.iris.edu/ieb/ws/event/1/query/?format=text&nodata=404&caller=ieb&starttime=2020-04-14&endtime=2020-04-14&orderby=time-desc&src=iris&limit=50&maxlat=41.856&minlat=-1.117&maxlon=98.518&minlon=61.956', false); // false for synchronous request
            xmlHttp.withCredentials = false;
            xmlHttp.send(null);
            response = xmlHttp.responseText;
 
            // replace | with comma 
            var newresp = response.replace(/\|/g, ",");
            //split the string using newline
            var n=newresp.split("\n");
            //remove first element from the array(count)
            n.shift();
            n.pop();
            n.pop();
            var liveseismic = {
                type: 'FeatureCollection',
                features: []
            };
            console.log(n.length)
            if(n.length==0){
              console.log("no data found");
            }
            for(var x in n){ 
                console.log(n[x]); 
                var eve=n[x].split(",") 
                console.log(eve);
                console.log(eve[0])
                liveseismic.features.push({ 
                    "geometry": {"type": "Point",
                    "coordinates":[parseFloat(eve[3]),parseFloat(eve[2])]},
                    "type": "Feature",
                    "properties":{
                    "id" : eve[0],
                    "date" : eve[1],
                    "lat" : eve[2],
                    "long" : eve[3],
                    "depthinkm" : eve[5],
                    "mag" : eve[11],
                    "region" : eve[13] 
                    }


                });

                eve = [];
            }

            var a= JSON.stringify(liveseismic);
            map.addSource('wms-test-source', {
            'type': 'geojson',
            'data': liveseismic
            });
            map.addLayer({
            'id': 'wms-test-layer',
            'type': 'circle',
            'source': 'wms-test-source',
            "paint": {
                "circle-radius": 10,
                "circle-color": "dodgerblue",
                'circle-opacity': 0.5,
                'circle-stroke-color': 'white',
                'circle-stroke-width': 3,
            }
            });

            console.log(a);
            
            

            // console.log(newresp);


        }

      }
      map.on('click', function(e) {
        var bounds = map.getBounds();
        var bboxArray = bounds.toArray().flat().join(','); //make of the bbox array
        var mapWidth = map.getContainer().clientWidth;
        var mapHeight = map.getContainer().clientHeight;
        var clickedPoint = e.point;
        //Build the URL
        var featureInfoURL = `${WMS_URL}?&INFO_FORMAT=application/json&REQUEST=GetFeatureInfo&EXCEPTIONS=application/vnd.ogc.se_xml&SERVICE=WMS&VERSION=1.1.1&WIDTH=${mapWidth}&HEIGHT=${mapHeight}&X=${clickedPoint.x}&Y=${clickedPoint.y}&BBOX=${bboxArray}&LAYERS=${layer_name}&QUERY_LAYERS=${layer_name}`;
        console.log(featureInfoURL);

        //make a request using XML Http Request

        //Listner for the response of the Request
        function onResponse() {
          var response;
          var name;
          if (cLayer == "2019_temperature" || cLayer == "2018_temperature") {
            response = JSON.parse(this.responseText);
            name = response.features[0].properties.temperatur;
            //Show as Popup
            popup = new mapboxgl.Popup({
                closeOnClick: true
              })
              .setLngLat(e.lngLat)
              .setHTML("<h5>TEMPERATURE</h5><hr>" + "TEMPERATURE : " + name.toFixed(2) + " &#8451;<br>PINCODE : " +
                response.features[0].properties.pincode + "<br>OFFICE NAME : " +
                response.features[0].properties.officename + "<br>POST OFFICE : " +
                response.features[0].properties.officetype + "<br>DISTRICT : " +
                response.features[0].properties.district + "<br>STATE : " +
                response.features[0].properties.state)
              .addTo(map);
          } else if (cLayer == "2018_precipitation" || cLayer == "2019_precipitation") {
            response = JSON.parse(this.responseText);
            name = response.features[0].properties.precipitat;
            //Show as Popup
            popup = new mapboxgl.Popup({
                closeOnClick: true
              })
              .setLngLat(e.lngLat)
              .setHTML("<h5>PRECIPITATION</h5><hr>" + "PRECIPITATION :" + name.toFixed(2) + " mm<br>PINCODE:" +
                response.features[0].properties.pincode + "<br>OFFICE NAME : " +
                response.features[0].properties.officename + "<br>POST OFFICE : " +
                response.features[0].properties.officetype + "<br>DISTRICT : " +
                response.features[0].properties.district + "<br>STATE : " +
                response.features[0].properties.state)
              .addTo(map);

          } else if (cLayer == "DIF") {
            response = JSON.parse(this.responseText);
            name = response.features[0].properties.GRAY_INDEX;
            //Show as Popup
            popup = new mapboxgl.Popup({
                closeOnClick: true
              })
              .setLngLat(e.lngLat)
              .setHTML("<h5>DHI</h5><hr>" + "DHI : " + name.toFixed(2) + " kWh/m2")
              .addTo(map);

          } else if (cLayer == "OPTA") {
            response = JSON.parse(this.responseText);
            name = response.features[0].properties.GRAY_INDEX;
            //Show as Popup
            popup = new mapboxgl.Popup({
                closeOnClick: true
              })
              .setLngLat(e.lngLat)
              .setHTML("<h5>OPTA</h5><hr>" + "OPTA : " + name.toFixed(2) + " \xB0")
              .addTo(map);

          }
           else if (cLayer == "GTI") {
            response = JSON.parse(this.responseText);
            name = response.features[0].properties.GRAY_INDEX;
            //Show as Popup
            popup = new mapboxgl.Popup({
                closeOnClick: true
              })
              .setLngLat(e.lngLat)
              .setHTML("<h5>GTI</h5><hr>" + "GTI : " + name.toFixed(2) + " Kwh/m2")
              .addTo(map);

          } else if (cLayer == "DNI") {
            response = JSON.parse(this.responseText);
            name = response.features[0].properties.GRAY_INDEX;
            //Show as Popup
            popup = new mapboxgl.Popup({
                closeOnClick: true
              })
              .setLngLat(e.lngLat)
              .setHTML("<h5>DNI</h5><hr>" + "DNI : " + name.toFixed(2) + " kWh/m2")
              .addTo(map);

          } else if (cLayer == "2020_feb_no2") {
            response = JSON.parse(this.responseText);
            name = response.features[0].properties.no2mean;
            //Show as Popup
            popup = new mapboxgl.Popup({
                closeOnClick: true
              })
              .setLngLat(e.lngLat)
              .setHTML("<h5>NO2</h5><hr>" + "NO2 : " + name + " mol/m^2<br>PINCODE : " +
                response.features[0].properties.pincode + "<br>OFFICE NAME : " +
                response.features[0].properties.officename + "<br>POST OFFICE : " +
                response.features[0].properties.officetype + "<br>DISTRICT : " +
                response.features[0].properties.district + "<br>STATE : " +
                response.features[0].properties.state)
              .addTo(map);

          } else if (cLayer == "2019_wind") {
            response = JSON.parse(this.responseText);
            name = response.features[0].properties.wind;
            //Show as Popup
            popup = new mapboxgl.Popup({
                closeOnClick: true
              })
              .setLngLat(e.lngLat)
              .setHTML("<h5>WIND SPEED</h5><hr>" + "WIND SPEED: " + name.toFixed(2) + " m/s<br>PINCODE : " +
                response.features[0].properties.pincode + "<br>OFFICE NAME : " +
                response.features[0].properties.officename + "<br>POST OFFICE : " +
                response.features[0].properties.officetype + "<br>DISTRICT : " +
                response.features[0].properties.district + "<br>STATE : " +
                response.features[0].properties.state)
              .addTo(map);

          } else if (cLayer == "2019_heat_index" || cLayer == "2018_heat_index") {
            response = JSON.parse(this.responseText);
            name = response.features[0].properties.heat_index;
            //Show as Popup
            popup = new mapboxgl.Popup({
                closeOnClick: true
              })
              .setLngLat(e.lngLat)
              .setHTML("<h5>CATTLE HEAT INDEX</h5><hr>" + "HEAT INDEX : " + name.toFixed(2) + "<br>PINCODE :" +
                response.features[0].properties.pincode + "<br>OFFICE NAME : " +
                response.features[0].properties.officename + "<br>POST OFFICE  : " +
                response.features[0].properties.officetype + "<br>DISTRICT : " +
                response.features[0].properties.district + "<br>STATE : " +
                response.features[0].properties.state)
              .addTo(map);

          } else if (cLayer == "GHI") {
            response = JSON.parse(this.responseText);
            name = response.features[0].properties.GRAY_INDEX;
            //Show as Popup
            popup = new mapboxgl.Popup({
                closeOnClick: true
              })
              .setLngLat(e.lngLat)
              .setHTML("<h5>GHI</h5><hr>" + "GHI : " + name.toFixed(2) + " kWh/m2")
              .addTo(map);

          } else if (cLayer == "PVOUT") {
            response = JSON.parse(this.responseText);
            name = response.features[0].properties.GRAY_INDEX;
            //Show as Popup
            popup = new mapboxgl.Popup({
                closeOnClick: true
              })
              .setLngLat(e.lngLat)
              .setHTML("<h5>PVOUT</h5><hr>" + "PVOUT : " + name.toFixed(2) + " kWh")
              .addTo(map);

          }
          else if (cLayer == "DEM") {
            response = JSON.parse(this.responseText);
            name = response.features[0].properties.GRAY_INDEX;
            //Show as Popup
            popup = new mapboxgl.Popup({
                closeOnClick: true
              })
              .setLngLat(e.lngLat)
              .setHTML("<h5>DEM</h5><hr>" + "DEM : " + name.toFixed(2) + " m")
              .addTo(map);

          } else if (cLayer == "seismic_data") {
            response = JSON.parse(this.responseText);
            name = response.features[0].properties.Magnitude;
            //Show as Popup
            popup = new mapboxgl.Popup({
                closeOnClick: true
              })
              .setLngLat(e.lngLat)
              .setHTML("<h5>SEISMIC DATA</h5><hr>" + "Magnitude : " + name + "<br>Location : " +
                response.features[0].properties.Location + "<br>Depth : " +
                response.features[0].properties.Depth)
              .addTo(map);
          }

          // } else if (cLayer == "NLE_India_Geojsn") {
          //   response = JSON.parse(this.responseText);
          //   name = response.features[0].properties.name;
          //   //Show as Popup
          //   popup = new mapboxgl.Popup({
          //       closeOnClick: true
          //     })
          //     .setLngLat(e.lngLat)
          //     .setHTML("<h5>NATURAL LOSS EVENTS</h5><hr>" + "Type :" + name + "<br>Location : " +
          //       response.features[0].properties.State + "<br>Event Family : " +
          //       response.features[0].properties.eventFamil + "<br>Year : " +
          //       response.features[0].properties.Year)
          //     .addTo(map);

          // } 
          else if (cLayer == "IBTrACS_Last3years") {
            response = JSON.parse(this.responseText);
            name = response.features[0].properties.SID;
            //Show as Popup
            popup = new mapboxgl.Popup({
                closeOnClick: true
              })
              .setLngLat(e.lngLat)
              .setHTML("<h5>TROPICAL CYCLONE TRACKS</h5><hr>" + "Name : " + name + "<br>ISO TIME : " +
                response.features[0].properties.ISO_TIME + "<br>Storm speed : " +
                response.features[0].properties.STORM_SPD + "<br>Year : " +
                response.features[0].properties.year)
              .addTo(map);

          } else if (cLayer == "geophysical_events") {
            response = JSON.parse(this.responseText);
            name = response.features[0].properties.name;
            //Show as Popup
            popup = new mapboxgl.Popup({
                closeOnClick: true
              })
              .setLngLat(e.lngLat)
              .setHTML("<h6>GEOPHYSICAL EVENTS</h6><hr>" + "Type : " + name + "<br>Location : " +
                response.features[0].properties.State + "<br>Year : " +
                response.features[0].properties.Year)
              .addTo(map);

          } else if (cLayer == "climatological_events") {
            response = JSON.parse(this.responseText);
            name = response.features[0].properties.name;
            //Show as Popup
            popup = new mapboxgl.Popup({
                closeOnClick: true
              })
              .setLngLat(e.lngLat)
              .setHTML("<h6>CLIMATOLOGICAL EVENTS</h6><hr>" + "Type : " + name + "<br>Location : " +
                response.features[0].properties.State + "<br>Year : " +
                response.features[0].properties.Year)
              .addTo(map);

          } else if (cLayer == "hydrological_events") {
            response = JSON.parse(this.responseText);
            name = response.features[0].properties.name;
            //Show as Popup
            popup = new mapboxgl.Popup({
                closeOnClick: true
              })
              .setLngLat(e.lngLat)
              .setHTML("<h6>HYDROLOGICAL EVENTS</h6><hr>" + "Type : " + name + "<br>Location : " +
                response.features[0].properties.State + "<br>Year : " +
                response.features[0].properties.Year)
              .addTo(map);

          }
          else if(cLayer=="live"){

          }
          else if (cLayer == "meteorological_events") {
            response = JSON.parse(this.responseText);
            name = response.features[0].properties.name;
            //Show as Popup
            popup = new mapboxgl.Popup({
                closeOnClick: true
              })
              .setLngLat(e.lngLat)
              .setHTML("<h6>METEOROLOGICAL EVENTS</h6><hr>" + "Type : " + name + "<br>State : " +
                response.features[0].properties.State + "<br>Year : " +
                response.features[0].properties.Year)
              .addTo(map);

          } else if (cLayer == "coc") {
            console.log("it works");
            var lat = e.lngLat.lat;
            var lng = e.lngLat.lng;
            console.log(lat, lng);
            var xmlHttp = new XMLHttpRequest();
            xmlHttp.open("GET", 'https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=' + lat + '&lon=' + lng + '&zoom=18', false); // false for synchronous request
            xmlHttp.send(null);
            response = xmlHttp.responseText;
            var place_name = JSON.parse(response);
            console.log(place_name.display_name);


            var dailyweather;
            $.ajax({
              type: "GET",
              url: "http://weather.dtn.com/basic/rest-3.4/obsfcst.wsgi?dataType=HourlyLatestObservation&dataTypeMode=0001&latitude=" + lat + "&longitude=" + lng,
              beforeSend: function(xhr) {
                xhr.setRequestHeader('Authorization', 'Basic bWlzdGVvMjUzODMyMTpCWVBJS1R6UERjdXdMRzUz');
              },
              success: function(result) {
                console.log("api call is success");
                dailyweather = result;
                //heat index
                var heati = result.getElementsByTagName("wx:locationResponseList")[0].childNodes[0].getElementsByTagName("wx:heatIndex")[0].
                getElementsByTagName("wx:value")[0];
                var heatindex = heati.childNodes[0];
                console.log("Heat index:", heatindex);
                //relative humidity
                var relat = result.getElementsByTagName("wx:locationResponseList")[0].childNodes[0].getElementsByTagName("wx:relativeHumidity")[0].
                getElementsByTagName("wx:value")[0];
                var relativehumidity = relat.childNodes[0];
                console.log("relative humidity:", relativehumidity);
                //temperature
                var temp = result.getElementsByTagName("wx:locationResponseList")[0].childNodes[0].getElementsByTagName("wx:temperature")[0].
                getElementsByTagName("wx:value")[0];
                var temperature = temp.childNodes[0];
                console.log("temperature:", temperature);
                //wind speed
                var ws = result.getElementsByTagName("wx:locationResponseList")[0].childNodes[0].getElementsByTagName("wx:windSpeed")[0].
                getElementsByTagName("wx:value")[0];
                var windspeed = ws.childNodes[0];
                console.log("Wind speed:", windspeed);
                //visibility
                var visi = result.getElementsByTagName("wx:locationResponseList")[0].childNodes[0].getElementsByTagName("wx:visibility")[0].
                getElementsByTagName("wx:value")[0];
                var visib = visi.childNodes[0];
                console.log("visiblity:", visib);
                var wd = result.getElementsByTagName("wx:locationResponseList")[0].childNodes[0].getElementsByTagName("wx:windDirection")[0].
                getElementsByTagName("wx:value")[0];
                var wdirection = wd.childNodes[0]
                console.log(heatindex);
                var temdeg = (temp.childNodes[0].nodeValue - 32) * 5 / 9
                console.log("degree:", temdeg.toFixed(2))
                var msg;
                if (heati.childNodes[0].nodeValue >= 80 && heati.childNodes[0].nodeValue <= 90) {
                  msg = "Caution";
                } else if (heati.childNodes[0].nodeValue >= 91 && heati.childNodes[0].nodeValue <= 103) {
                  msg = "Extreme Caution";

                } else if (heati.childNodes[0].nodeValue >= 104 && heati.childNodes[0].nodeValue <= 124) {
                  msg = "Danger";

                } else if (heati.childNodes[0].nodeValue >= 91) {
                  msg = "Extreme Danger"

                } else {
                  msg = "No Stress"
                }
                popup = new mapboxgl.Popup({
                    closeOnClick: true
                  })
                  .setLngLat([lng, lat])
                  .setHTML('Location :  ' +
                    place_name.display_name + '<hr><h5>Current Observed Conditions</h5>Temperature :  ' +
                    temdeg.toFixed(2) + ' &#8451;<br>Relative Humidity :&nbsp&nbsp' +
                    relat.childNodes[0].nodeValue + ' %<br>Heat Index :&nbsp&nbsp' +
                    heati.childNodes[0].nodeValue + ' <br>Wind Speed :&nbsp&nbsp' +
                    ws.childNodes[0].nodeValue + ' mph<br>Wind Direction :&nbsp&nbsp' +
                    wd.childNodes[0].nodeValue + ' deg<br>Visiblity :&nbsp&nbsp' +
                    visi.childNodes[0].nodeValue + ' SM<br>Stress Level :&nbsp&nbsp' +
                    msg )
                  .addTo(map);


                console.log(visi.childNodes[0].nodeValue)

              },
              error: function(result) {
                console.log("api call filed")
              }
            });
          }

        }
        //Making the request here
        var oReq = new XMLHttpRequest();
        oReq.addEventListener("load", onResponse);
        oReq.open("GET", featureInfoURL);
        oReq.send();


      });
      //function for displaying layer info
      function generateInfo(value) {

        var x = document.getElementById("info");
        var min = document.getElementById("min");
        var max = document.getElementById("max");
        var grad = document.getElementById("colorscale");
        var di = document.getElementById("dotinfo");
        var d = document.getElementById("dot");
        var met = document.getElementById("met");
        var metid = document.getElementById("metinfo");
        var hy = document.getElementById("hyd");
        var hyinfo = document.getElementById("hydinfo");
        var leg = document.getElementById("legend");
        var lin = document.getElementById("line");

        if (value == "2019_temperature" || value == "2018_temperature") {
          x.innerHTML = "<br>Temperature <br><br> The daily average of temperature at 2 meters above ground level.<br><br>Sample Data Used :  01-01-2019 <br>Data Availability : 1984 - present.<br>Granularity :  Pincode<br>Temporal resolution : Daily<br>Primary Data Source : <i>NASA</i>";
          min.innerHTML = "-21&nbsp&#8451";
          max.innerHTML = "&nbsp40&#8451";
          min.style.display = "block";
          max.style.display = "block";
          grad.style.display = 'block';
          leg.style.display = 'block';
          lin.style.display = 'block';
          grad.style.width = "190px";
          di.style.display = "none";
          d.style.display = "none";
        } else if (value == "GHI") {
          x.innerHTML = "<br>GHI - Global horizontal irradiation<br><br> The average daily/yearly sum of global horizontal irradiation (GHI) covering a period of recent 20 years (1999-2018).<br><br>Spatial Resolution : 1 km<br>Primary Data Source :  <i>Global Solar Atlas 2.0</i>";
          min.innerHTML = "414&nbsp<br>Kwh/m2";
          max.innerHTML = "&nbsp2158<br>Kwh/m2";
          min.style.display = "block";
          max.style.display = "block";
          leg.style.display = 'block';
          lin.style.display = 'block';
          grad.style.display = 'block';
          grad.style.width = "190px";
          di.style.display = "none";
          d.style.display = "none";
        } else if (value == "DIF") {
          x.innerHTML = "<br>DHI -Diffuse horizontal irradiation<br><br> The average daily/yearly sum of diffuse horizontal irradiation (DHI) covering a period of recent 20 years (1999-2018).<br><br>Spatial Resolution : 1 km<br>Primary Data Source :  <i>Global Solar Atlas 2.0</i>";
          min.innerHTML = "285&nbsp<br>Kwh/m2";
          max.innerHTML = "&nbsp1000<br>Kwh/m2";
          min.style.display = "block";
          max.style.display = "block";
          leg.style.display = 'block';
          lin.style.display = 'block';
          grad.style.display = 'block';
          grad.style.width = "190px";
          di.style.display = "none";
          d.style.display = "none";
        } else if (value == "PVOUT") {
          x.innerHTML = "<br>PVOUT - Photovoltaic power potential<br><br> The average daily/yearly totals of electricity production from a 1 kW-peak grid-connected solar PV power plant, calculated for a period of recent 20 years (1999-2018).<br><br>Spatial Resolution : 1 km<br>Primary Data Source :  <i>Global Solar Atlas 2.0</i>";
          min.innerHTML = "500&nbsp<br>Kwh";
          max.innerHTML = "&nbsp2205<br>Kwh";
          min.style.display = "block";
          max.style.display = "block";
          leg.style.display = 'block';
          lin.style.display = 'block';
          grad.style.display = 'block';
          grad.style.width = "190px";
          di.style.display = "none";
          d.style.display = "none";
        } else if (value == "DNI") {
          x.innerHTML = "<br>DNI - Direct normal irradiation<br><br> The average daily/yearly sum of direct normal irradiation (DNI) covering a period of recent 20 years (1999-2018).<br><br>Spatial Resolution : 1 km<br>Primary Data Source :  <i>Global Solar Atlas 2.0</i>";
          min.innerHTML = "0&nbsp<br>Kwh/m2";
          max.innerHTML = "&nbsp2824<br>Kwh/m2";
          min.style.display = "block";
          max.style.display = "block";
          grad.style.display = 'block';
          lin.style.display = 'block';
          leg.style.display = 'block';
          grad.style.width = "190px";
          di.style.display = "none";
          d.style.display = "none";
        } else if (value == "2018_precipitation" || value == "2019_precipitation") {
          x.innerHTML = "<br>Precipitation<br><br>The daily average rain rate.<br><br>Sample Data Used :  01-01-2019 <br>Data Availability : 1984 - present<br>Granularity :  Pincode<br>Temporal resolution : Daily<br>Primary Data Source : <i>NASA</i>";
          min.innerHTML = "0&nbspmm";
          max.innerHTML = "&nbsp5 mm";
          min.style.display = "block";
          max.style.display = "block";
          grad.style.display = 'block';
          leg.style.display = 'block';
          lin.style.display = 'block';
          grad.style.width = "190px";
          di.style.display = "none";
          d.style.display = "none";
        } else if (value == "2018_heat_index" || value == "2019_heat_index") {
          x.innerHTML = "<br>Cattle Heat Index<br><br>The daily average of Heat Index at 2 meters above ground level.<br><br>Sample Data Used :  01-01-2019 <br>Data Availability : 1984 - present.<br>Granularity :  Pincode<br>Temporal resolution : Daily<br>Primary Data Source : <i>MistEO's proprietary model</i>";
          min.innerHTML = "0&nbsp";
          max.innerHTML = "&nbsp79";
          min.style.display = "block";
          max.style.display = "block";
          grad.style.display = 'block';
          leg.style.display = 'block';
          lin.style.display = 'block';
          grad.style.width = "190px";
          di.style.display = "none";
          d.style.display = "none";
        } else if (value == "2019_wind") {
          x.innerHTML = "<br>Wind Speed<br><br>The daily average of wind speed at 2 meters above the surface of the earth.<br><br>Sample Data Used :  01-01-2019 <br>Data Availability : 1984 - present.<br>Granularity :  Pincode<br>Temporal resolution : Daily<br>Primary Data Source : <i>NASA</i>";
          min.innerHTML = "0.491&nbsp m/s";
          max.innerHTML = "&nbsp8 m/s";
          min.style.display = "block";
          max.style.display = "block";
          grad.style.display = 'block';
          leg.style.display = 'block';
          lin.style.display = 'block';
          grad.style.width = "190px";
          di.style.display = "none";
          d.style.display = "none";
        } else if (value == "IBTrACS_Last3years") {
          x.innerHTML = "<br>Tropical Cyclone Tracks<br><br>Global tropical cyclone best track data from International Best Track Archive for Climate Stewardship (IBTrACS).<br><br>Data Availability :1980 - present.<br>Primary Data Source : <i>NCDC NOAA</i>";

          grad.style.display = 'none';
          min.style.display = "none";
          max.style.display = "none";
          d.style.display = "inline-block";
          d.style.background = "#8b48af";
          di.style.display = "inline-block";
          leg.style.display = 'block';
          lin.style.display = 'block';
          di.innerHTML = " Tropical Cyclone Tracks"
        }
        // } else if (value == "NLE_India_Geojsn") {
        //   x.innerHTML = "<br>Natural Loss Events<br><br>Geographical overview of natural loss events for the period from 1980 to 2018.<br><br>Data Availability : 1980 - 2018.<br><br>Primary Data Source : <i>NCEI NOAA </i>";
        //   grad.style.display = 'none';
        //   min.style.display = "none";
        //   max.style.display = "none";
        //   d.style.display = "inline-block";
        //   d.style.background = "#8b48af";
        //   di.style.display = "inline-block";
        //   di.innerHTML = "Meteorological events"
        //   met.style.display = "inline-block";
        //   met.style.background = "#f59b42";
        //   metinfo.style.display = "inline-block";
        //   metinfo.innerHTML = " Meteorological events"



        // } 
        else if (value == "climatological_events") {
          x.innerHTML = "<br>Climatological Natural Loss Events<br><br>Geographical overview of climatological natural loss events for the period from 1980 to 2018.<br><br>Data Availability : 1980 - 2018.<br>Primary Data Source : <i>NCEI NOAA </i>";
          grad.style.display = 'none';
          min.style.display = "none";
          max.style.display = "none";
          d.style.display = "inline-block";
          d.style.background = "#8b48af";
          di.style.display = "inline-block";
          leg.style.display = 'block';
          lin.style.display = 'block';
          di.innerHTML = " Climatological events"
        } else if (value == "meteorological_events") {
          x.innerHTML = "<br>Meteorological Natural Loss Events<br><br>Geographical overview of meteorological natural loss events for the period from 1980 to 2018.<br><br>Data Availability : 1980 - 2018.<br>Primary Data Source : <i>NCEI NOAA </i>";
          grad.style.display = 'none';
          min.style.display = "none";
          max.style.display = "none";
          d.style.display = "inline-block";
          d.style.background = "#8b48af";
          di.style.display = "inline-block";
          leg.style.display = 'block';
          lin.style.display = 'block';
          di.innerHTML = " Meteorological Events"
        } else if (value == "geophysical_events") {
          x.innerHTML = "<br>Geophysical Natural Loss Events<br><br>Geographical overview of  geophysical natural loss events for the period from 1980 to 2018.<br><br>Data Availability : 1980 - 2018.<br>Primary Data Source : <i>NCEI NOAA </i>";
          grad.style.display = 'none';
          min.style.display = "none";
          max.style.display = "none";
          d.style.display = "inline-block";
          d.style.background = "#8b48af";
          di.style.display = "inline-block";
          leg.style.display = 'block';
          lin.style.display = 'block';
          di.innerHTML = " Geophysical events"
        } else if (value == "hydrological_events") {
          x.innerHTML = "<br>Hydrological Natural Loss Events<br><br>Geographical overview of hydrological  natural loss events for the period from 1980 to 2018.<br><br>Data Availability : 1980 - 2018.<br>Primary Data Source : <i>NCEI NOAA </i>";
          grad.style.display = 'none';
          min.style.display = "none";
          max.style.display = "none";
          d.style.display = "inline-block";
          d.style.background = "#8b48af";
          di.style.display = "inline-block";
          leg.style.display = 'block';
          lin.style.display = 'block';
          di.innerHTML = " Hydrological events"
        } else if (value == "seismic_data") {
          x.innerHTML = "<br>Seismic Data<br><br>Event wise earthquake data for one year 01/04/2018 - 31/03/2019 .<br><br>Data Availability : 1980 - present<br>Primary Data Source : <i>National Center for Seismology India </i>";
          grad.style.display = 'none';
          min.style.display = "none";
          max.style.display = "none";
          d.style.display = "inline-block";
          d.style.background = "#8b48af";
          di.style.display = "inline-block";
          leg.style.display = 'block';
          lin.style.display = 'block';
          di.innerHTML = " Seismic Data"
        } else if (value == "2020_feb_no2") {
          x.innerHTML = "<br>NO2 Concentration <br><br>The daily average of NO2 concentration from satellite imagery<br><br>Sample Data Used : FEB-2020<br>Data Availability : 2018 - present<br>Granularity :  Pincode<br>Primary Data Source : <i>Sentinel-5P NRTI NO2 </i>";
          min.innerHTML = "3.40E&nbsp<br>mol/&#13217";
          max.innerHTML = "&nbsp3.91E<br>mol/&#13217";
          min.style.display = "block";
          max.style.display = "block";
          grad.style.display = 'block';
          leg.style.display = 'block';
          lin.style.display = 'block';
          grad.style.width = "165px"
          di.style.display = "none";
          d.style.display = "none";

        } else if (value == "GTI") {
          x.innerHTML = "<br>GTI - Global irradiation for optimally tilted surface <br><br>The average daily/yearly sum of Global Tilted Irradiation (GTI) covering a period of recent 20 years (1999-2018).<br><br>Spatial Resolution : 1 km<br>Primary Data Source : <i>Global Solar Atlas 2.0 </i>";
          min.innerHTML = "0&nbsp<br>Kwh/m2";
          max.innerHTML = "&nbsp2523<br>Kwh/m2";
          min.style.display = "block";
          max.style.display = "block";
          grad.style.display = 'block';
          leg.style.display = 'block';
          lin.style.display = 'block';
          grad.style.width = "190px";
          di.style.display = "none";
          d.style.display = "none";


        } else if (value == "OPTA") {
          x.innerHTML = "<br>OPTA - Optimum tilt of PV module to maximize the yearly yield <br><br>The average sum of Optimum tilt to maximize yearly yield [°] covering a period of recent 20 years (1999-2018).<br><br>Spatial Resolution : 1 km<br>Primary Data Source : <i>Global Solar Atlas 2.0 </i>";
          min.innerHTML = "7&nbsp\xB0";
          max.innerHTML = "&nbsp42\xB0";
          min.style.display = "block";
          max.style.display = "block";
          grad.style.display = 'block';
          leg.style.display = 'block';
          lin.style.display = 'block';
          grad.style.width = "190px";
          di.style.display = "none";
          d.style.display = "none";

        } else if (value == "coc") {
          x.innerHTML = "<br>Current Observed Weather Conditions <br><br>Primary Data Source : <i>DTN</i>";
          min.innerHTML = "7&nbsp";
          max.innerHTML = "&nbsp42";
          min.style.display = "none";
          max.style.display = "none";
          grad.style.display = 'none';
          leg.style.display = 'none';
          lin.style.display = 'block';
          di.style.display = "none";
          d.style.display = "none";
         

        }
        else if (value == "DEM") {
          x.innerHTML = "<br>Digital Elevation Model <br><br>Primary Data Source : <i>SRTM</i>";
          min.innerHTML = "0 m&nbsp";
          max.innerHTML = "&nbsp80 m";
          min.style.display = "block";
          max.style.display = "block";
          grad.style.display = 'block';
          leg.style.display = 'block';
          lin.style.display = 'block';
          grad.style.width = "190px";
          di.style.display = "none";
          d.style.display = "none";

         

        }




      }
      map.on('click', 'wms-test-layer', function(e) {
      var coordinates = e.features[0].geometry.coordinates.slice();
      var depth = e.features[0].properties.depthinkm;
      var magnitude = e.features[0].properties.mag;
      var isodate = e.features[0].properties.date;
      var reg=e.features[0].properties.region;
      
      // Ensure that if the map is zoomed out such that multiple
      // copies of the feature are visible, the popup appears
      // over the copy being pointed to.
      
      
      popup = new mapboxgl.Popup({closeOnClick: true})
      .setLngLat(e.lngLat)
      .setHTML("<h5>LIVE EARTHQUAKE DATA</h5><hr>" + "Date : " + isodate + "<br>Depth in Km : " +
                depth + "<br>Magnitude : " +
                magnitude + "<br>Region : " +
                reg)
      .addTo(map);
      });


    </script>


    <!-- <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script> 
    <script src="js/jquery.min.js"></script> -->
    <script src="js/popper.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/main.js"></script>
</body>

</html>  