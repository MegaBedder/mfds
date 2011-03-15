function DownloadModel(link){
	this.started = false;
	this.complete = false;
	this.aborted = false;
	this.progress = 1;
	this.link = link;
	this.ID;
	this.selected = false;
	this.downloaded = 0;
	this.downloadSize = 0;
	this.valuesSet = false;
	this.unit = "Byte"
	
	if(link.length <= 40)
		this.displayLink = link;
	else
		this.displayLink = "..."+link.substring(link.length-37,link.length);
	
}


