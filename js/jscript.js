
	var date = new Date();
	var formattedDate = date.toLocaleDateString('en-GB', {
  		day: 'numeric', 
  		month: 'short', 
  		year: 'numeric'
	}).replace(/ /g, '-');
	document.getElementById("sdate").value = formattedDate;

