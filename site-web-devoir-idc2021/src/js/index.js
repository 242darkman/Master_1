
"use strict";

let widget = document.getElementById("image");

let butto = document.getElementById("btn");
var output = document.getElementById('output');


let request = new XMLHttpRequest();
request.open("POST","index.php" );


butto.onclick = function(e){

	let formData = new FormData(document.getElementById("form"));
	e.preventDefault();
	request.send(formData);

	var image = widget.files[0];

	 

   let img = new File();

    
      output.appendChild(img);

      console.log(image);
      openFile(image, img);
      createObURl(image, img);
    
   		
}



function openFile( image, img){
	   let reader = new FileReader();
      reader.addEventListener("load", (event)=> {
      	let dataURL = reader.result;
        
     
     	img.src = dataURL;
      });
	 reader.readAsDataURL(image);
		
}

function createObURl(image, img){
	
	 img.src = window.URL.createObjectURL(image);

   

}


 
