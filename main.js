function wstrAddNewTrack(element){
	// without jQuery
	var wrap = document.getElementById("wstr_form");
	var div = document.createElement('div');
	div.className = "wstr_track";

	var label = document.createElement('label');
	label.innerHTML="Widget code: ";

	var textarea = document.createElement('textarea');
	textarea.className="wstr_value";
	textarea.name="wstr_options[]";
	label.appendChild(textarea);

	var button = document.createElement('button');
	button.className = "wstr_remove";
	button.innerHTML = "X";
	button.type = "button";
	button.onclick = function(){wstrRemoveTrack(button)};
	
	div.appendChild(label);

	div.appendChild(button);

	wrap.insertBefore(div,wrap.childNodes[5]);

	return false;
}

function wstrRemoveTrack(element){
	// without jQuery - old, removing by id
	// var wrap = document.getElementById("wstr_form");
	// var div = wrap.children[3+id];
	// wrap.removeChild(div);
	// return false;

	var div = element.parentNode;
	var wrap = div.parentNode;
	wrap.removeChild(div);
	return false;
}