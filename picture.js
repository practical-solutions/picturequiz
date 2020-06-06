function writeMessage(canvasObj,evt,msgDiv) {
	var rect = canvasObj.getBoundingClientRect();
	message = Math.round(evt.clientX - rect.left)+", "+Math.round(evt.clientY - rect.top);
    msgDiv.value = msgDiv.value+message;
    msgDiv.focus();
}

/* OnKeyUp-Event für das Text-Formular im Edit-Mode.
   Ersetzt Buchstaben durch Tags */
function setTags(id){
	textDiv = document.getElementById(id);
	textDiv.value = textDiv.value.replace(/y/g, "{button}");
	textDiv.value = textDiv.value.replace(/x/g, "{/button}");
}

function canvas_button(){
	this.loc = new Array();
	this.visible = true;
		
	this.default_caption = "[klicken]";
	this.caption_visible = true;
		
	this.set_dimensions = function(x1,y1,x2,y2){
		this.loc = [x1,y1,x2,y2];
	}
		
	this.draw = function(context_obj){
		//context_obj.globalAlpha = 1;
		if (this.visible){
			
			context_obj.beginPath();
			context_obj.rect(this.loc[0], this.loc[1],this.loc[2]-this.loc[0],this.loc[3]-this.loc[1]);
			context_obj.lineWidth = 1;
			context_obj.strokeStyle = 'black';
			context_obj.fillStyle = 'yellow';
			context_obj.fill();
			context_obj.stroke();
			
			if (this.caption_visible){	
				this.paint_centered_wrap(
										context_obj,
										this.loc[0],
										this.loc[1],
										this.loc[2]-this.loc[0],
										this.loc[3]-this.loc[1],
										this.default_caption,
										0,10
										);
			}
			
		} else {
			//context_obj.globalAlpha = 0.2;
			context_obj.beginPath();		
			context_obj.lineWidth = 1;
			//context_obj.fillStyle = "yellow";
			//context_obj.fillRect(this.loc[0], this.loc[1],this.loc[2]-this.loc[0],this.loc[3]-this.loc[1]);
			context_obj.rect(this.loc[0], this.loc[1],this.loc[2]-this.loc[0],this.loc[3]-this.loc[1]);
			context_obj.stroke();
		}		
	}
	
	this.clicked = function(x,y,scale){
		if (((x>this.loc[0]*scale) && (x<this.loc[2]*scale)) && ((y>this.loc[1]*scale) && (y<this.loc[3]*scale))) {
			if (this.visible==true) {
				this.visible = false;
			} else {
				this.visible = true;
			}
			
			return true;
		} else {
			return false;
		}
	}
		
		
	this.paint_centered_wrap = function(ctx2d, x, y, w, h, text, fh, spl) {
    
		var Paint = {
			RECTANGLE_STROKE_STYLE : 'black',
			VALUE_FONT : '15px Arial',
			VALUE_FILL_STYLE : 'yellow'
		}
		/*
		* @param ctx   : The 2d context 
		* @param mw    : The max width of the text accepted
		* @param font  : The font used to draw the text
		* @param text  : The text to be splitted   into 
		*/
		var split_lines = function(ctx, mw, font, text) {
			// We give a little "padding"
			// This should probably be an input param but for the sake of simplicity we will keep it this way
			mw = mw - 10;
			// We setup the text font to the context (if not already)
			ctx2d.font = font;
			// We split the text by words 
			var words = text.split(' ');
			var new_line = words[0];
			var lines = [];
			for(var i = 1; i < words.length; ++i) {
				if (ctx.measureText(new_line + " " + words[i]).width < mw) {
					new_line += " " + words[i];
				} else {
					lines.push(new_line);
					new_line = words[i];
				}
			}
			lines.push(new_line);
			// DEBUG 
			// for(var j = 0; j < lines.length; ++j) {
			//    console.log("line[" + j + "]=" + lines[j]);
			// }
			return lines;
		}
    
		if (ctx2d) {
			// Paint text
			var lines = split_lines(ctx2d, w, Paint.VALUE_FONT, text);
			// Block of text height
			var both = lines.length * (fh + spl);
			if (both >= h) {
				// We won't be able to wrap the text inside the area
				// the area is too small. We should inform the user 
				// about this in a meaningful way
			} else {
				// We determine the y of the first line
				var ly = (h - both)/2 + y + spl*lines.length;
				var lx = 0;
				for (var j = 0, ly; j < lines.length; ++j, ly+=fh+spl) {
					// We continue to centralize the lines
					lx = x+w/2-ctx2d.measureText(lines[j]).width/2;
					// DEBUG 
					console.log("ctx2d.fillText('"+ lines[j] +"', "+ lx +", " + ly + ")");
					ctx2d.fillStyle = 'black';
					ctx2d.fillText(lines[j], lx, ly);
				}
			}
		} else {
			// Do something meaningful
		}
	}
}

function tagged_img(canvasObj,image){
	this.button = new Array();
	
	this.fullWidth = 0;
	this.canvasObj = canvasObj;
	this.contextObj = this.canvasObj.getContext('2d');
	this.imageObj = image;
	
	this.editMode = false;
	
	this.show_btn_caption = true;
	
	this.quelle = "";
	this.addQuelle = function(q) {
		this.quelle = q;
	}	
	
	this.add_button = function(x1,y1,x2,y2){
		this.button.push (new canvas_button());
		this.button[this.button.length-1].caption_visible = this.show_btn_caption;
		this.button[this.button.length-1].set_dimensions(x1,y1,x2,y2);
	}
	
	this.paint = function(){
		this.contextObj.drawImage(this.imageObj, 0, 0);
		
		for (c=0;c<this.button.length;c++){
			this.button[c].draw(this.contextObj);
		}
		
		// Quellenangabe
		if (this.quelle != "") {
			this.contextObj.fillStyle = 'black';
			this.contextObj.font = "9px Arial";
			this.contextObj.fillText("Quelle: "+this.quelle,2,this.canvasObj.height-9);
		}
	}
	
	this.click = function(evt,width){
		scale = width/this.fullWidth;

		var rect = this.canvasObj.getBoundingClientRect();
		x = evt.clientX - rect.left;
		y = evt.clientY - rect.top;
		
		for (c=0;c<this.button.length;c++){
			this.button[c].clicked(x,y,scale);	
		}
		
		this.paint();
	}
	
}

// needed for multiple instances on a page
var tagged_image_canvas = new Array();
var tagged_image_context = new Array();
var tagged_image_pic = new Array();
var tagged_image_main = new Array();
var tagged_image_instance = -1;
