function SpeedMeter(intervall){
	this.values = new Array(2);
	this.times = new Array(2);
	this.intervall = intervall;
	this.initalized = false;
	this.currSpeed = "calculating...";
	this.count = 0;
	
	this.mesure = function(bytes){
				if(!this.initalized){
					for(var i=0; i<this.intervall; i++) this.values[i] = bytes;
					this.initalized = true;
					this.currSpeed = "calculating...";
				}
			
				if(this.count == 0){
					this.values[0] = bytes;
					this.times[0] = new Date().getTime();
				}
				
				if(this.count == intervall-1) {
					this.values[1] = bytes;
					this.times[1] = new Date().getTime();
					var diff1 = this.values[1]-this.values[0];
					var diff2 = this.times[1]-this.times[0];
					this.currSpeed = (Math.round(( (diff1/1000) / (diff2/1024) )*10) / 10) + " KB/s";
					this.count = 0;
				}
				
				this.count++;
			  }
	
					
}
