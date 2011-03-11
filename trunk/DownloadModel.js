function DownloadModel(link){
	this.started = false;
	this.complete = false;
	this.aborted = false;
	this.progress = 1;
	this.link = link;
	this.ID;
}


