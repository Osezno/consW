;(function(API) {
'use strict'
API.myText = function(txt, options, x, y) {
			options = options ||{};
			if( options.align == "center" ){
				// Get current font size
				var fontSize = this.internal.getFontSize();
	
				// Get page width
				var pageWidth = this.internal.pageSize.width;
	
				var txtWidth = this.getStringUnitWidth(txt)*fontSize/this.internal.scaleFactor;
	
				// Calculate text's x coordinate
				x = ( pageWidth - txtWidth ) / 2;
			} else if(options.align == "right"){				
				// Get current font size
				var fontSize = this.internal.getFontSize();
	
				// Get page width
				var pageWidth = this.internal.pageSize.width;

                console.log('text right:',txt);

                if(txt.indexOf('\r\n') > -1) {
                    var longest_width = 0;
                    var text_lines    = txt.split("\r\n");

                    for(var text_i = 0; text_i >= text_lines.length; text_i++){
                        console.log('text_line', text_lines[text_i]);
                        var text_length = this.getStringUnitWidth(text_lines[text_i]) * fontSize / this.internal.scaleFactor;

                        if(text_length > longest_width){
                            longest_width = text_length;
                        }
                    }

                    txtWidth = longest_width;
                } else {
                    txtWidth = this.getStringUnitWidth(txt) * fontSize / this.internal.scaleFactor;
                }
				
				// Calculate text's x coordinate
				x = (typeof x != "undefined" ? (( pageWidth - txtWidth ) - x) : ( pageWidth - txtWidth ) );
			}
	
			// Draw text at x,y
			this.text(txt,x,y);
		}

})(jsPDF.API);

// jsPDF addon
	(function(API){
		
	})(jsPDF.API);
