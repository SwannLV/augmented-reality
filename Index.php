
<!DOCTYPE html>
<html>
<head>
<title>AWE Marker AR demo</title>
<meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0" />
<meta charset="utf-8"/>
<style type="text/css">
* {
	margin: 0;
	padding: 0;
}
#container {
	position: absolute;
	left: 0;
	top: 0;
	bottom: 0;
	right: 0;
	overflow: hidden;
}
</style>
</head>
<body>
<div id="container"></div>
<script type="text/javascript" src="../../js/awe.js"></script>
<script type="text/javascript">
  window.addEventListener('load', function() {
    window.awe.init({
      device_type: awe.AUTO_DETECT_DEVICE_TYPE,
      settings: {
      	container_id: 'container',
        default_camera_position: { x:0, y:0, z:0 },
        default_lights:[
          {
            id: 'point_light',
            type: 'point',
            color: 0xFFFFFF,
          },
        ],
      },
      ready: function() {
        awe.util.require([
          {
            capabilities: ['gum','webgl'],
            files: [ 
              [ '../../js/awe-standard-dependencies.js', '../../js/awe-standard.js'],
              'awe-jsartoolkit-dependencies.js',
              'awe.marker_ar.js',
            ],
            success: function() { 
					awe.setup_scene();			
			        awe.pois.add({ id:'poi_1', position: { x:0, y:0, z:0 }, visible: true });
			        awe.projections.add({ 
                        id:'projection_1', 
                        //geometry: { shape: 'planegeometry', width:400, height:400 },//
                        //geometry:{ shape: 'cube', x:330, y:0.5, z:300 },
                        geometry:{ shape: 'plane', height:300, width:330 },
                        material:{ type: 'phong', color: 0xFFFFFF, transparent: true }, 
                        texture: { path: 'inverted-pasta.png' },
                        rotation:{ x:45, y:0, z:0 }
                        //rotation:{ x:0, y:0, z:0 }
			        }, { poi_id: 'poi_1' });
			        awe.events.add([{
								id: 'ar_tracking_marker',
								device_types: {
									pc: 1,
									android: 1
								},
								register: function(handler) {
									window.addEventListener('ar_tracking_marker', handler, false);
								},
								unregister: function(handler) {
									window.removeEventListener('ar_tracking_marker', handler, false);
								},
								handler: function(event) {
									if (event.detail) {
										if (event.detail['64']) { // we are mapping marker #64 to this projection
											awe.pois.update({
												data: {
												    visible: true,
                                                    position: { x:0, y:0, z:100 },
													matrix: event.detail['64'].transform
												},
												where: {
													id: 'poi_1'
												}
											});
										}
										else {
											awe.pois.update({
												data: {
													visible: false
												},
												where: {
													id: 'poi_1'
												}
											});
										}
										awe.scene_needs_rendering = 1;
									}
								}
							}])
			      },
          },
          {
            capabilities: [],
            success: function() { 
		          document.body.innerHTML = '<p>Try this demo in the latest version of Chrome or Firefox on a PC or Android device</p>';
            },
          },
        ]);
      }
    });
  });
</script>
</body>
</html>
