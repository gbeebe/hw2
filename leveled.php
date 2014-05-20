<!doctype html>
<html>
	<head>
		<title>
			Zombie Fodder Level Editor
		</title>
		
	</head>
	<body>
		<div style="width: 200px; border-right: 1px solid black">
			<input type="textbox" id="fileName" /><br />
			<button id="btnOpen">Open</button>
			<button id="btnSave">Save</button>
			<hr>
			Dimmensions:<br />
			height: <input type="textBox" id="dimHeight" style="width: 50px;" /><br />
			<button id="btnUpdate">Update</button>
			<hr>
			Map Tile:<br />
			X: <input type="number" id="tileX" style="width: 50px;" />  Y: <input type="number" id="tileY" style="width: 50px;" /></br />
			Tileset Tile: <br />
			X: <input type="number" id="origX" style="width: 50px;" />  Y: <input type="number" id="origY" style="width: 50px;" /></br />
			<button id="btnAdd">Add</button><button id="btnDelete">Delete</button><button id="btnSnapTile">Snap</button>
			<hr>
			Object:<br />
			X: <input type="number" id="objX" style="width: 50px;" />  Y: <input type="number" id="objY" style="width: 50px;" /><br />
			Type: <input type="textBox" id="objType" style="width: 75px;" /><br />
			<button id="btnObjAdd">Add</button><button id="btnObjDelete">Delete</button><button id="btnObjTile">Snap</button>
			<hr>
			Ruler:<br />
			X: <input type="number" id="ruleX" style="width: 50px;" value="0"/>  Y: <input type="number" id="ruleY" style="width: 50px" value="0" /><br />
			<button id="btnMoveRule">Move</button>
			<hr>
			<button id="btnRefresh">Refresh</button>
			
		</div>
		
		<div style="position: absolute; top: 10px; left: 220px; width: 400px; height: 600px; overflow: scroll">
			<div id="holder"></div>
		</div>
		
		<div style="position: absolute; top: 10px; left: 650px;">
			<img src="res/sprites/tileset.png" />
		</div>	
	
		

		<script type="text/javascript" src="res/js/jquery.min.js"></script>
		<script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
		<script type="text/javascript" src="res/js/sprite.js"></script>
		
		<script>
			//$('#ruleX').spinner();
			
			spr_tiles = _addSprite('res/sprites/tileset.png');
			
			var setting = {width: 20, height: 800}
			var scaleX = 1;
			var scaleY = 1;
			
			var map = new Array(20);
			function clearMap() {
				for (m = 0; m < 20; m++) {
					map[m] = new Array(setting.height);
				}
			}
			clearMap();
			
			//var objects = [];
			
			var can = document.createElement('canvas');
				can.width = setting.width * 8 * 2; //window.innerWidth;
				can.height = setting.height * 8 * 2; // window.innerHeight; 
				can.id = 'game';
				can.style.position = 'absolute';
				can.style.backgroundColor = "#22ff22";
				
			var ctx = can.getContext("2d");
				ctx.webkitImageSmoothingEnabled = false; 
				ctx.mozImageSmoothingEnabled = false;
				ctx.imageSmoothingEnabled = false;
				ctx.globalCompositeOperation='copy';
				
			var buf = document.createElement('canvas');
				buf.width = setting.width *8;
				buf.height = setting.height *8;
			
			
				
						
			document.getElementById('holder').appendChild(can);
		//open button
			document.getElementById("btnOpen").onclick = function() {openMap();};
			function openMap() {
				$.ajax({
					type: 'GET',
					url: $('#fileName').val(),
					success: function(d) {
						console.log(d);
						tmpMap = JSON.parse(d).map;
						clearMap();
						for (i = 0; i < tmpMap.length; i++) {
							map[tmpMap[i].mX][tmpMap[i].mY] = {'x' : tmpMap[i].x, 'y' : tmpMap[i].y, 'o' : tmpMap[i].o};
						}
						//objects = JSON.parse(d).o;
						console.log (JSON.parse(d));
						refresh();
					},
					error: function(d) {
						console.log("error " + d);
					}
					
				});	
			}
			
		//save button
			document.getElementById("btnSave").onclick = function() {saveMap();};
			function saveMap() {
				var tmpData = {};
				var tmpMap = [];
				
				for (x = 0; x < 20; x++) {
					for (y = 0; y < setting.height; y++) {
						if (map[x][y] !== undefined && map[x][y] !== null) {
							tmpMap[tmpMap.length] = {"mX" : x, "mY" : y, "x" : map[x][y].x, "y" : map [x][y].y, "o" : map[x][y].o};
						}
					}
				}
				
				tmpData.map = tmpMap;
				//tmpData.o = objects;
				//console.log (tmpData.map);
				
				var data = JSON.stringify(tmpData);
				//console.log (data);
				
				$.ajax({
					type: 'POST',
					// make sure you respect the same origin policy with this url:
					// http://en.wikipedia.org/wiki/Same_origin_policy
					url: 'saveMap.php',
					data: { 
						'data': data, 
						'fName': $('#fileName').val()
					},
					success: function(msg){
						console.log(msg);
					}
				});
				
			}
				
		
		
		//add tile button		
			document.getElementById("btnAdd").onclick = function() {addTile();};
			function addTile() {
				var x = $('#tileX').val();
				var y = $('#tileY').val();
				var ox = $('#origX').val();
				var oy = $('#origY').val();
				
				map[x][y] = {'x': ox, 'y': oy, 'o' : 0};
				refresh();
			
			}
		//snap tile button
			document.getElementById("btnSnapTile").onclick = function() {snapTile();};
			function snapTile() {
				$('#tileX').val($('#ruleX').val());
				$('#tileY').val($('#ruleY').val());			
			}
			
		//refresh button
			document.getElementById("btnRefresh").onclick = function() {refresh();};
			function refresh() {
				bufCtx = buf.getContext("2d");
				//clear the buffer
				bufCtx.beginPath();
				bufCtx.clearRect(0, 0, setting.width*8, setting.height*8);
				bufCtx.closePath();
							
				var spr = _getSprite(spr_tiles);			
				for (x = 0; x < 20; x++) {
					for (y = 0; y < setting.height; y++) {
						if (map[x][y] !== undefined && map[x][y] !== null) {
							bufCtx.drawImage(spr, map[x][y].x * 8, map[x][y].y * 8, 8, 8, x * 8, (setting.height * 8) - 8 - y * 8, 8, 8);						
							
						}
					}
				}
				
				
								
				bufCtx.beginPath();
					bufCtx.fillStyle = 'rgba(255, 0, 0, 0.5)';
					bufCtx.strokeStyle = '#ff0000';
					bufCtx.rect(ruleX * 8, (setting.height * 8) - 8 - ruleY * 8, 8, 8);
					bufCtx.fill();
					bufCtx.stroke();
				bufCtx.closePath();
				
				ctx.drawImage(buf, 0, 0, setting.width * 8 *2, setting.height*8*2);
			
			}
			
		//delete tile button
			document.getElementById("btnDelete").onclick = function() {rmTile();};
			function rmTile() {
				var x = $('#tileX').val();
				var y = $('#tileY').val();
				map[x][y] = undefined;
				refresh();
			}
		
		//add object button
			document.getElementById("btnObjAdd").onclick = function() {addObject();};
			function addObject() {
				var oX = $('#objX').val();
				var oY = $('#objY').val();
				var oT = $('#objType').val();
				switch(oT) {
					case 'g1':
						x = 15;
						y = 1;
						break;
					default:
						x = 15;
						y = 0;
						break;		
				}	
				map[oX][oY] = {'x': x, 'y': y, 'o' : oT};			
				
				refresh();
			
			}
			
		//delete object button
			document.getElementById("btnObjDelete").onclick = function() {delObject();};
			function delObject() {
				console.log("del obj");
				var x = $('#objX').val();
				var y = $('#objY').val();
				map[x][y] = undefined;
				refresh();
			}	
		//snap object button
			document.getElementById("btnObjTile").onclick = function() {snapObj();};
			function snapObj() {
				$('#objX').val($('#ruleX').val());
				$('#objY').val($('#ruleY').val());			
			}
					
		//ruler
			var ruleX = 0;
			var ruleY = 0;
			document.getElementById("ruleX").onchange = function() {ruleMove();};
			document.getElementById("ruleY").onchange = function() {ruleMove();};
			document.getElementById("btnMoveRule").onclick = function() {ruleMove();};
			function ruleMove() {
				var x = $('#ruleX').val();
				var y = $('#ruleY').val();
				
				ruleX = x;
				ruleY = y;
				refresh();			
			}	
		</script>
	</body>
</html>
