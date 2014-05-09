var _sprite = [];

function _spriteClass(src) {
	this.loaded = false;
	this.img = new Image();			
	this.img.that = this;			
	this.img.onload = function() {
		this.that.loaded = true;
	}	

	this.img.onprogress = function(e) {
		if (e.lengthComputable) {
			//console.log(e.loaded / e.total * 100);
		}else{
			//console.log("sorry");
		}	
	}
			
	this.img.src = src;
}

function _addSprite(imgSrc) {
	var len = _sprite.length
	_sprite[len] = new _spriteClass(imgSrc);	
	return len;
}

function _allSpritesLoaded() {
	var loaded = true;		//innocent until proven otherwise.
	var count = 0;
	for (t = 0; t < _sprite.length; t++) {
		if (_sprite[t].loaded == false) {
			loaded = false;
		}else{
			count++;
		}
	}
	if (loaded == true) {
		return loaded;
	}else{
		return (count / _sprite.length);
	}	
}

function _waitSprites(callback) {
	if (_allSpritesLoaded() == true) {
		callback();
	}else{
		setTimeout(function(){_waitSprites(callback)}, 100);
	}
}

function _getSprite(index) {
	return _sprite[index].img;
}
