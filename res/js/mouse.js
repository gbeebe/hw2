var touchStartX, touchStartY;
var mouseX, mouseY;
var touchX, touchY;
var mouseDown;
var whichButton; 

function touchStart(e) {
	mouseDown = true;
	whichButton = 0;
	if (e.which) {
		whichButton = e.which;
	} else if (e.button) {
	   if (e.button == 2) { whichButton = 2; }
	   if (e.button == 4) { whichButton = 3; }
	}

	x = e.x || e.targetTouches[0].pageX;
	y = e.y || e.targetTouches[0].pageY;
	
	mouseX = (x - can.offsetLeft) ;
	mouseY = (y - can.offsetTop) ;	
	
	if (e.target.id == 'game') {
		
		touchStartX = mouseX / scaleX;
		touchStartY = mouseY / scaleY;	
		
		touchX = mouseX / scaleX;
		touchY = mouseY / scaleY;
		//console.log(touchX);									
	}	
	
	
	
	e.preventDefault();
}
document.body.addEventListener('mousedown', touchStart, false);
document.body.addEventListener("touchstart", touchStart, false);

function touchMove(e) {
	//console.log(e);
	x = e.x || e.targetTouches[0].pageX;
	y = e.y || e.targetTouches[0].pageY;
	
	mouseX = (x - can.offsetLeft);
	mouseY = (y - can.offsetTop);
	
	//if (e.target.id == 'game' && mouseDown == true) {
		
		
		touchX = mouseX / scaleX;
		touchY = mouseY / scaleY;
	//}
	
	
	
	e.preventDefault();
}
document.body.addEventListener('mousemove', touchMove, false);
document.body.addEventListener("touchmove", touchMove, false);

function touchEnd(e) {
	mouseDown = false;
	e.preventDefault();	
}	
document.body.addEventListener('mouseup', touchEnd, false);
document.body.addEventListener("touchend", touchEnd, false);
document.body.addEventListener('mouseout', touchEnd, false);
